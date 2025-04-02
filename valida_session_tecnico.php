<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['nivel'], ['tecnico', 'administrador'])) {
    header("Location: login.php");
    exit;
}
?>
