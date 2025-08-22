<?php
    require_once 'class/pedido.php';

    $pedido = new Pedido();

    $idTareaEnc = $_GET['id']; 
    
    $data = $pedido->getEncTarea($idTareaEnc);
?>
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

<script>
function descargarConsumo() {
    const header = ['Número de Pedido', 'Código Cliente', 'Sucursal', 'Cantidad', 'Número Remito', 'Número Factura'];
    const rows = Array.from(document.querySelectorAll('#tabla-detalles tr'))
        .map(tr => Array.from(tr.cells).map(td => td.innerText.trim()));
    const data = [header, ...rows];
    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Consumo");
    const id = document.getElementById('idTareaEnc').textContent.trim();
    XLSX.writeFile(wb, `Consumo_${id}.xlsx`);
}
</script>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8" id="contenido-principal">
        <!-- Header -->
         <div id="idTareaEnc" style="display: none;"><?php echo $idTareaEnc ?></div>
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
                    <p class="mt-1 text-lg font-semibold"><?php echo str_pad($idTareaEnc, 3, '0', STR_PAD_LEFT) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Fecha Tarea</label>
                    <p class="mt-1 text-lg"><?php echo $data[0]['FECHA_TAREA']->format('d/m/Y H:i'); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tipo</label>
                    <p class="mt-1">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?php echo $data[0]['TIPO_TAREA'] == 1 ? 'Ejecución' : 'Programación'; ?>
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Estado</label>
                    <p class="mt-1 flex items-center">
                        <?php if ($data[0]['ESTADO'] === 1) { ?>
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <?php } else { ?>
                            <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        <?php } ?>
                        <span><?php echo $data[0]['ESTADO'] == 1 ? 'Finalizada' : 'Programada'; ?></span>
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
    <div id="loadingSpinner" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
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
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tabla-detalles">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="assets/js/detalle.js"></script>
    <!-- jquery -->

    <script>
        function imprimirTodos() {
            // Guardar el título original de la página
            const originalTitle = document.title;
            
            // Cambiar el título para la impresión
            document.title = "Detalle de Remisión de Pedidos - <?php echo $idTareaEnc ?>";
            
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

    </script>
</body>
</html>