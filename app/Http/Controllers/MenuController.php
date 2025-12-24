<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{

    private $menuItems;

    public function __construct()
    {
        $this->menuItems = [
            // Inicio
            [
                'key' => "Inicio",
                'label' => "Inicio",
                'icon' => "pi pi-server",
                'url' => route("dashboard"),
            ],
            // Parametros
            [
                'key' => "Parametros",
                'label' => "Parametros",
                'permissions' => ["param.show"],
                'icon' => "pi pi-users",
                'items' => [
                    [
                        'key' => "Parametros_Empresa",
                        'label' => "Empresa",
                        'permissions' => ["param.empresa.show"],
                        'icon' => "pi pi-users",
                        'items' => [
                            [
                                'key' => "Parametros_Empresa_Distrito",
                                'label' => "Distrito",
                                'permissions' => ["Param_Distrito.Explorar"],
                                'url' => route("Distrito.Explorar"),
                            ],
                            [
                                'key' => "Parametros_Empresa_Finca",
                                'label' => "Finca",
                                'permissions' => ["param.finca.show"],
                                'url' => route("Finca.Explorar"),
                            ],
                            [
                                'key' => "Parametros_Empresa_Lote",
                                'label' => "Lote",
                                'permissions' => ["param.lote.show"],
                                'url' => route("Lote.Explorar"),
                            ],
                        ],
                    ],
                    [
                        'key' => "Parametros_Cultivo",
                        'label' => "Cultivo",
                        'icon' => "pi pi-users",
                        'permissions' => ["param.cultivo.show"],
                        'items' => [
                            [
                                'key' => "Parametros_Cultivo_TipoCultivo",
                                'label' => "Tipo Cultivo",
                                'permissions' => ["param.cultivo.tipocultivo.show"],
                                'url' => route("TipoCultivo.Explorar"),
                            ],
                            [
                                'key' => "Parametros_Cultivo_TipoVariedad",
                                'label' => "Tipo Variedad",
                                'permissions' => ["param.cultivo.tipocultivo.show"],
                                'url' => route("TipoVariedad.Explorar"),
                            ],
                        ],
                    ],
                    // Maquinaria
                    [
                        'key' => "Parametros_Maquinaria",
                        'label' => "Maquinaria",
                        'permissions' => ["param.maquinaria.show"],
                        'icon' => "pi pi-users",
                        'items' => [
                            [
                                'key' => "Parametros_Maquinaria_GrupoMaquinaria",
                                'label' => "Grupo Maquinaria",
                                'permissions' => ["param.maquinaria.grupomaquinaria.show"],
                                'url' => route("GrupoMaquina.Explorar"),
                            ],
                            [
                                'key' => "Parametros_Maquinaria_Maquinaria",
                                'label' => "Maquinaria",
                                'permissions' => ["param.maquinaria.show"],
                                'url' => route("Maquinaria.Explorar"),
                            ],
                        ],
                    ],
                    // Cumplido
                    [
                        'key' => "Parametros_Cumplidos",
                        'label' => "Cumplidos",
                        'permissions' => ["param.cumplido.show"],
                        'icon' => "pi pi-users",
                        'items' => [
                            [
                                'key' => "Parametros_Cumplidos_TipoCumplido",
                                'label' => "Tipo Cumplido",
                                'permissions' => ["param.cumplido.tipocumplido.show"],
                                'url' => route("TipoCumplido.Explorar"),
                            ],
                        ],
                    ],
                    [
                        'key' => "Parametros_Labor",
                        'label' => "Labor",
                        'permissions' => ["param.labores.show"],
                        'icon' => "pi pi-users",
                        'items' => [
                            [
                                'key' => "Parametros_Labor_TipoLabor",
                                'label' => "Tipo Labor",
                                'permissions' => ["param.labores.tipolabor.show"],
                                'url' => route("TipoLabor.Explorar"),
                            ],
                            [
                                'key' => "Parametros_Labor_Labor",
                                'label' => "Labor",
                                'permissions' => ["param.labores.labor.show"],
                                'url' => route("Labor.Explorar"),
                            ],
                        ],
                    ],
                    // Nomina
                    [
                        'key' => "Parametros_Nomina",
                        'label' => "Nomina",
                        'permissions' => ["param.nomina.show"],
                        'icon' => "pi pi-users",
                        'items' => [
                            [
                                'key' => "Parametros_Nomina_Empleado",
                                'label' => "Empleado",
                                'permissions' => ["param.labores.labor.show"],
                                'url' => route("empleados.Explorar"),
                            ],
                        ],
                    ],
                    // Materia Prima
                    [
                        'key' => "Parametros_MateriaPrima",
                        'label' => "Materia Prima",
                        'permissions' => ["param.materiaprima.show"],
                        'icon' => "pi pi-users",
                        'items' => [
                            [
                                'key' => "Parametros_MateriaPrima_GrupoProducto",
                                'label' => "Grupo Producto",
                                'permissions' => ["param.materiaprima.grupoproducto.show"],
                                'url' => route("GrupoMateriaPrima.Explorar"),
                            ],
                            [
                                'key' => "Parametros_MateriaPrima_Producto",
                                'label' => "Producto",
                                'permissions' => ["param.materiaprima.producto.show"],
                                'url' => route("MateriaPrima.Explorar"),
                            ],
                        ],
                    ],
                    [
                        'key' => "Parametros_Contratista",
                        'label' => "Contratista",
                        'permissions' => ["param.materiaprima.show"],
                        'icon' => "pi pi-users",
                        'url' => route("contratista.Explorar"),

                    ],
                ],
            ],
            [
                'key' => "Modulos",
                'label' => "Modulos",
                'icon' => "pi pi-server",
                'items' => [
                    // Registro Lotes
                    [
                        'key' => "Modulos_RegistroLotes",
                        'label' => "Registro Lotes",
                        'icon' => "pi pi-server",
                        'permissions' => ["mod.reglote.show"],
                        'items' => [
                            [
                                'key' => "Modulos_RegistroLotes_Explorar",
                                'label' => "Explorar",
                                'permissions' => ["mod.reglote.show"],
                                'url' => route("RegLote.Explorar"),
                            ],

                            [
                                'key' => "Modulos_RegistroLotes_Crear",
                                'label' => "Crear",
                                'permissions' => ["mod.reglote.create"],
                                'url' => route("RegLote.Crear"),
                            ],
                        ],
                    ],
                    // Cumplidos
                    [
                        'key' => "Modulos_Cumplidos",
                        'label' => "Cumplidos",
                        'icon' => "pi pi-verified",
                        'items' => [
                            // Cumplido Maquinaria
                            [
                                'key' => "Modulos_Cumplidos_CumplidoMaquinaria",
                                'label' => "Cumplido Maquinaria",
                                'permissions' => ["mod.cump_maq.show"],
                                'icon' => "pi pi-verified",

                                'items' => [
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoMaquinaria_Explorar",
                                        'label' => "Explorar",
                                        'permissions' => ["mod.cump_maq.show"],
                                        'url' => route("CumpMaquinaria.Explorar"),
                                    ],
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoMaquinaria_Crear",
                                        'label' => "Crear",
                                        'permissions' => ["mod.cump_maq.show"],
                                        'url' => route("CumpMaquinaria.Crear"),
                                    ],
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoMaquinaria_Reporte",
                                        'label' => "Reporte",
                                        'permissions' => ["mod.cump_maq.show"],
                                        'url' => route("CumpMaquinaria.report"),
                                    ],


                                ],
                            ],
                            // Cumplido de Aplicacion
                            [
                                'key' => "Modulos_Cumplidos_CumplidoAplicacion",
                                'label' => "Cumplido Aplicacion",
                                'permissions' => ["mod.cump_aplicacion.show"],
                                'icon' => "pi pi-verified",
                                'items' => [
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoAplicacion_Explorar",
                                        'label' => "Explorar",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumplidoAplicacion.Explorar"),
                                    ],
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoAplicacion_Reporte",
                                        'label' => "Reporte",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumplidoAplicacion.report"),
                                    ],
                                ],
                            ],
                            // Cumplido de ordenes de Servicio
                            [
                                'key' => "Modulos_Cumplidos_CumplidoOrdenServicio",
                                'label' => "Cumplido Orden Servicio",
                                'permissions' => ["mod.cump_aplicacion.show"],
                                'icon' => "pi pi-verified",
                                'items' => [
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoOrdenServicio_Explorar",
                                        'label' => "Explorar",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumplidoOrdenServicio.Explorar"),
                                    ],
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoOrdenServicio_Crear",
                                        'label' => "Crear",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumplidoOrdenServicio.crear"),
                                    ],
                                    [
                                        'key' => "Modulos_Cumplidos_CumplidoOrdenServicio_Reporte",
                                        'label' => "Reporte",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumplidoOrdenServicio.report"),
                                    ],
                                ],
                            ],
                            // Cumplido Labor de Campo
                            [
                                'key' => "Modulos_Cumplidos_LaborCampo",
                                'label' => "Cumplido Labor Campo",
                                'permissions' => ["mod.cump_aplicacion.show"],
                                'icon' => "pi pi-verified",
                                'items' => [
                                    [
                                        'key' => "Modulos_Cumplidos_LaborCampo_Explorar",
                                        'label' => "Explorar",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumpLaborCampo.Explorar"),
                                    ],
                                    [
                                        'key' => "Modulos_Cumplidos_LaborCampo_Crear",
                                        'label' => "Crear",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumpLaborCampo.crear"),
                                    ],
                                    [
                                        'key' => "Modulos_Cumplidos_LaborCampo_Reporte",
                                        'label' => "Reporte",
                                        'permissions' => ["mod.cump_aplicacion.show"],
                                        'url' => route("CumpLaborCampo.report"),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    // Record
                    [
                        'key' => "Record",
                        'label' => "Agronomo",
                        'icon' => "pi pi-server",
                        'items' => [
                            [
                                'key' => "Record_VisitaAgronomo",
                                'label' => "Record Visita",
                                'icon' => "pi pi-server",
                                'items' => [
                                    [
                                        'key' => "Record_VisitaAgronomo_Explorar",
                                        'label' => "Explorar",
                                        'url' => route("RecordVisita.Explorar"),
                                    ],
                                    [
                                        'key' => "Record_VisitaAgronomo_crear",
                                        'label' => "Crear",
                                        'url' => route("RecordVisita.crear"),
                                    ],
                                ],
                            ],
                            [
                                'key' => "Record_ReportSiembra",
                                'label' => "Reporte Siembra",
                                'icon' => "pi pi-server",
                                'items' => [
                                    [
                                        'key' => "Record_ReportSiembra_Explorar",
                                        'label' => "Explorar",
                                        'url' => route("RecordVisita.Explorar"),
                                    ],
                                    [
                                        'key' => "Record_ReportSiembra_crear",
                                        'label' => "Crear",
                                        'url' => route("RecordVisita.crear"),
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => "Modulos_Costos",
                        'label' => "Costos",
                        'permissions' => ["mod.costos"],

                        'icon' => "pi pi-box",
                        'items' => [
                            [
                                'key' => "Modulos_Costos_Parametros",
                                'label' => "Parametros",
                                'icon' => "pi pi-wrench",
                                'items' => [
                                    [
                                        'key' => "Modulos_Costos_Parametros",
                                        'label' => "Productos",
                                        'url' => route('costos.param.subtipogasto.index'),
                                    ],

                                ],
                            ],

                            [
                                'key' => "Modulos_Costos_Gastos",
                                'label' => "Gastos",
                                'icon' => "pi pi-cart-arrow-down",
                                'items' => [

                                    [
                                        'key' => "Modulos_Costos_Gastos_movimientos",
                                        'label' => "Movimientos",
                                    ],
                                    [
                                        'key' => "Modulos_Costos_Gastos_Importar",
                                        'label' => "Importar",
                                        'icon' => "pi pi-file-arrow-up",
                                        'url' => route("costos.gastos.importar"),
                                    ],
                                ],
                            ],
                            [
                                'key' => "Modulos_Costos_Ingresos",
                                'label' => "Ingresos",
                                'icon' => "pi pi-money-bill",
                                'items' => [
                                    [
                                        'key' => "Modulos_Costos_Ingresos_movimientos",
                                        'label' => "Movimientos",
                                        'icon' => "pi pi-file-arrow-up",
                                        'url' => route("costos.ingresos.importar"),
                                    ],
                                    [
                                        'key' => "Modulos_Costos_Ingresos_Importar",
                                        'label' => "Importar",
                                        'icon' => "pi pi-file-arrow-up",
                                        //'url'=>route("costos.ingresos.importar"),
                                    ],
                                ],
                            ],
                            [
                                'key' => "Modulos_Costos_Informes",
                                'label' => "Informes",
                                'icon' => "pi pi-chart-line",
                                'items' => [
                                    [
                                        'key' => "Modulos_Costos_Informes_ConsolidadoVentas",
                                        'label' => "Consolidado Ventas",
                                        'icon' => "pi pi-file",
                                        //'url'=>route("costos.report.gastosporlotedetallado"),
                                    ],
                                    [
                                        'key' => "Modulos_Costos_Informes_GastosXLote",
                                        'label' => "Gastos por Lote Detallado",
                                        'icon' => "pi pi-file",
                                        'url' => route("costos.report.gastosporlotedetallado"),
                                    ],
                                    [
                                        'key' => "Modulos_Costos_Informes_GastosXLote",
                                        'label' => "Gastos por Lote Consolidado",
                                        'icon' => "pi pi-file",
                                        'url' => route("costos.report.GastosPorLote"),
                                    ],
                                ],
                            ],
                            [
                                'key' => "Modulos_Costos_Presupuesto",
                                'label' => "Presupuesto",
                                'icon' => "pi pi-map",
                                'url' => route("costos.ppt.index"),


                            ]
                        ]
                    ],
                ],
            ],

            // Configuracion
            [
                'key' => "Config",
                'label' => "Config",
                'icon' => "pi pi-cog",
                'permissions' => ["config.show"],
                'items' => [
                    [
                        'key' => "Config_Usuarios",
                        'label' => "Usuarios",
                        'permissions' => ["config.user.show"],
                        'icon' => "pi pi-cog",
                        'items' => [
                            [
                                'key' => "Config_Usuarios_Explorar",
                                'label' => "Explorar",
                                'icon' => "pi pi-cog",
                                'permissions' => ["config.user.show"],
                                'url' => route("User.Explorar"),
                            ],
                            [
                                'key' => "Config_Usuarios_Crear",
                                'label' => "Crear",
                                'icon' => "pi pi-cog",
                                'permissions' => ["config.user.create"],
                                'url' => route("dashboard"),
                            ],
                        ],
                    ],
                    [
                        'key' => "Config_Roles",
                        'label' => "Roles",
                        'permissions' => ["config.rol.show"],
                        'icon' => "pi pi-cog",
                        'items' => [
                            [
                                'key' => "Config_Roles_Exporar",
                                'label' => "Explorar",
                                'icon' => "pi pi-cog",
                                'permissions' => ["config.rol.show"],
                                'url' => route("Rol.Explorar"),
                            ],
                            [
                                'key' => "Config_Roles_Crear",
                                'label' => "Crear",
                                'icon' => "pi pi-cog",
                                'permissions' => ["config.rol.create"],
                                'url' => route("Rol.create"),
                            ],
                        ],
                    ],
                    [
                        'key' => "Config_Permisos",
                        'label' => "Permisos",
                        'icon' => "pi pi-cog",
                        'permissions' => ["config.permisos.show"],
                        'items' => [
                            [
                                'key' => "Config_Permisos_Explorar",
                                'label' => "Explorar",
                                'icon' => "pi pi-cog",
                                'permissions' => ["config.permisos.show"],
                                'url' => route("Permisos.Explorar"),
                            ],
                            [
                                'key' => "Config_Permisos_Crear",
                                'label' => "Crear",
                                'icon' => "pi pi-cog",
                                'permissions' => ["config.permisos.create"],
                                'url' => route("Permisos.create"),
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => "Reportes",
                'label' => "Reportes",
                'icon' => "pi pi-server",
            ],
        ];
    }

    public function getMenuItems()
    {
        $user = Auth::user();

        // Filtrar los elementos del menú según los permisos del usuario
        $filteredMenuItems = $this->filterMenuItems($this->menuItems, $user);

        return response()->json($filteredMenuItems);
    }

    public function getExpandedKeys(Request $request)
    {
        $url = $request->input('url');
        $expandedKeys = $this->expandKeysForUrl($this->menuItems, $url);

        return response()->json($expandedKeys);
    }

    private function filterMenuItems($menuItems, $user)
    {
        $filteredItems = [];

        foreach ($menuItems as $item) {
            if (isset($item['permissions'])) {
                $hasPermission = false;
                foreach ($item['permissions'] as $permission) {
                    if ($user->can($permission)) {
                        $hasPermission = true;
                        break;
                    }
                }
                if (!$hasPermission) {
                    continue;
                }
            }

            if (isset($item['items'])) {
                $item['items'] = $this->filterMenuItems($item['items'], $user);
            }

            $filteredItems[] = $item;
        }

        return $filteredItems;
    }

    private function expandKeysForUrl($menuItems, $url)
    {
        $expandedKeys = [];

        foreach ($menuItems as $item) {
            if (isset($item['url']) && $item['url'] === $url) {
                $expandedKeys[$item['key']] = true;
                $this->expandParentKeys($item['key'], $expandedKeys);
            }

            if (isset($item['items'])) {
                $expandedKeys = array_merge($expandedKeys, $this->expandKeysForUrl($item['items'], $url));
            }
        }

        return $expandedKeys;
    }
    private function expandParentKeys($key, &$expandedKeys)
    {
        // Primero agregamos 0:true
        $expandedKeys["0"] = true;

        // Verificar si la clave no tiene un guion bajo
        if (strpos($key, '_') === false) {
            $expandedKeys[$key] = true;
            return;
        }

        // Dividir la clave en partes
        $parts = explode('_', $key);
        $primerParte = $parts[0];

        // Agregar la clave completa
        $expandedKeys[$key] = true;

        // Expandir las claves padres
        while (count($parts) > 1) {
            array_pop($parts); // Remover la última parte
            $parentKey = implode('_', $parts); // Reunir las partes restantes
            $expandedKeys[$parentKey] = true; // Agregar la clave padre
        }

        // Agregar la clave de nivel más alto
        $expandedKeys[$primerParte] = true;
    }
}
