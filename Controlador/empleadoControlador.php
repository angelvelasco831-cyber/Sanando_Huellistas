<?php
include_once(__DIR__ . '/../Modelo/conexion.php');
include_once(__DIR__ . '/../Modelo/empleadoModelo.php');

$accion = $_GET['accion'] ?? '';

switch ($accion) {

    // Listar empleados
    case 'listar':
        $empleados = EmpleadoModelo::obtenerEmpleados($conn);
        echo json_encode($empleados);
        break;

    // Agregar empleado
    case 'agregar':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['nombre']) && !empty($data['apellidoPaterno']) && !empty($data['apellidoMaterno']) && !empty($data['telefono']) && !empty($data['correo']) && !empty($data['puesto'])) {
            $ok = EmpleadoModelo::agregarEmpleado(
                $conn,
                $data['nombre'],
                $data['apellidoPaterno'],
                $data['apellidoMaterno'],
                $data['telefono'],
                $data['correo'],
                $data['puesto'],
                $data['cedula'] ?? null
            );

            echo json_encode(["success" => $ok, "mensaje" => $ok ? "Empleado agregado correctamente" : "Error al agregar"]);
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    // Actualizar empleado
    case 'actualizar':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['id_empleado'])) {
            $ok = EmpleadoModelo::actualizarEmpleado(
                $conn,
                $data['id_empleado'],
                $data['nombre'],
                $data['apellidoPaterno'],
                $data['apellidoMaterno'],
                $data['telefono'],
                $data['correo'],
                $data['puesto'],
                $data['cedula'] ?? null
            );

            echo json_encode(["success" => $ok, "mensaje" => $ok ? "Empleado actualizado correctamente" : "Error al actualizar"]);
        } else {
            echo json_encode(["error" => "Falta el ID del empleado"]);
        }
        break;

    // Eliminar empleado
case 'eliminar':
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id_empleado'] ?? null;

    if ($id) {
        $ok = EmpleadoModelo::eliminarEmpleado($conn, $id);
        echo json_encode(["success" => $ok, "mensaje" => $ok ? "Empleado eliminado correctamente" : "No se pudo eliminar"]);
    } else {
        echo json_encode(["error" => "ID no recibido"]);
    }
    break;

    // Acción no válida
    default:
        echo json_encode(["error" => "Acción no reconocida"]);
        break;
}

// Cerrar conexión
$conn->close();
?>
