<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listado de elementos</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="bg-white">

<header>
    <nav class="navbar navbar-light bg-light">
        <h1 class="navbar-brand mb-0">Lista de elementos</h1>
    </nav>
</header>

<main>
    <section class="container">

        <div class="row mt-5">
            <div class="col-12">
                <div class="text-center">
                    <button id="botonAgregar" type="button" class="btn btn-primary" onclick="mostrarFormulario()">Agregar elemento</button>
                    <button id="botonCancelar" type="button" class="btn btn-secondary d-none" onclick="ocultarFormulario()">Cancelar</button>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="col-md-6 mx-auto">
                    <form class="form-group d-none" id="formulario" method="post">

                        <input type="hidden" name="position" value="1">

                        <div class="text-center mb-2">
                            <img class="img-fluid" id="preview" src="#" alt="" width="300" height="300">
                        </div>

                        <input type="file" name="image" id="image" class="d-none">
                        <button type="button" id="ImageBrowse" class="btn btn-block btn-primary"
                                onclick="document.getElementById('image').click()">
                            <i class="fa fa-upload"></i> Subir imagen
                        </button>
                        <p id="error-image" class="d-none text-danger"></p>
                        <br>

                        <textarea rows="5" maxlength="200" class="form-control"
                                  placeholder="Agregar una descripción"
                                  name="description" id="description"></textarea>
                        <p id="error-description" class="d-none text-danger"></p>
                        <br>

                        <p id="error-position" class="d-none text-danger"></p>
                        <button type="submit" class="btn btn-block btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div>
                    <p class="text-center">Cantidad de elementos (<span id="cantidad">0</span>)</p>
                </div>
                <ul class="list-unstyled sortable" id="listas"></ul>
            </div>
        </div>

    </section>

</main>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/html.sortable.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
