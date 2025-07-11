const obtenerDetalleTarea = async () => {
    let idTareaEnc = document.querySelector('#idTareaEnc').textContent
    $.ajax({
        url: "assets/controllers/importController.php",
        type: "POST",
        data: {
            idTarea: idTareaEnc,
           action:  "getDetalleTarea"

        },
        success: function (data) {

            console.log(data,'response');

            armarTabla(data);
        },
        error: function (error) {
            console.log(error,'error');
        }
    });
}

obtenerDetalleTarea();

function armarTabla(data) {
    const tabla = document.querySelector('#tabla-detalles');
    data.forEach(element => {
        // console.log(element,'element');
        // este log COD_CLIENT
        // : 
        // "GTARCO"
        // CREATED_AT
        // : 
        // "2025-05-28T11:09:54.863Z"
        // FINALIZACION
        // : 
        // "2025-05-28T11:09:54.863Z"
        // ID
        // : 
        // 1
        // ID_TAREA
        // : 
        // 1
        // NRO_PEDIDO
        // : 
        // "0000100295424"
        // NRO_SUCURS
        // : 
        // "2"
        // UPDATED_AT
        // : 
        // "2025-05-28T11:09:54.863Z"

    //     tabla      <table class="min-w-full">
    //     <thead class="bg-gray-50">
    //         <tr>
    //             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Pedido</th>
    //             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código Cliente</th>
    //             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
    //             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
    //             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número Remito</th>
    //             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número Factura</th>
    //         </tr>
    //     </thead>
    //     <tbody class="bg-white divide-y divide-gray-200" id="tabla-detalles">
    //     </tbody>
    // </table>

    const tr = document.createElement('tr');

    tr.classList.add('hover:bg-gray-50');

    const td1 = document.createElement('td');
    td1.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-900');
    td1.textContent = element.NRO_PEDIDO;

    tr.appendChild(td1);

    const td2 = document.createElement('td');
    td2.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
    td2.textContent = element.COD_CLIENT;

    tr.appendChild(td2);

    const td3 = document.createElement('td');
    td3.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
    td3.textContent = element.NRO_SUCURS;

    tr.appendChild(td3);

    const td4 = document.createElement('td');
    td4.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
    td4.textContent = element.cantidad;

    tr.appendChild(td4);

    const td5 = document.createElement('td');
    td5.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
    td5.textContent = element.N_COMP;

    tr.appendChild(td5);

    const td6 = document.createElement('td');
    td6.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
    td6.textContent = element.NRO_FACT;

    tr.appendChild(td6);

    tabla.appendChild(tr);

    console.log(tabla,'tabla'); 


    });
}

