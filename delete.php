<?php

include 'functions.php';
csrf();

if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}

$id = $_GET['id'];
$sql = "DELETE FROM receta WHERE id =" . $id;

require "templates/header.php";

if (exec_query($sql)) {
    header('Location: index.php');
} else {
    echo <<<EOT
    <div class="container mt-2">
          <div class="row">
                <div class="col-md-12">
                      <div class="alert alert-danger" role="alert">
                          error de base de datos 
                      </div>
                </div>
          </div>
    </div>
    EOT;
}

require "templates/footer.php";