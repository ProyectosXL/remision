
function imprimirRemitos(taskId, cantidadRemitos, primerRemito, ultimoRemito) {
    // Valores por defecto para demostración (en producción, estos vendrían como parámetros)
    taskId = taskId || 'TASK_001';
    cantidadRemitos = cantidadRemitos || 5;
    primerRemito = primerRemito || 'R0080000138477';
    ultimoRemito = ultimoRemito || 'R0080000139190';
    
    // Crear el elemento de confirmación
    const confirmDialog = document.createElement('div');
    confirmDialog.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50';
    confirmDialog.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="text-center mb-4">
                <i class="fas fa-print text-blue-500 text-4xl mb-4"></i>
                <h3 class="text-xl font-bold">Confirmar impresión de remitos</h3>
            </div>
            
            <div class="mb-6">
                <p class="mb-2">Está a punto de imprimir los remitos de la tarea <span class="font-semibold">${taskId}</span>.</p>
                <ul class="bg-gray-50 p-3 rounded-lg text-sm">
                    <li class="py-1 border-b border-gray-100">
                        <span class="text-gray-600">Cantidad de remitos:</span> 
                        <span class="font-semibold">${cantidadRemitos}</span>
                    </li>
                    <li class="py-1 border-b border-gray-100">
                        <span class="text-gray-600">Primer remito:</span> 
                        <span class="font-semibold">${primerRemito}</span>
                    </li>
                    <li class="py-1">
                        <span class="text-gray-600">Último remito:</span> 
                        <span class="font-semibold">${ultimoRemito}</span>
                    </li>
                </ul>
            </div>
            
            <div class="flex justify-end gap-3">
                <button id="cancelarImpresion" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Cancelar
                </button>
                <button id="confirmarImpresion" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Imprimir
                </button>
            </div>
        </div>
    `;
    
    // Agregar el diálogo al body
    document.body.appendChild(confirmDialog);
    
    // Agregar event listeners
    document.getElementById('cancelarImpresion').addEventListener('click', function() {
        document.body.removeChild(confirmDialog);
    });
    
    document.getElementById('confirmarImpresion').addEventListener('click', function() {
        // Aquí iría la lógica de impresión real
        alert(`Iniciando impresión de ${cantidadRemitos} remitos...`);
        document.body.removeChild(confirmDialog);
        
        // En producción, podría hacer una petición al servidor:
        /*
        fetch('imprimir_remitos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                taskId: taskId,
                remitos: [primerRemito, ..., ultimoRemito]
            })
        });
        */
    });
    
    // Cerrar al hacer clic fuera del diálogo
    confirmDialog.addEventListener('click', function(e) {
        if (e.target === confirmDialog) {
            document.body.removeChild(confirmDialog);
        }
    });
}

