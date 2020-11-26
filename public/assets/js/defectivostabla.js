var urlAjax;
var accion;

var handleDataTableTools = function () {
    "use strict";
    $('#data-table').on('click', 'button#boton-editar', function (e) {
        e.preventDefault();

        var tabla = $('#data-table').DataTable();
        var fila = $(this).closest('tr');
        var datos = tabla.row(fila).data();

        limpiaModal();

        $('#input-accion').val('editar');
        $('#input-id').val(datos['cda_id']);

        $('#cda_depto').val(datos['cda_depto']).selectpicker('refresh');

        $('#cda_descripcion').val(datos['cda_descripcion']);
        $('#cda_pond').val(datos['cda_pond']);
        $('#cda_activo').prop('checked', datos['cda_activo']);

        $('#modalNuevo').on('shown.bs.modal', function () {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            $('#cda_depto').focus();
            //}).modal("show").draggable({
            //    handle: ".modal-header"
        });
        $('#modalNuevo').modal('show');
    });

    $('#data-table').on('click', 'button#boton-eliminar', function (e) {
        e.preventDefault();

        var tabla = $('#data-table').DataTable();
        var fila = $(this).closest('tr');
        var datos = tabla.row(fila).data();
        $('#confirma-id').val(datos['cda_id']);

        $('#confirma').on('shown.bs.modal', function () {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
        });
        $('#confirma').modal('show');
    });
};

function validaModal() {
    var errorDiv = document.getElementById("errors");
    errorDiv.innerHTML = "";
    if ($('#cda_depto').val() == '') {
        mensaje = "Ingresa departamento";
        errorDiv.innerHTML += "<div class='alert alert-danger' role='alert'><strong>" + mensaje + "</strong></div>";
        return false;
    }
    if ($('#cda_descripcion').val() == '') {     
        mensaje = "Ingresa descripción";
        errorDiv.innerHTML += "<div class='alert alert-danger' role='alert'><strong>" +mensaje+ "</strong></div>";
        return false;
    }
    if ($('#cda_pond').val() == '') {
        mensaje = "Ingresa Pond";
        errorDiv.innerHTML += "<div class='alert alert-danger' role='alert'><strong>" + mensaje + "</strong></div>";
        return false;
    }
   
    return true;
}

function limpiaModal() {
    $('#input-accion').val('');
    $('#input-id').val('');
    $('#cda_descripcion').val('');
    $('#cda_pond').val('');
    $('#cda_activo').val('');
    $('#cda_depto').val('').selectpicker('refresh');
}

function callnuevo() {
    limpiaModal();
    $('#input-accion').val('nuevo');
    $('#modalNuevo').on('shown.bs.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function () {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
        $('#cda_depto').focus();
        $('#cda_activo').prop('checked', 1);
    });
    $('#modalNuevo').modal('show');
}

var TableManageTableToolsEditor = function () {
    "use strict";
    return {
        init: function () {
            handleDataTableTools();  
            InicializaComboBox();
        }
    }
}();

/**
 * Petición que obtiene la información para llenar los combobox
 * @event -
 * @param  -
 * @return  -
 */
function InicializaComboBox() {
    var options = [];   

    $("#cda_depto").empty();
    $('#cda_depto').append(options).selectpicker('refresh');

    $.ajax({
        url: 'calidad/tabladefectivos/combobox',
        type: 'GET',
        success: function (data) {        
            var options = [];       
            for (var i = 0; i < data.deptos.length; i++) {
                options.push('<option value="' + data.deptos[i]['llave'] + '">' + data.deptos[i]['valor'] + '</option>');
            }
            $('#cda_depto').append(options).selectpicker('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            var error = JSON.parse(xhr.responseText);
            bootbox.alert({
                size: "large",
                title: "<h4><i class='fa fa-info-circle'></i> Alerta</h4>",
                message: "<div class='alert alert-danger m-b-0'> Mensaje : " + error['mensaje'] + "<br>" +
                    (error['codigo'] != '' ? "Código : " + error['codigo'] + "<br>" : '') +
                    (error['clase'] != '' ? "Clase : " + error['clase'] + "<br>" : '') +
                    (error['linea'] != '' ? "Línea : " + error['linea'] + "<br>" : '') + '</div>'
            });
        }
    });

}

