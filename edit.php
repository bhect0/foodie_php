<?php

include 'functions.php';

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
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $receta = [
            "id" => $_GET['id'],
            "titulo" => $_POST['nombre'],
            "descripcion" => $_POST['descripcion'],
            "foto" => $_POST['foto'],
            "pasos" => $_POST['pasos'],
            "ingredientes" => $_POST['ingredientes'],
            "tiempo_estimado" => $_POST['tiempoestimado']
        ];

        // TODO: doc
        $consultaSQL = "UPDATE receta SET
        titulo = :titulo,
        descripcion = :descripcion,
        foto = :foto,
        pasos = :pasos,
        ingredientes = :ingredientes,
        tiempo_estimado = :tiempo_estimado,
        updated_at = NOW()
        WHERE id = :id";

        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($receta);

    } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
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
try {
    $dsn = 'mysql:host='.$config['db']['host'].';dbname='.$config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $id = $_GET['id'];
    $sql = "SELECT * FROM receta WHERE id =".$id;

    $sentencia = $conexion->prepare($sql);
    $sentencia->execute();

    $receta = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$receta) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado la receta';
    }

} catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
}

if (isset($_POST['submit']) && !$resultado['error']) {
    echo <<<EOT
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">El alumno ha sido actualizado correctamente</div>
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
                            <label for="tiempoestimado">Tiempo estimado</label>
                            <input type="text" name="tiempoestimado" id="tiempoestimado" value="$tiempo_estimado" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    EOT;
}

require "templates/footer.php";