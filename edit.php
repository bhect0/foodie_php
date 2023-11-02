<?php

include 'functions.php';
csrf();

if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}

$config = include 'config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['id'])) { // TODO: || id no valido
    $resultado['error'] = true;
    $resultado['mensaje'] = 'La receta no existe';
}

if (isset($_POST['submit'])) {
    $receta = [
        "id" => $_GET['id'],
        "titulo" => $_POST['nombre'],
        "descripcion" => $_POST['descripcion'],
        "foto" => $_POST['foto'],
        "pasos" => $_POST['pasos'],
        "ingredientes" => $_POST['ingredientes'],
        "tiempo_estimado" => $_POST['tiempo_estimado']
    ];

    // TODO: doc
    $sql = "UPDATE receta SET
    titulo = :titulo,
    descripcion = :descripcion,
    foto = :foto,
    pasos = :pasos,
    ingredientes = :ingredientes,
    tiempo_estimado = :tiempo_estimado,
    updated_at = NOW()
    WHERE id = :id";

    exec_query($sql, $receta);

}

if ($resultado['error']) {
    echo '<div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">'.$resultado['mensaje'].'</div>
            </div>
        </div>
    </div>';
    die;
}

    $id = $_GET['id'];
    $sql = "SELECT * FROM receta WHERE id ='$id'";

    $receta = get_record($sql);

    if (!$receta) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado la receta';
    }


if (isset($_POST['submit']) && !$resultado['error']) {
    echo <<<EOT
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">La receta ha sido actualizada correctamente</div>
                </div>
            </div>
        </div>
    EOT;
}

require 'templates/header.php';

if (isset($receta) && $receta) {
    $titulo = escapar($receta['titulo']);
    $descripcion = escapar($receta["descripcion"]);
    $foto = escapar($receta["foto"]);
    $pasos = escapar($receta["pasos"]);
    $ingredientes = escapar($receta["ingredientes"]);
    $tiempo_estimado = escapar($receta["tiempo_estimado"]);
    $created_at = escapar($receta["created_at"]);
    $updated_at = escapar($receta["updated_at"]);
    $csrf = escapar($_SESSION['csrf']);

    echo <<<EOT
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4">Editando la receta: $titulo</h2>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                            <label for="nombre">Titulo/nombre</label>
                            <input type="text" name="nombre" id="nombre" value="$titulo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">descripcion</label>
                            <input type="text" name="descripcion" id="descripcion" value="$descripcion" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="foto">foto</label>
                            <input type="url" name="foto" id="foto" value="$foto" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="pasos">pasos</label>
                            <input type="text" name="pasos" id="pasos" value="$pasos" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="ingredientes">ingredientes</label>
                            <input type="text" name="ingredientes" id="ingredientes" value="$ingredientes" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tiempo_estimado">Tiempo estimado</label>
                            <input type="text" name="tiempo_estimado" id="tiempo_estimado" value="$tiempo_estimado" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
                        </div>
                        <input name="csrf" type="hidden" value="$csrf">
                    </form>
                </div>
            </div>
        </div>
    EOT;
}

require "templates/footer.php";