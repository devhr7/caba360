<script setup lang="ts">
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

interface InvoiceData {
    number: string;
    issuer?: string;
    nit?: string;
    date?: string;
    total?: string;
    cufe?: string;
}

// Props
const props = defineProps<{
    data?: unknown;
}>();

// Composables
const toast = useToast();

// Datos de factura
const invoiceNumber = ref<string>("");
const invoiceData = ref<InvoiceData | null>(null);
const facturaData = ref<XMLDocument | null>(null);
const fileUpload = ref<InstanceType<any> | null>(null);

// Namespaces UBL
const NS = {
    inv: "urn:oasis:names:specification:ubl:schema:xsd:Invoice-2",
    cbc: "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2",
    cac: "urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2",
};

// Factura interna (XML del Invoice) e ítems extraídos
const invoiceDoc = ref<XMLDocument | null>(null);
type InvoiceItem = {
    code: string;
    description: string;
    quantity: string;
    unit: string;
    price: string; // con moneda si aplica
    tax: string; // con moneda si aplica
    lineTotal: string; // total línea sin impuestos
    total: string; // total con impuestos si disponible
};
const invoiceItems = ref<InvoiceItem[]>([]);

const getFirstText = (parent: Element | Document, ns: string, tag: string): string | undefined => {
    const el = (parent as Document).getElementsByTagNameNS
        ? (parent as Document).getElementsByTagNameNS(ns, tag)[0]
        : (parent as Element).getElementsByTagNameNS(ns, tag)[0];
    return el?.textContent?.trim() || undefined;
};

/**
 * Extrae el número de factura del documento XML
 */
const extractInvoiceNumber = (xmlDoc: XMLDocument): string => {
    try {
        // Primero buscar ParentDocumentID (número real de la factura)
        const parentDocId = xmlDoc.querySelector("cbc\\:ParentDocumentID");
        if (parentDocId?.textContent?.trim()) {
            return parentDocId.textContent.trim();
        }

        // Luego buscar en namespaces estándar UBL
        const namespaces = NS;

        // Intentar obtener el ID de la factura
        let idElement = xmlDoc.querySelector("cbc\\:ID");
        if (idElement) {
            return idElement.textContent?.trim() || "Sin número";
        }

        // Búsqueda alternativa por namespace
        const parentDocElements = xmlDoc.getElementsByTagNameNS(
            namespaces.cbc,
            "ParentDocumentID"
        );

        if (parentDocElements.length > 0 && parentDocElements[0].textContent) {
            return parentDocElements[0].textContent.trim();
        }

        const allIdElements = xmlDoc.getElementsByTagNameNS(
            namespaces.cbc,
            "ID"
        );

        if (allIdElements.length > 0 && allIdElements[0].textContent) {
            return allIdElements[0].textContent.trim();
        }

        // Última opción: búsqueda genérica
        const genericId = xmlDoc.querySelector("[*|id], [*|ID]");
        if (genericId?.textContent?.trim()) {
            return genericId.textContent.trim();
        }

        return "Sin número";
    } catch (error) {
        console.error("Error al extraer número de factura:", error);
        return "Sin número";
    }
};

/**
 * Extrae información del encabezado de la factura
 */
const extractInvoiceHeader = (xmlDoc: XMLDocument): InvoiceData => {
    const data: InvoiceData = {
        number: extractInvoiceNumber(xmlDoc),
    };

    try {
        // Extraer fecha de emisión (si está en documento externo)
        const issueDate = xmlDoc.querySelector("cbc\\:IssueDate");
        if (issueDate?.textContent) {
            data.date = issueDate.textContent.trim();
        }

        // Extraer nombre del emisor usando namespaces
        // Primero intentar desde AccountingSupplierParty (documento interno Invoice)
        let supplierName = getFirstText(xmlDoc, NS.cac, "AccountingSupplierParty");
        if (!supplierName) {
            // Fallback: buscar RegistrationName directamente en SenderParty
            const senderParties = xmlDoc.getElementsByTagNameNS(NS.cac, "SenderParty");
            if (senderParties.length > 0) {
                const regNames = senderParties[0].getElementsByTagNameNS(NS.cbc, "RegistrationName");
                if (regNames.length > 0) {
                    supplierName = regNames[0].textContent?.trim();
                }
            }
        }
        if (supplierName) {
            data.issuer = supplierName;
        }

        // Extraer NIT (CompanyID) del emisor usando namespaces
        let nit: string | undefined;
        const senderParties = xmlDoc.getElementsByTagNameNS(NS.cac, "SenderParty");
        if (senderParties.length > 0) {
            const companyIds = senderParties[0].getElementsByTagNameNS(NS.cbc, "CompanyID");
            if (companyIds.length > 0) {
                nit = companyIds[0].textContent?.trim();
            }
        }
        if (nit) {
            data.nit = nit;
        }

        // Extraer monto total
        const totalAmount = xmlDoc.querySelector(
            "cac\\:LegalMonetaryTotal cbc\\:PayableAmount"
        );
        if (totalAmount?.textContent) {
            data.total = totalAmount.textContent.trim();
        }

        // Extraer CUFE (UUID)
        const uuids = xmlDoc.getElementsByTagNameNS(NS.cbc, "UUID");
        if (uuids.length > 0) {
            const cufe = uuids[0].textContent?.trim();
            if (cufe) {
                data.cufe = cufe;
            }
        }
    } catch (error) {
        console.error("Error al extraer información de factura:", error);
    }

    return data;
};

/**
 * Obtiene y parsea el XML de la factura interna (Invoice) que viene en CDATA dentro de cbc:Description
 */
const parseInnerInvoice = (outerDoc: XMLDocument): XMLDocument | null => {
    const descNodes = outerDoc.getElementsByTagNameNS(NS.cbc, "Description");
    for (let i = 0; i < descNodes.length; i++) {
        const raw = descNodes[i].textContent || "";
        if (raw.includes("<Invoice")) {
            const parsed = new DOMParser().parseFromString(raw, "text/xml");
            const isParseError =
                parsed.documentElement.tagName.toLowerCase() === "parsererror" ||
                parsed.getElementsByTagName("parsererror").length > 0;
            if (!isParseError) return parsed;
        }
    }
    return null;
};

/**
 * Extrae los ítems de la factura interna (namespaces UBL)
 */
const extractInvoiceItems = (doc: XMLDocument): InvoiceItem[] => {
    const result: InvoiceItem[] = [];
    const lines = doc.getElementsByTagNameNS(NS.cac, "InvoiceLine");
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i];
        const code = getFirstText(line, NS.cbc, "ID") ?? "N/A";

        // Descripción: normalmente está en cac:Item/cbc:Description
        let description = "N/A";
        const itemEl = line.getElementsByTagNameNS(NS.cac, "Item")[0];
        if (itemEl) {
            const d = itemEl.getElementsByTagNameNS(NS.cbc, "Description")[0]?.textContent?.trim();
            if (d) description = d;
        }
        if (description === "N/A") {
            const d2 = getFirstText(line, NS.cbc, "Description");
            if (d2) description = d2;
        }

        // Cantidad y unidad
        let quantity = "";
        let unit = "";
        const qtyEl = line.getElementsByTagNameNS(NS.cbc, "InvoicedQuantity")[0];
        if (qtyEl) {
            quantity = qtyEl.textContent?.trim() || "";
            unit = qtyEl.getAttribute("unitCode") || "";
        }

        // Precio unitario
        let price = "N/A";
        const priceEl = line.getElementsByTagNameNS(NS.cac, "Price")[0];
        if (priceEl) {
            const pNode = priceEl.getElementsByTagNameNS(NS.cbc, "PriceAmount")[0];
            const pVal = pNode?.textContent?.trim();
            if (pVal) price = pVal; // sin moneda
        }

        // Total de la línea (sin impuestos)
        let lineTotal = "N/A";
        const lineTotNode = line.getElementsByTagNameNS(NS.cbc, "LineExtensionAmount")[0];
        const lineTotVal = lineTotNode?.textContent?.trim();
        const lineTotCur = lineTotNode?.getAttribute("currencyID") || "";
        if (lineTotVal) lineTotal = lineTotVal; // sin moneda

        // Impuestos de la línea (si vienen informados)
        let tax = "";
        const taxTotalEl = line.getElementsByTagNameNS(NS.cac, "TaxTotal")[0];
        if (taxTotalEl) {
            const taxAmtNode = taxTotalEl.getElementsByTagNameNS(NS.cbc, "TaxAmount")[0];
            const taxVal = taxAmtNode?.textContent?.trim();
            if (taxVal) tax = taxVal; // sin moneda
        }

        // Total con impuestos (si se puede calcular)
        let total = lineTotal;
        const num = (s: string) => parseFloat(s.replace(/[^0-9.,-]/g, "").replace(",", "."));
        if (lineTotVal && tax) {
            const taxNum = num(tax);
            const lineNum = num(lineTotVal);
            if (!isNaN(taxNum) && !isNaN(lineNum)) {
                const sum = (lineNum + taxNum).toFixed(2);
                total = sum; // sin moneda
            }
        }

        result.push({ code, description, quantity: quantity || "N/A", unit, price, tax: tax || "N/A", lineTotal, total });
    }
    return result;
};

/**
 * Procesa el archivo XML cargado
 */
const processInvoiceFile = async (file: File): Promise<void> => {
    try {
        if (!file.name.endsWith(".xml")) {
            throw new Error("Por favor selecciona un archivo XML válido");
        }

        const text = await file.text();
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(text, "text/xml");

        // Validar XML
        if (xmlDoc.documentElement.tagName === "parsererror") {
            throw new Error("El archivo XML no es válido");
        }

        // Extraer datos
        const data = extractInvoiceHeader(xmlDoc);
        invoiceData.value = data;
        invoiceNumber.value = data.number;
        facturaData.value = xmlDoc;

        // Parsear XML interno de la factura (Invoice) e ítems
        const inner = parseInnerInvoice(xmlDoc);
        invoiceDoc.value = inner;
        invoiceItems.value = inner ? extractInvoiceItems(inner) : [];

        // Fallback de fecha: si falta en el contenedor, tomar del Invoice interno
        if (inner && (!invoiceData.value?.date || invoiceData.value.date === '')) {
            const innerDate = getFirstText(inner, NS.cbc, 'IssueDate');
            if (innerDate && invoiceData.value) {
                invoiceData.value = { ...invoiceData.value, date: innerDate };
            }
        }

        toast.add({
            severity: "success",
            summary: "Éxito",
            detail: `Factura ${data.number} leída correctamente`,
            life: 3000,
        });
    } catch (error) {
        const message = error instanceof Error ? error.message : "Error al procesar el archivo";
        console.error("Error al procesar factura:", error);
        toast.add({
            severity: "error",
            summary: "Error",
            detail: message,
            life: 3000,
        });
    }
};

/**
 * Calcula el total sumando todos los line.total
 */
const calculateTotalInvoice = (): string => {
    if (invoiceItems.value.length === 0) return 'N/A';

    const total = invoiceItems.value.reduce((sum, item) => {
        const num = parseFloat(item.total.replace(/[^0-9.,-]/g, '').replace(',', '.'));
        return sum + (isNaN(num) ? 0 : num);
    }, 0);

    return total.toFixed(2);
};

/**
 * Formatea un número con separadores de miles (.) y decimales (,)
 * Ej: 1234567.89 → 1.234.567,89
 */
const formatCurrency = (value: string): string => {
    const num = parseFloat(value.replace(/[^0-9.,-]/g, '').replace(',', '.'));
    if (isNaN(num)) return value;

    const parts = num.toFixed(2).split('.');
    const integer = parts[0];
    const decimal = parts[1];

    const formatted = integer.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return `${formatted},${decimal}`;
};

/**
 * Manejador del evento de selección de archivo
 */
const handleFileSelect = (event: any): void => {
    const files = event.files;
    if (files && files.length > 0) {
        processInvoiceFile(files[0]);
    }
};


</script>

<template>

    <Head title="Cump Labor Campo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Importar Facturas -
                <a :href="route('importarfactura.index')" class="text-teal-800 hover:text-teal-600"> .</a>
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <Card>
                        <template #title>Importar Facturas XML</template>
                        <template #content>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <FileUpload ref="fileUpload" mode="basic" name="factura"
                                        accept=".xml,application/xml" :auto="false" :show-upload-button="false"
                                        :show-cancel-button="false" choose-label="Seleccionar Factura XML"
                                        @select="handleFileSelect" />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Selecciona un archivo XML para leer la factura
                                    </p>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <Card v-if="invoiceData" class="mt-4">

                        <template #content>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-blue-50 rounded p-3 border border-blue-200" v-if="invoiceData.cufe">
                                    <span class="text-sm font-semibold text-blue-900">CUFE:</span>
                                    <div class="text-xs text-blue-800 break-all mt-1 font-mono">{{ invoiceData.cufe }}
                                    </div>
                                </div>
                                <Chip :label="invoiceData.number" />

                            </div>
                            <Card class="mt-4">
                                <template #content>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="bg-gray-50 rounded p-3">Número Factura:
                                            <Chip :label="invoiceData.number" />
                                        </div>
                                        <div class="bg-gray-50 rounded p-3">Fecha Emisión:
                                            <Chip :label="invoiceData.date || 'N/A'" />
                                        </div>
                                        <div class="bg-gray-50 rounded p-3">Valor Total:
                                            <Chip :label="formatCurrency(calculateTotalInvoice())" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 mt-3">
                                        <div class="bg-gray-50 rounded p-3">Razón Social:
                                            <Chip :label="invoiceData.issuer || 'N/A'" />
                                        </div>
                                        <div class="bg-gray-50 rounded p-3">NIT:
                                            <Chip :label="invoiceData.nit || 'N/A'" />
                                        </div>
                                        <div class="bg-gray-50 rounded p-3">Tipo de Contribuyente:
                                            <Chip label="—" />
                                        </div>
                                    </div>
                                </template>
                            </Card>
                        </template>
                    </Card>

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Ítems</h4>
                        <div class="overflow-auto rounded border border-gray-200">
                            <table class="min-w-full text-sm text-gray-800">
                                <thead>
                                    <tr class="bg-gray-50 text-left">
                                        <th class="px-3 py-2 border-b">Código</th>
                                        <th class="px-3 py-2 border-b">Descripción</th>
                                        <th class="px-3 py-2 border-b text-right">Cant.</th>
                                        <th class="px-3 py-2 border-b">Unidad</th>
                                        <th class="px-3 py-2 border-b text-right">P. Unit.</th>
                                        <th class="px-3 py-2 border-b text-right">IVA</th>
                                        <th class="px-3 py-2 border-b text-right">Total línea</th>
                                        <th class="px-3 py-2 border-b text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(line, index) in invoiceItems" :key="index" class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap">{{ line.code }}</td>
                                        <td class="px-3 py-2">{{ line.description }}</td>
                                        <td class="px-3 py-2 text-right">{{ line.quantity }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap">{{ line.unit || '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ formatCurrency(line.price) }}</td>
                                        <td class="px-3 py-2 text-right">{{ formatCurrency(line.tax) }}</td>
                                        <td class="px-3 py-2 text-right">{{ formatCurrency(line.lineTotal) }}</td>
                                        <td class="px-3 py-2 text-right font-semibold">{{ formatCurrency(line.total) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
