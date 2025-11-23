<?php
session_start();
header("Content-Type: application/json");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");

include_once(__DIR__ . '/../Modelo/conexion.php');
include_once(__DIR__ . '/../Modelo/usuarioModelo.php');

/* ---------------------------------------------------------
   LEER JSON O FORM-DATA AUTOMÁTICAMENTE
--------------------------------------------------------- */
$inputRaw = file_get_contents("php://input");
$inputJson = json_decode($inputRaw, true);

$usuario = "";
$password = "";

// Si viene JSON
if (is_array($inputJson)) {
    $usuario = trim($inputJson["usuario"] ?? "");
    $password = trim($inputJson["password"] ?? "");
}

// Si viene por POST normal
if ($usuario === "" && $password === "") {
    $usuario = trim($_POST["usuario"] ?? "");
    $password = trim($_POST["password"] ?? "");
}

if ($usuario === "" || $password === "") {
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

/* ---------------------------------------------------------
   VERIFICAR USUARIO
--------------------------------------------------------- */
$datos = UsuarioModelo::verificarUsuario($conn, $usuario);

if (!$datos) {
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit;
}

/* ---------------------------------------------------------
   VERIFICAR CONTRASEÑA
--------------------------------------------------------- */
if ($password !== $datos["password"]) {
    echo json_encode(["error" => "Contraseña incorrecta"]);
    exit;
}


/* ---------------------------------------------------------
   INICIAR SESIÓN
--------------------------------------------------------- */
session_regenerate_id(true);
$_SESSION["usuario"] = $datos["usuario"];
$_SESSION["rol"] = $datos["rol"];

/* ---------------------------------------------------------
   REDIRECCIÓN POR ROL
--------------------------------------------------------- */
switch (strtolower($datos["rol"])) {
    case "admin":
        $ruta = "Vista/Menu.php";
        break;
    case "recepcionista":
        $ruta = "Vista/Menu.php";
        break;
    case "veterinario":
        $ruta = "Vista/Menu.php";
        break;
    default:
        echo json_encode(["error" => "Rol desconocido"]);
        exit;
}

echo json_encode([
    "success" => true,
    "redireccion" => $ruta
]);

$conn->close();
?>
