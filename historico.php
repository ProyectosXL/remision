
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Procesos - Adelanto de Sueldo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="mb-10">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-history text-blue-600 mr-3"></i>
                    Histórico de Procesos
                </h1>
                <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <!-- Filters -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Desde</label>
                    <input type="date" id="fechaDesde" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Hasta</label>
                    <input type="date" id="fechaHasta" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="estado" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="finalizada">Finalizada</option>
                    </select>
                </div>
                <div class="relative">
                    <button id="btnFiltrar" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-300">
                        <i class="fas fa-filter mr-2"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nro. Tarea
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha Tarea
                        </th>
                        <th class="px py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cantidad de Pedidos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo Tarea
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Comienzo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Finalización
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Ejemplo 1: Tarea Finalizada -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            TASK_001
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            22/02/2024 10:30
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            15
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Ejecución
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            22/02/2024 10:30
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            22/02/2024 10:35
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Finalizada
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex justify-center gap-4">
                                <a href="detalle.php?id=TASK_001" class="text-blue-600 hover:text-blue-800 tooltip-container">
                                    <i class="fas fa-eye text-lg"></i>
                                    <span class="tooltip">Ver detalle</span>
                                </a>
                                <button onclick="imprimirRemitos('TASK_001', 5, 'R0080000138477', 'R0080000139190')" class="text-gray-600 hover:text-blue-600 tooltip-container">
                                    <i class="fas fa-print text-lg"></i>
                                    <span class="tooltip">Imprimir remitos</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                        <!-- Ejemplo 2: Tarea Programada Pendiente -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                TASK_002
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                22/02/2024 11:00
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                8
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Programación
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                23/02/2024 08:00
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                -
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                    Pendiente
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex justify-center gap-4">
                                    <a href="detalle.php?id=TASK_001" class="text-blue-600 hover:text-blue-800 tooltip-container">
                                        <i class="fas fa-eye text-lg"></i>
                                        <span class="tooltip">Ver detalle</span>
                                    </a>
                                    <button onclick="imprimirRemitos('TASK_001', 5, 'R0080000138477', 'R0080000139190')" class="text-gray-600 hover:text-blue-600 tooltip-container">
                                        <i class="fas fa-print text-lg"></i>
                                        <span class="tooltip">Imprimir remitos</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Ejemplo 3: Tarea Finalizada -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                TASK_003
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                22/02/2024 09:15
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                12
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Ejecución
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                22/02/2024 09:15
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                22/02/2024 09:20
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Finalizada
                                </span>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex justify-center gap-4">
                                    <a href="detalle.php?id=TASK_001" class="text-blue-600 hover:text-blue-800 tooltip-container">
                                        <i class="fas fa-eye text-lg"></i>
                                        <span class="tooltip">Ver detalle</span>
                                    </a>
                                    <button onclick="imprimirRemitos('TASK_001', 5, 'R0080000138477', 'R0080000139190')" class="text-gray-600 hover:text-blue-600 tooltip-container">
                                        <i class="fas fa-print text-lg"></i>
                                        <span class="tooltip">Imprimir remitos</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando 1-3 de 3 registros
                </div>
                <div class="flex gap-2">
                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-50 disabled:opacity-50" disabled>
                        Anterior
                    </button>
                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-50 disabled:opacity-50" disabled>
                        Siguiente
                    </button>
                </div>
            </div>
        </main>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="assets/js/historico.js"></script>

</html>