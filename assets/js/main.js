
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const tabExcel = document.getElementById('tabExcel');
    const tabRango = document.getElementById('tabRango');
    const seccionExcel = document.getElementById('seccionExcel');
    const seccionRango = document.getElementById('seccionRango');
    const fileInput = document.getElementById('excelFile');
    const dataTableContainer = document.getElementById('dataTableContainer');
    const dataTableBody = document.getElementById('dataTableBody');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const buscarRango = document.getElementById('buscarRango');
    
    let importedData = [];

    // Tab handling
    tabExcel.addEventListener('click', function() {
        tabExcel.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        tabExcel.classList.remove('text-gray-500');
        tabRango.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        tabRango.classList.add('text-gray-500');
        seccionExcel.classList.remove('hidden');
        seccionRango.classList.add('hidden');
    });

    tabRango.addEventListener('click', function() {
        tabRango.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        tabRango.classList.remove('text-gray-500');
        tabExcel.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        tabExcel.classList.add('text-gray-500');
        seccionRango.classList.remove('hidden');
        seccionExcel.classList.add('hidden');
    });

    // Download template handler
    document.getElementById('downloadTemplate').addEventListener('click', function() {
        const template = [
            ['Talonario', 'Número de Pedido'],
            ['1', ' 0000100280528'],
            ['1', ' 0000100280535'],
            ['1', ' 0000100280973']
        ];
        
        const ws = XLSX.utils.aoa_to_sheet(template);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Plantilla");
        XLSX.writeFile(wb, "plantilla_pedidos.xlsx");
    });

    // File import handler
    fileInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!['xlsx', 'xls'].includes(fileExtension)) {
            alert('Por favor, seleccione un archivo Excel válido (.xlsx o .xls)');
            return;
        }

        loadingSpinner.classList.remove('hidden');
        
        try {
            const data = await readExcelFile(file);
            await validateAndProcessData(data, 'excel');
        } catch (error) {
            console.error('Error al procesar el archivo:', error);
            alert('Error al procesar el archivo Excel: ' + error.message);
        } finally {
            loadingSpinner.classList.add('hidden');
        }
    });

    // Rango search handler
    buscarRango.addEventListener('click', async function() {
        const talonario = document.getElementById('talonario').value;
        const desdePedido = document.getElementById('desdePedido').value;
        const hastaPedido = document.getElementById('hastaPedido').value;

        if (!talonario || !desdePedido || !hastaPedido) {
            alert('Por favor, complete todos los campos');
            return;
        }

        if (parseInt(desdePedido) > parseInt(hastaPedido)) {
            alert('El número de pedido inicial debe ser menor o igual al final');
            return;
        }

        loadingSpinner.classList.remove('hidden');

        try {
            const data = [];
            for (let i = parseInt(desdePedido); i <= parseInt(hastaPedido); i++) {
                // Formato: espacio + 13 dígitos (completando con ceros a la izquierda)
                const numeroPedido = ' ' + i.toString().padStart(13, '0');
                data.push({
                    talonario: talonario,
                    numeroPedido: numeroPedido
                });
            }
            await validateAndProcessData(data, 'rango');
        } catch (error) {
            console.error('Error al procesar el rango:', error);
            alert('Error al procesar el rango de pedidos: ' + error.message);
        } finally {
            loadingSpinner.classList.add('hidden');
        }
    });

    // También actualizamos la función readExcelFile para mantener consistencia
    async function readExcelFile(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: ['talonario', 'numeroPedido'] });
                    
                    if (jsonData.length > 0 && jsonData[0].talonario === 'Talonario') {
                        jsonData.shift();
                    }
                    
                    // Formatear números de pedido con espacio inicial y 13 dígitos
                    jsonData.forEach(row => {
                        row.numeroPedido = row.numeroPedido.toString().padStart(13, '0');
                    });
                    
                    resolve(jsonData);
                } catch (error) {
                    reject(new Error('Error al leer el archivo Excel: ' + error.message));
                }
            };
            reader.onerror = () => reject(new Error('Error al leer el archivo'));
            reader.readAsArrayBuffer(file);
        });
    }

    async function validateAndProcessData(data, source) {
        try {
            const response = await fetch('assets/controllers/importController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'validar',
                    data: data,
                    source: source
                })
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const result = await response.json();
            
            if (result.success) {
                importedData = result.data;
                displayData(result.data);
                dataTableContainer.classList.remove('hidden');
            } else {
                throw new Error(result.message || 'Error en la validación');
            }
        } catch (error) {
            throw new Error('Error en la validación: ' + error.message);
        }
    }

    function displayData(data) {
        dataTableBody.innerHTML = '';
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.TALON_PED || row.talonario || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.NRO_PEDIDO || row.numeroPedido || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.COD_CLIENT || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.DESC_SUCURSAL || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.CANT_PEDID || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm ${row.exists ? 'text-green-500' : 'text-red-500'}">
                    ${row.exists ? 'Válido' : 'No encontrado'}
                </td>
            `;
            dataTableBody.appendChild(tr);
        });
    }

    // Remitir Ahora handler
document.getElementById('remitirBtn').addEventListener('click', async function() {
    if (!importedData.length) {
        mostrarAlerta('No hay datos para procesar', 'error');
        return;
    }

    const validData = importedData.filter(row => row.exists);
    if (validData.length === 0) {
        mostrarAlerta('No hay pedidos válidos para procesar', 'error');
        return;
    }

    if (!confirm(`¿Desea procesar ${validData.length} pedidos ahora?`)) {
        return;
    }

    loadingSpinner.classList.remove('hidden');
    
    try {
        const response = await fetch('assets/controllers/importController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'remitir',
                data: validData
            })
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            // Mensaje mejorado con más información
            const mensaje = `
                <div class="text-center">
                    <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">¡Proceso completado exitosamente!</h3>
                    <p class="mb-2">Número de tarea: <span class="font-semibold">${result.taskId || 'TASK_' + Date.now()}</span></p>
                    <p class="mb-2">Pedidos procesados: <span class="font-semibold">${validData.length}</span></p>
                    <p class="text-sm text-gray-500">Fecha: ${new Date().toLocaleString()}</p>
                </div>
            `;
            
            mostrarAlerta(mensaje, 'success');
            resetForm();
        } else {
            throw new Error(result.message || 'Error en el proceso de remisión');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al procesar la remisión: ' + error.message, 'error');
    } finally {
        loadingSpinner.classList.add('hidden');
    }
});

    // Programar handler
    const modalProgramar = document.getElementById('modalProgramar');
    const fechaProgramada = document.getElementById('fechaProgramada');
    const horaProgramada = document.getElementById('horaProgramada');
    const cantidadPedidos = document.getElementById('cantidadPedidos');
    const confirmarProgramacion = document.getElementById('confirmarProgramacion');
    const cancelarProgramacion = document.getElementById('cancelarProgramacion');
    const cerrarModal = document.getElementById('cerrarModal');

    // Set min date to today
    const today = new Date();
    fechaProgramada.min = today.toISOString().split('T')[0];

    // Programar handler
    document.getElementById('programarBtn').addEventListener('click', function() {
        if (!importedData.length) {
            alert('No hay datos para programar');
            return;
        }

        const validData = importedData.filter(row => row.exists);
        if (validData.length === 0) {
            alert('No hay pedidos válidos para programar');
            return;
        }

        // Actualizar cantidad de pedidos en el modal
        cantidadPedidos.textContent = validData.length;

        // Set default values
        const now = new Date();
        fechaProgramada.value = now.toISOString().split('T')[0];
        horaProgramada.value = now.toTimeString().slice(0,5);

        // Show modal
        modalProgramar.classList.remove('hidden');
    });

    // Close modal handlers
    [cerrarModal, cancelarProgramacion].forEach(element => {
        element.addEventListener('click', () => {
            modalProgramar.classList.add('hidden');
        });
    });

    // Click outside modal to close
    modalProgramar.addEventListener('click', function(e) {
        if (e.target === modalProgramar) {
            modalProgramar.classList.add('hidden');
        }
    });

    // Confirm handler
    confirmarProgramacion.addEventListener('click', async function() {
        const fecha = fechaProgramada.value;
        const hora = horaProgramada.value;
        
        if (!fecha || !hora) {
            alert('Por favor, seleccione fecha y hora');
            return;
        }

        const fechaHora = `${fecha} ${hora}:00`;
        const fechaProgramadaDate = new Date(fechaHora);
        const ahora = new Date();

        if (fechaProgramadaDate <= ahora) {
            alert('La fecha y hora de programación debe ser posterior a la actual');
            return;
        }

        const validData = importedData.filter(row => row.exists);
        modalProgramar.classList.add('hidden');
        await programarRemision(validData, fechaHora);
    });

    // Mantener la función programarRemision existente, solo actualizar el mensaje de éxito
    async function programarRemision(data, fechaProgramada) {
        loadingSpinner.classList.remove('hidden');

        try {
            const response = await fetch('assets/controllers/importController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'programar',
                    data: data,
                    scheduledDateTime: fechaProgramada
                })
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const result = await response.json();
            
            if (result.success) {
                alert(`Remisión programada exitosamente para ${fechaProgramada}`);
                resetForm();
            } else {
                throw new Error(result.message || 'Error al programar la remisión');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al programar la remisión: ' + error.message);
        } finally {
            loadingSpinner.classList.add('hidden');
        }
    }

    function resetForm() {
        fileInput.value = '';
        document.getElementById('desdePedido').value = '';
        document.getElementById('hastaPedido').value = '';
        importedData = [];
        dataTableBody.innerHTML = '';
        dataTableContainer.classList.add('hidden');
    }

    // Función para mostrar alertas personalizadas
function mostrarAlerta(mensaje, tipo) {
    // Crear elemento de alerta
    const alertaDiv = document.createElement('div');
    alertaDiv.className = `fixed inset-0 flex items-center justify-center z-50`;
    
    // Aplicar estilos según el tipo
    let colorClass = '';
    let iconClass = '';
    
    switch(tipo) {
        case 'success':
            colorClass = 'bg-green-50 border-green-500 text-green-700';
            iconClass = 'text-green-500 fa-check-circle';
            break;
        case 'error':
            colorClass = 'bg-red-50 border-red-500 text-red-700';
            iconClass = 'text-red-500 fa-exclamation-circle';
            break;
        default:
            colorClass = 'bg-blue-50 border-blue-500 text-blue-700';
            iconClass = 'text-blue-500 fa-info-circle';
    }
    
    // Contenido de la alerta
    alertaDiv.innerHTML = `
        <div class="bg-black bg-opacity-50 absolute inset-0"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 z-10 relative">
            ${mensaje}
            <div class="mt-6 flex justify-center">
                <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition-colors">
                    Aceptar
                </button>
            </div>
        </div>
    `;
    
    // Agregar al body
    document.body.appendChild(alertaDiv);
    
    // Cerrar al hacer click en el botón
    alertaDiv.querySelector('button').addEventListener('click', function() {
        alertaDiv.remove();
    });
}
});