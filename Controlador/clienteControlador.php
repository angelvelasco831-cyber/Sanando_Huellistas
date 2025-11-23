<?php
// ✅ Conexión a la base de datos
include_once(__DIR__ . '/../Modelo/conexion.php');

// ✅ Importar el modelo
include_once(__DIR__ . '/../Modelo/clienteModelo.php');

// ✅ Leer la acción
$accion = $_GET['accion'] ?? '';

switch ($accion) {

  // Listar todos los clientes
  case 'listar':
    $clientes = ClienteModelo::obtenerClientes($conn);
    echo json_encode($clientes);
    break;

  // Agregar cliente
  case 'agregar':
    $data = json_decode(file_get_contents("php://input"), true);
    if (!empty($data['nombre']) && !empty($data['telefono']) && !empty($data['correo'])) {
      $ok = ClienteModelo::agregarCliente($conn, $data['nombre'], $data['telefono'], $data['correo']);
      echo json_encode(["success" => $ok, "mensaje" => $ok ? "Cliente agregado correctamente" : "Error al agregar"]);
    } else {
      echo json_encode(["error" => "Datos incompletos"]);
    }
    break;

  // Actualizar cliente
  case 'actualizar':
    $data = json_decode(file_get_contents("php://input"), true);
    if (!empty($data['id'])) {
      $ok = ClienteModelo::actualizarCliente($conn, $data['id'], $data['nombre'], $data['telefono'], $data['correo']);
      echo json_encode(["success" => $ok, "mensaje" => $ok ? "Cliente actualizado correctamente" : "Error al actualizar"]);
    } else {
      echo json_encode(["error" => "Falta el ID del cliente"]);
    }
    break;

  // Eliminar cliente
  case 'eliminar':
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if ($id) {
      $ok = ClienteModelo::eliminarCliente($conn, $id);
      echo json_encode(["success" => $ok, "mensaje" => $ok ? "Cliente eliminado correctamente" : "No se pudo eliminar"]);
    } else {
      echo json_encode(["error" => "ID no recibido"]);
    }
    break;

  // Acción no válida
  default:
    echo json_encode(["error" => "Acción no reconocida"]);
    break;
}

// Cerrar conexión al final
$conn->close();
?>
