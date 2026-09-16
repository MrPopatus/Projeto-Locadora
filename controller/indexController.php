<?php
require_once '../model/carroModel.php';

session_start();

$carros = $carroModel->listarCarros();

    header("Location: ../index.php");

?>