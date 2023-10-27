<?php

include 'functions.php';
csrf();

if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}

if (isset($_POST['submit'])) {
    $resultado = [
        'error' => false,
        'mensaje' => 'Receta ' . escapar($_POST['nombre']) . ' creada con éxito'
    ];

    $config = include 'config.php';

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        // Código que insertará un alumno
        $receta = [
            "titulo" => $_POST['nombre'],
            "descripcion" => $_POST['descripcion'],
            "foto" => $_POST['foto'],
            "pasos" => $_POST['pasos'],
            "ingredientes" => $_POST['ingredientes'],
            "tiempoestimado" => $_POST['tiempoestimado']
        ];

        $consultaSQL = "INSERT INTO receta (titulo, descripcion, foto, pasos, ingredientes, tiempo_estimado) values (:" . implode(", :", array_keys($receta)) . ")";
        echo $consultaSQL;
        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($receta);

    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}




include "templates/header.php";

$csrf = escapar($_SESSION['csrf']);

if (isset($resultado)) {
    $err_result = $resultado['error'] ? 'danger' : 'success';
    $msj =  $resultado['mensaje'];

    echo <<<EOT
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-$err_result" role="alert">
                        $msj
                    </div>
                </div>
            </div>
        </div>
    EOT;
}

echo <<<EOT
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Crear receta</h2>
                <hr>
                <form method="post">
                    <input name="csrf" type="hidden" value="$csrf">
                    <div class="form-group">
                        <label for="nombre">Titulo/nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">descripcion</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="foto">foto</label>
                        <input type="url" name="foto" id="foto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="pasos">pasos</label>
                        <input type="text" name="pasos" id="pasos" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="ingredientes">ingredientes</label>
                        <input type="text" name="ingredientes" id="ingredientes" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tiempoestimado">Tiempo estimado</label>
                        <input type="text" name="tiempoestimado" id="tiempoestimado" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
                        <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
EOT;

include "templates/footer.php";
