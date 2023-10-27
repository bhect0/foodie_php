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

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $id = $_GET['id'];
    $consultaSQL = "DELETE FROM receta WHERE id =" . $id;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    header('Location: index.php');

} catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
}

require "templates/header.php";

$msj = $resultado['mensaje'];
echo <<<EOT
    <div class="container mt-2">
          <div class="row">
                <div class="col-md-12">
                      <div class="alert alert-danger" role="alert">
                          $msj 
                      </div>
                </div>
          </div>
    </div>
EOT;

require "templates/footer.php";