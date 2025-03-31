
<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remisión Masiva de pedidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-10">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-file-invoice-dollar text-blue-600 mr-3"></i>
                    Remisión Masiva de pedidos
                </h1>
                <a href="historico.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-history mr-2"></i>
                    Ver Histórico
                </a>
            </div>
        </header>

        <main class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <!-- Tabs para seleccionar método -->
            <div class="mb-8 border-b border-gray-200">
                <div class="flex space-x-4" role="tablist">
                    <button id="tabExcel" class="px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600" role="tab" aria-selected="true">
                        <i class="fas fa-file-excel mr-2"></i>
                        Importar Excel
                    </button>
                    <button id="tabRango" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" role="tab">
                        <i class="fas fa-list-ol mr-2"></i>
                        Rango de Pedidos
                    </button>
                </div>
            </div>

            <!-- Sección Excel -->
            <div id="seccionExcel">
                <!-- Plantilla de ejemplo -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Plantilla de Ejemplo</h2>
                    <div class="flex gap-4">
                        <button id="downloadTemplate" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                            <i class="fas fa-download mr-2"></i>
                            Descargar Plantilla
                        </button>
                        <span class="text-sm text-gray-500 self-center">
                            Descarga la plantilla de ejemplo para importar tus pedidos
                        </span>
                    </div>
                </div>

                <!-- Upload Section -->
                <div class="mb-8">
                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
                        <i class="fas fa-file-excel text-4xl text-green-500 mb-4"></i>
                        <label for="excelFile" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded cursor-pointer transition duration-300">
                            <i class="fas fa-upload mr-2"></i>
                            Importar Excel
                        </label>
                        <input type="file" id="excelFile" class="hidden" accept=".xlsx, .xls">
                        <p class="text-sm text-gray-500 mt-2">Solo archivos Excel (.xlsx, .xls)</p>
                    </div>
                </div>
            </div>

            <!-- Sección Rango de Pedidos -->
            <div id="seccionRango" class="hidden">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-semibold mb-6">Ingreso por Rango de Pedidos</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Talonario</label>
                            <select id="talonario" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Desde Pedido Nº</label>
                            <input type="number" id="desdePedido" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hasta Pedido Nº</label>
                            <input type="number" id="hastaPedido" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="1">
                        </div>
                    </div>
                    <div class="mt-6">
                        <button id="buscarRango" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded transition duration-300">
                            <i class="fas fa-search mr-2"></i>
                            Buscar Pedidos
                        </button>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="dataTableContainer" class="hidden mt-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Talonario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Pedido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody" class="divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex flex-col sm:flex-row gap-4">
                    <button id="remitirBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Remitir Ahora
                    </button>
                    <button id="programarBtn" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                        <i class="fas fa-clock mr-2"></i>
                        Programar Remisión
                    </button>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
            </div>
        </main>

        <!-- Modal Programar -->
        <div id="modalProgramar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Programar Remisión</h3>
                        <button id="cerrarModal" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="mt-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de ejecución
                            </label>
                            <input type="date" 
                                id="fechaProgramada" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                min="">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hora de ejecución
                            </label>
                            <input type="time" 
                                id="horaProgramada" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="bg-gray-50 p-4 rounded-md mb-4">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-2"></i>
                                Se programará la remisión de <span id="cantidadPedidos" class="font-semibold">0</span> pedidos
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button id="cancelarProgramacion" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancelar
                        </button>
                        <button id="confirmarProgramacion" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>