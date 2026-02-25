<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            margin: 0.5cm;
            padding: 0;
            font-size: 8px;
        }

        .container {
            width: 100%;
            margin: 0.5rem 0.5rem;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .info {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        th,
        td {
            border: 1px solid #808080;
            padding: 2px;
            font-size: 8px;
            text-align: left;
        }

        th {
            background-color: #dddddd;
        }

        .subtotal,
        .total {
            background-color: #dddddd;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Consolidado de Gastos Por Lote</h2>
        <div class="info">
            <p><strong>Finca:</strong> {{ $data['finca'] }} | <strong>Lote:</strong> {{ $data['lote'] }} |
                <strong>Código:</strong> {{ $data['codigo'] }} | <strong>Hect:</strong> {{ $data['hect'] }}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['data'] as $gasto)
                    <tr>
                        <td>{{ $gasto['nombre_gasto'] }}</td>
                        <td>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Gasto</th>
                                        <th colspan="2">Detalle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gasto['data'] as $tipogasto)
                                        <tr>
                                            <td>{{ $tipogasto['nombre_tipogasto'] }}</td>
                                            <td colspan="2">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Descripción</th>
                                                            <th>Gasto x Hect</th>
                                                            <th>Total Gasto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tipogasto['data'] as $subtipogasto)
                                                            <tr>
                                                                <td>{{ $subtipogasto['nombre_subtipogasto'] }}</td>
                                                                <td style="text-align: right">{{ $subtipogasto['gastoxhect'] }}</td>
                                                                <td style="text-align: right">{{ $subtipogasto['gasto_total'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr class="subtotal">
                                                            <td>Subtotal</td>
                                                            <td style="text-align: right">{{ $tipogasto['gastoxhect'] }}
                                                            </td>
                                                            <td style="text-align: right">
                                                                {{ $tipogasto['gasto_total'] }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="subtotal">
                                        <td>Subtotal {{ $gasto['nombre_gasto'] }}</td>
                                        <td style="text-align: right">{{ $gasto['gastoxhect'] }}</td>
                                        <td style="text-align: right">{{ $gasto['gasto_total'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total">
                    <td>Total</td>
                    <td style="text-align: right">CostoXHect :{{ $data['gastoxhect'] }} || Total :
                        {{ $data['gasto_total'] }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
