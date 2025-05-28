
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
                  

                        <!-- Ejemplo 3: Tarea Finalizada -->

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