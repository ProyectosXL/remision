const loadingSpinner = document.getElementById('loadingSpinner');
loadingSpinner.classList.remove('hidden');
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
            loadingSpinner.classList.add('hidden');
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
    td4.textContent = element.CANTIDAD;

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


function formatFechaHora(fechaString, flag = false) {
    if (!fechaString) return '-';
    const [fecha, hora] = fechaString.split('T');
    const [anio, mes, dia] = fecha.split('-');
    const [hh, mm] = hora.replace('Z', '').split(':');
    return `${dia}/${mes}/${anio} ${hh}:${mm}`;
}


