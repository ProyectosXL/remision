document.addEventListener('DOMContentLoaded', function() {
    const fechaDesde = document.getElementById('fechaDesde');
    const fechaHasta = document.getElementById('fechaHasta');
    const estado = document.getElementById('estado');
    const btnFiltrar = document.getElementById('btnFiltrar');
    const tableBody = document.querySelector('tbody');
    const paginationInfo = document.querySelector('.text-sm.text-gray-500');
    
    const hoy = new Date();
    const unaSemanaAtras = new Date();
    unaSemanaAtras.setDate(hoy.getDate() - 7);
    
    fechaDesde.value = formatDateForInput(unaSemanaAtras);
    fechaHasta.value = formatDateForInput(hoy);
    
    cargarTareas();
    
    btnFiltrar.addEventListener('click', function() {
        cargarTareas();
    });
    
    function cargarTareas() {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-4 text-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Cargando datos...
                </td>
            </tr>
        `;
        
        const desde = fechaDesde.value ? formatDateForApi(fechaDesde.value, false) : '';
        const hasta = fechaHasta.value ? formatDateForApi(fechaHasta.value, true) : '';
        let estadoValue = estado.value;
        
        if (estadoValue === '') {
            estadoValue = '%';
        }
        
        const data = {
            desde: desde,
            hasta: hasta,
            estado: estadoValue
        };
        
        $.ajax({
            url: "assets/controllers/importController.php",
            type: "POST",
            data: {
               data: data,
               action:  "getTareas"

            },
            success: function (data) {
                const response = JSON.parse(data);
                renderizarTabla(response['body']);
            },
            error: function (error) {
                console.log(error,'error');
            }
        });
    }
    
    function renderizarTabla(tareas) {
        if (!tareas || tareas.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        No se encontraron registros con los filtros seleccionados
                    </td>
                </tr>
            `;
            paginationInfo.textContent = 'Mostrando 0 registros';
            return;
        }
        
        tableBody.innerHTML = '';
        
        tareas.forEach(tarea => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            let tipoClase = 'bg-blue-100 text-blue-800';
            if (tarea.tipo_tarea.toLowerCase() == 'programación') {
                tipoClase = 'bg-purple-100 text-purple-800';
            }
            
            let estadoIcono = 'fa-check-circle text-green-500';
            let estadoTexto = 'Finalizada';
            if (tarea.estado.toLowerCase() === 'pendiente') {
                estadoIcono = 'fa-clock text-yellow-500';
                estadoTexto = 'Pendiente';
            }
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${tarea.nro_tarea}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formatFechaHora(tarea.fecha_tarea)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${tarea.cantidad_pedidos}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${tipoClase}">
                        ${tarea.tipo_tarea}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formatFechaHora(tarea.comienzo)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${tarea.finalizacion ? formatFechaHora(tarea.finalizacion) : '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center">
                        <i class="fas ${estadoIcono} mr-2"></i>
                        ${estadoTexto}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex justify-center gap-4">
                        <a href="detalle.php?id=${tarea.nro_tarea}" class="text-blue-600 hover:text-blue-800 tooltip-container">
                            <i class="fas fa-eye text-lg"></i>
                            <span class="tooltip">Ver detalle</span>
                        </a>
                        <button onclick="imprimirRemitos('${tarea.nro_tarea}', ${tarea.cantidad_pedidos}, '${tarea.comienzo || ''}', '${tarea.finalizacion || ''}')" 
                                class="text-gray-600 hover:text-blue-600 tooltip-container">
                            <i class="fas fa-print text-lg"></i>
                            <span class="tooltip">Imprimir remitos</span>
                        </button>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        paginationInfo.textContent = `Mostrando 1-${tareas.length} de ${tareas.length} registros`;
    }
    
    window.imprimirRemitos = function(nroTarea, cantidadPedidos, remitosDesde, remitosHasta) {
        
        const confirmDialog = document.createElement('div');
        confirmDialog.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50';
        confirmDialog.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="text-center mb-4">
                    <i class="fas fa-print text-blue-500 text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold">Confirmar impresión de remitos</h3>
                </div>
                
                <div class="mb-6">
                    <p class="mb-2">Está a punto de imprimir los remitos de la tarea <span class="font-semibold">${nroTarea}</span>.</p>
                    <ul class="bg-gray-50 p-3 rounded-lg text-sm">
                        <li class="py-1 border-b border-gray-100">
                            <span class="text-gray-600">Cantidad de remitos:</span> 
                            <span class="font-semibold">${cantidadPedidos}</span>
                        </li>
                        <li class="py-1 border-b border-gray-100">
                            <span class="text-gray-600">Primer remito:</span> 
                            <span class="font-semibold">${remitosDesde || 'N/A'}</span>
                        </li>
                        <li class="py-1">
                            <span class="text-gray-600">Último remito:</span> 
                            <span class="font-semibold">${remitosHasta || 'N/A'}</span>
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
        
        document.body.appendChild(confirmDialog);
        
        document.getElementById('cancelarImpresion').addEventListener('click', function() {
            document.body.removeChild(confirmDialog);
        });
        
        document.getElementById('confirmarImpresion').addEventListener('click', function() {
            alert(`Iniciando impresión de ${cantidadPedidos} remitos...`);
            document.body.removeChild(confirmDialog);
            
            // En producción, podría hacer una petición al servidor:
            /*
            fetch('imprimir_remitos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    taskId: nroTarea,
                    remitos: { desde: remitosDesde, hasta: remitosHasta }
                })
            });
            */
        });
        
        confirmDialog.addEventListener('click', function(e) {
            if (e.target === confirmDialog) {
                document.body.removeChild(confirmDialog);
            }
        });
    };
    
    function formatDateForInput(date) {
        return date.toISOString().split('T')[0];
    }
    
    function formatDateForApi(dateString, isEndDate) {
        const time = isEndDate ? '23:59:59.000' : '00:00:00.000';
        return `${dateString} ${time}`;
    }
    
    function formatFechaHora(fechaString) {
        if (!fechaString) return '-';
        
        try {
            const fecha = new Date(fechaString);
            if (isNaN(fecha.getTime())) {
                const parts = fechaString.split(/[/ :]/);
                if (parts.length >= 5) {
                    const dia = parts[0];
                    const mes = parts[1];
                    const anio = parts[2];
                    const hora = parts[3];
                    const minutos = parts[4];
                    return `${dia}/${mes}/${anio} ${hora}:${minutos}`;
                }
                return fechaString; 
            }
            
            const dia = String(fecha.getDate()).padStart(2, '0');
            const mes = String(fecha.getMonth() + 1).padStart(2, '0');
            const anio = fecha.getFullYear();
            const hora = String(fecha.getHours()).padStart(2, '0');
            const minutos = String(fecha.getMinutes()).padStart(2, '0');
            
            return `${dia}/${mes}/${anio} ${hora}:${minutos}`;
        } catch (error) {
            console.error('Error al formatear fecha:', error, fechaString);
            return fechaString; 
        }
    }
});
