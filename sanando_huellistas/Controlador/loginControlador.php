<?php
session_start();

include_once(__DIR__ . '/../Modelo/conexion.php');
include_once(__DIR__ . '/../Modelo/usuarioModelo.php');

$usuario = $_POST["usuario"] ?? "";
$password = $_POST["password"] ?? "";

if ($usuario === "" || $password === "") {
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

// Obtener datos del usuario
$datos = UsuarioModelo::verificarUsuario($conn, $usuario);

if (!$datos) {
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit;
}

// Validar contraseña directa (sin hash)
if ($password !== $datos["password"]) {
    echo json_encode(["error" => "Contraseña incorrecta"]);
    exit;
}

// Guardamos en sesión
$_SESSION["usuario"] = $datos["usuario"];
$_SESSION["rol"] = $datos["rol"];

// Convertimos rol a minúsculas para evitar errores
$rol = strtolower($datos["rol"]);

if ($rol === "admin") {
    $ruta = "../Vista/Menu.html";
}
else if ($rol === "recepcionista") {
    $ruta = "../Vista/MenuRecepcion.html";
}
else if ($rol === "veterinario") {
    $ruta = "../Vista/MenuVeterinario.html";
}
else {
    echo json_encode(["error" => "Rol desconocido: " . $datos["rol"]]);
    exit;
}

echo json_encode([
    "success" => true,
    "redireccion" => $ruta
]);

$conn->close();
