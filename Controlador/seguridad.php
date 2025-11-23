<?php
session_start();

// Si no ha iniciado sesión
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["rol"])) {
    header("Location: ../Vista/Login.html");
    exit;
}

// Función para verificar rol
function permitirSolo($rolesPermitidos = []) {
    if (!in_array($_SESSION["rol"], $rolesPermitidos)) {
        // Si no tiene permiso, lo mandas fuera
        header("Location: ../Vista/NoAutorizado.html");
        exit;
    }
}
?>