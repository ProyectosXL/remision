
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Tarea - Remisión de Pedidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8" id="contenido-principal">
        <!-- Header -->
        <header class="mb-10">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-blue-600 mr-3"></i>
                    Detalle de Tarea
                </h1>
                <a href="historico.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </header>

        <!-- Información de la Tarea -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nro. Tarea</label>
                    <p class="mt-1 text-lg font-semibold">TASK_001</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Fecha Tarea</label>
                    <p class="mt-1 text-lg">22/02/2024 10:30</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tipo</label>
                    <p class="mt-1">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            Ejecución
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Estado</label>
                    <p class="mt-1 flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>Finalizada</span>
                    </p>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-6 flex justify-end gap-4">
                <button onclick="descargarConsumo()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-file-excel mr-2"></i>
                    Descargar Consumo
                </button>
                <button onclick="imprimirTodos()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-print mr-2"></i>
                    Imprimir Detalle
                </button>
            </div>
        </div>

        <!-- Tabla de Detalles -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Pedido</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número Remito</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número Factura</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Ejemplo de filas -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> 000010028172</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">FRCONC</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CONCORDIA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">19</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> 000010028085</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">FRCONC</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CONCORDIA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">21</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R0080000138477</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">A0014400067444</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> 0000100282377</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">FRCONC</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CONCORDIA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">52</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R0080000138835</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">A0014400067708</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> 0000100283495</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">FRCONC</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CONCORDIA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">50</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R0080000139190</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">A0014400067914</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> 0000100284003</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">FRCONC</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CONCORDIA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        function imprimirTodos() {
            // Guardar el título original de la página
            const originalTitle = document.title;
            
            // Cambiar el título para la impresión
            document.title = "Detalle de Remisión de Pedidos - TASK_001";
            
            // Crear un estilo para la impresión
            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    body { 
                        font-family: Arial, sans-serif;
                        background: white;
                    }
                    header a, button {
                        display: none !important;
                    }
                    .shadow-lg {
                        box-shadow: none !important;
                    }
                    @page {
                        size: landscape;
                        margin: 1cm;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    thead {
                        background-color: #f3f4f6 !important;
                        -webkit-print-color-adjust: exact;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Imprimir la página
            window.print();
            
            // Restaurar el título original después de imprimir
            document.title = originalTitle;
            
            // Eliminar el estilo de impresión
            document.head.removeChild(style);
        }

        function descargarConsumo() {
            // Datos de ejemplo para el Excel
            const data = [
                ['Número de Pedido', 'Código Cliente', 'Sucursal', 'Cantidad', 'Número Remito', 'Número Factura'],
                [' 000010028172', 'FRCONC', 'CONCORDIA', 19, '-', '-'],
                [' 000010028085', 'FRCONC', 'CONCORDIA', 21, 'R0080000138477', 'A0014400067444'],
                [' 0000100282377', 'FRCONC', 'CONCORDIA', 52, 'R0080000138835', 'A0014400067708'],
                [' 0000100283495', 'FRCONC', 'CONCORDIA', 50, 'R0080000139190', 'A0014400067914'],
                [' 0000100284003', 'FRCONC', 'CONCORDIA', 12, '-', '-']
            ];
            
            // Crear un libro de trabajo
            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Consumo");
            
            // Descargar el archivo Excel
            XLSX.writeFile(wb, "Consumo_TASK_001.xlsx");
        }
    </script>
</body>
</html>