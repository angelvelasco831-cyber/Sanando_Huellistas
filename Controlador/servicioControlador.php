<?php
// Cargar modelo y conexión
require_once __DIR__ . '/../Modelo/conexion.php';
require_once __DIR__ . '/../Modelo/servicioModelo.php';

// Determinar acción
$accion = $_GET['accion'] ?? '';

switch($accion){

    // Listar servicios
    case 'listar':
        $servicios = ServicioModelo::obtenerServicios($conn);
        echo json_encode($servicios);
        break;

    // Agregar servicio
    case 'agregar':
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data['nombre']) && !empty($data['descripcion']) && isset($data['precio']) && !empty($data['duracion'])){
            $ok = ServicioModelo::agregarServicio($conn, $data['nombre'], $data['descripcion'], $data['precio'], $data['duracion']);
            echo json_encode(["success"=>$ok, "mensaje"=>$ok?"Servicio agregado correctamente":"Error al agregar"]);
        } else {
            echo json_encode(["error"=>"Datos incompletos"]);
        }
        break;

    // Actualizar servicio
    case 'actualizar':
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data['id_servicio']) && !empty($data['duracion'])){
            $ok = ServicioModelo::actualizarServicio($conn, $data['id_servicio'], $data['nombre'], $data['descripcion'], $data['precio'], $data['duracion']);
            echo json_encode(["success"=>$ok, "mensaje"=>$ok?"Servicio actualizado correctamente":"Error al actualizar"]);
        } else {
            echo json_encode(["error"=>"Falta el ID del servicio o duración"]);
        }
        break;

    // Eliminar servicio
    case 'eliminar':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id_servicio'] ?? null;
        if($id){
            $ok = ServicioModelo::eliminarServicio($conn, $id);
            echo json_encode(["success"=>$ok, "mensaje"=>$ok?"Servicio eliminado correctamente":"No se pudo eliminar"]);
        } else {
            echo json_encode(["error"=>"ID no recibido"]);
        }
        break;

    default:
        echo json_encode(["error"=>"Acción no reconocida"]);
        break;
}

$conn->close();
?>
