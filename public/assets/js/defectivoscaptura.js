var urlAjax;
var accion;

var handleDataTableTools = function () {
    "use strict";
    $('#data-table').on('click', 'button#boton-editar', function (e) {
        

        var tabla = $('#data-table').DataTable();
        var fila = $(this).closest('tr');
        var datos = tabla.row(fila).data();
         
        $('#cde_depto').val(datos['cde_depto']).selectpicker('refresh');
        $.ajax({
            url: 'calidad/capturadefectivos/combobox2',
            type: 'GET',
            data: {
                departamento: datos['cde_depto']
            },
            success: function (data) {
                    var options = [];
                    for (var i = 0; i < data.operarios.length; i++) {
                        options.push('<option value="' + data.operarios[i]['llave'] + '">' + data.operarios[i]['valor'] + '</option>');
                    }
                    $('#cde_operario').append(options).selectpicker('refresh');
                $('#cde_operario').val(datos['cde_operario']).selectpicker('refresh');
                
                    var options = [];
                    for (var i = 0; i < data.defectos.length; i++) {
                        options.push('<option value="' + data.defectos[i]['llave'] + '">' + data.defectos[i]['valor'] + '</option>');
                    }
                    $('#cde_cda').append(options).selectpicker('refresh');
                $('#cde_cda').val(datos['cde_cda']).selectpicker('refresh');

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
        $('#input-accion').val('editar');
        $('#input-id').val(datos['cde_id']);
        
        $('#cde_cantidad').val(datos['cde_cantidad']);
        $('#cde_inspector').val(datos['cde_inspector']);
        var now = new Date(datos['cde_fecha']);
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear() + "-" + (month) + "-" + (day);
        $('#cde_fecha').val(today);
        
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
        $('#confirma-id').val(datos['cde_id']);

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
    $('#cde_cantidad').val('').selectpicker('refresh');
    $('#cde_fecha').val('').selectpicker('refresh');
}

function callnuevo() {
    limpiaModal();
    $('#input-accion').val('nuevo');
    InicializaComboBox();
   
    $("#cde_cda").empty();
    $('#cde_cda').selectpicker('destroy');
    $("#cde_depto").empty();
    $('#cde_depto').selectpicker('destroy');
    $('#cde_operario').selectpicker('destroy');
    $("#cde_operario").empty();
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#cde_fecha').val(today);

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
            InicializaComboBox2();
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
    $('#cda_depto').selectpicker('destroy');
    $('#cda_depto').append(options).selectpicker('refresh');
   
    $.ajax({
        url: 'calidad/capturadefectivos/combobox',
        type: 'GET',
        success: function (data) {        
            var options = [];       
            for (var i = 0; i < data.deptos.length; i++) {
                options.push('<option value="' + data.deptos[i]['llave'] + '">' + data.deptos[i]['valor'] + '</option>');
            }
            $('#cde_depto').append(options).selectpicker('refresh');                                                                                       
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

$("#cde_depto").on('change', function (e) {
    e.preventDefault();
    InicializaComboBox2();
})

function InicializaComboBox2() {
    $("#cde_operario").empty();
    $("#cde_cda").empty();
    $('#cde_cda').selectpicker('destroy');
    $('#cde_operario').selectpicker('destroy');
    $.ajax({
        url: 'calidad/capturadefectivos/combobox2',
        type: 'GET',
        data:{
            departamento : $("#cde_depto").val()
        },
        success: function (data) {
            if (data.operarios == null) {
                $("#cde_operario").empty();
                $("#cde_cda").empty();
            } else {
                var options = [];
                for (var i = 0; i < data.operarios.length; i++) {
                    options.push('<option value="' + data.operarios[i]['llave'] + '">' + data.operarios[i]['valor'] + '</option>');
                }
                $('#cde_operario').append(options).selectpicker('refresh');
                var options = [];
                for (var i = 0; i < data.defectos.length; i++) {
                    options.push('<option value="' + data.defectos[i]['llave'] + '">' + data.defectos[i]['valor'] + '</option>');
                }
                $('#cde_cda').append(options).selectpicker('refresh');   
            }
           
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
};
