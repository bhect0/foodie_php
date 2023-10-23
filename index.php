<?php
require_once 'functions.php';

$error = false;
$config = include 'config.php';

$recetas = [];

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    if (isset($_POST['apellido'])) {
        $sql = "SELECT * FROM receta WHERE titulo LIKE '%" . $_POST['nombre'] . "%'";
    } else {
        $sql = "SELECT * FROM receta";
    }

    $sentencia = $conexion->prepare($sql);
    $sentencia->execute();

    $recetas = $sentencia->fetchAll();

    $titulo = isset($_POST['apellido']) ? 'Lista de alumnos (' . $_POST['apellido'] . ')' : 'Lista de alumnos';

} catch(PDOException $error) {
    $error = $error->getMessage();
}

include "templates/header.php";

if ($error) {
    echo <<<EOF
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        $error
                    </div>
                </div>
            </div>
        </div>
    EOF;
}

echo <<<EOF
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="create.php"  class="btn btn-primary mt-4">Nueva receta</a>
                <hr>
                <form method="post" class="form-inline">
                    <div class="form-group mr-3">
                        <input type="text" id="nombre" name="nombre" placeholder="Buscar por nombre" class="form-control">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-3">Lista de alumnos</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>nombre</th>
                        <th>foto</th>
                        <th>pasos</th>
                        <th>ingredientes</th>
                        <th>tiempo_estimado</th>
                        <th>ingredientes</th>
                        <th>Fecha de creación</th>
                        <th>Fecha de modificación</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
EOF;
                    if ($recetas && $sentencia->rowCount() > 0) {
                        foreach ($recetas as $receta) {
                            $id = escapar($receta["id"]);
                            $titulo = escapar($receta["titulo"]);
                            $descripcion = escapar($receta["descripcion"]);
                            $foto = escapar($receta["foto"]);
                            $pasos = escapar($receta["pasos"]);
                            $ingredientes = escapar($receta["ingredientes"]);
                            $tiempo_estimado = escapar($receta["tiempo_estimado"]);
                            $created_at = escapar($receta["created_at"]);
                            $updated_at = escapar($receta["updated_at"]);

                            echo <<<EOF
                                <tr>
                                    <td>$id</td>
                                    <td>$titulo</td>
                                    <td>$descripcion</td>
                                    <td>$foto</td>
                                    <td>$pasos</td>
                                    <td>$ingredientes</td>
                                    <td>$tiempo_estimado</td>
                                    <td>$created_at</td>
                                    <td>$updated_at</td>
                                    <td>
                                        <a href="delete.php?id=$id">🗑️Borrar</a>
                                        <a href="edit.php?id=$id">✏️Editar</a>
                                    </td>
                                </tr>
                            EOF;
                        }
                    }
echo <<<EOF
                    <tbody>
                </table>
            </div>
        </div>
    </div>
EOF;

include "templates/footer.php";