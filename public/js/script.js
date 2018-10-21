var $body = $("body");

$(document).ready(function () {
    obtenerTareas();
});

$body.on("submit", "#formulario", function (_evt) {
    _evt.preventDefault();
    let formData = new FormData(this);
    let $submit = $(this).find('[type=submit]');

    $submit.attr('disabled', 'disabled');

    $(".text-danger").empty().addClass('d-none');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function () {
            $submit.removeAttr('disabled');
            obtenerTareas();
            resetFormulario();
            ocultarFormulario();
        },
        error: function (response) {
            $submit.removeAttr('disabled');
            $.each(response.responseJSON.errors, function (key, value) {
                $("#error-" + key).empty().removeClass('d-none').append(value);
            });
        }
    });
});

$body.on('click', '.edit', function (_evt) {
    _evt.preventDefault();

    let $form = $("#formulario");
    let id = $(this).data('id');
    let $box = $("#box-" + id);
    let image = $box.find('.edit-image').attr('src');
    let description = $box.find('.edit-description').text();

    $form.find("[name=description]").val(description);
    $form.find("#preview").attr("src", image);
    if (!$form.find("[name=_method]").length) {
        $form.append('<input name="_method" type="hidden" value="PUT">');
    }
    $form.attr('action', 'api/tareas/' + id);
    $form.removeClass('d-none');
    $("#botonAgregar").addClass('d-none');
    $("#botonCancelar").removeClass('d-none');
});

$body.on('click', '.delete', function (_evt) {
    _evt.preventDefault();
    let $element = $(this);
    let response = confirm("¿Seguro que desea eliminar el elemento?");

    if (response !== true) {
        return;
    }

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: 'api/tareas/' + $element.data('id'),
        type: 'DELETE',
        success: function () {
            let cantidad = parseInt($("#cantidad").text()) - 1;
            $element.closest("li").remove();
            $("#cantidad").empty().append(cantidad);
        },
        error: function (response) {
            alert(response.responseJSON.message);
        }
    });
});

$("#image").change(function () {
    previewImage(this);
    if (!$("#error-image").hasClass('d-none')) {
        $("#error-image").empty().addClass('d-none');
    }
});

$("#description").keyup(function () {
    if (!$("#error-description").hasClass('d-none')) {
        $("#error-description").empty().addClass('d-none');
    }
});

function obtenerTareas() {
    $.get("/api/tareas", function (response, status) {
        if (status === 'success' && response.data.length) {
            let template;

            $("#cantidad").empty().append(response.data.length);

            $('#listas').empty();

            $.each(response.data, function (key, value) {
                template = '<li class="bg-light media-body p-5 mb-2" id="box-'+ value.id +'">' +
                    '<div class="media">' +
                    '<img class="mr-3 img-fluid edit-image" src="' + value.image + '" alt=".">' +
                    '<div class="media-body">' +
                    '<p class="mt-0 mb-1 edit-description">' + value.description + '</p>' +
                    '</div>' +
                    '<a href="#" class="text-muted mx-1 edit" data-id="' + value.id + '"><i class="fa fa-pencil"></i></a>' +
                    '<a href="#" class="text-muted mx-1 delete" data-id="' + value.id + '"><i class="fa fa-trash"></i></a>' +
                    '<span class="text-muted mx-1 cursor-move" draggable="true"><i class="fa fa-arrows"></i></span>' +
                    '</div>' +
                    '</li>';

                $('#listas').append(template);

                sortable("#listas", {
                    items: ':not(.disabled)',
                    handle: '.fa-arrows'
                });

                sortable("#listas")[0].addEventListener('sortupdate', function(e) {
                    if (e.detail.newStartList.length > 1) {
                        let ids = [];
                        $.each(e.detail.newStartList, function (key, value) {
                            ids.push($(value).find('.edit').data('id'));
                        });
                        $.post("/api/reorganizar", {ids: ids});
                    }
                });
            });
        }
    });
}

function mostrarFormulario() {
    let $form = $("#formulario");
    $form.removeClass('d-none');
    $form.find('[name=_method]').remove();
    $form.attr('action', "api/tareas");
    $("#botonAgregar").addClass('d-none');
    $("#botonCancelar").removeClass('d-none');
}

function ocultarFormulario() {
    let $form = $("#formulario");
    $form.addClass('d-none');
    $form.find('[name=_method]').remove();
    $form.attr('action', '');
    $("#botonAgregar").removeClass('d-none');
    $("#botonCancelar").addClass('d-none');
    resetFormulario();
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $('#preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        $('#preview').attr('src', '#');
    }
}

function resetFormulario() {
    $('#preview').attr('src', '#');
    $('#formulario').each(function () {
        this.reset();
    });
}
