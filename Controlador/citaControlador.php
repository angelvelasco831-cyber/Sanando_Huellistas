<?php
include_once(__DIR__ . '/../Modelo/conexion.php');
include_once(__DIR__ . '/../Modelo/citaModelo.php');

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'listar':
        $citas = CitaModelo::obtenerCitas($conn);
        echo json_encode($citas);
        break;

    case 'agregar':
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data['cliente']) && !empty($data['mascota']) && !empty($data['servicio'])){
            $ok = CitaModelo::agregarCita($conn, $data['cliente'], $data['mascota'], $data['servicio'], $data['fecha'], $data['hora'], $data['observaciones']);
            echo json_encode(["success"=>$ok]);
        } else {
            echo json_encode(["error"=>"Datos incompletos"]);
        }
        break;

    case 'actualizar':
    $data = json_decode(file_get_contents("php://input"), true);
    if(!empty($data['id'])){
        $ok = CitaModelo::actualizarCita($conn, $data['id'], $data['cliente'], $data['mascota'], $data['servicio'], $data['fecha'], $data['hora'], $data['observaciones']);
        echo json_encode(["success"=>$ok]);
    } else {
        echo json_encode(["error"=>"ID no recibido"]);
    }
    break;

    case 'eliminar':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
        if($id){
            $ok = CitaModelo::eliminarCita($conn, $id);
            echo json_encode(["success"=>$ok]);
        } else {
            echo json_encode(["error"=>"ID no recibido"]);
        }
        break;

    default:
        echo json_encode(["error"=>"Acción no válida"]);
        break;
}

$conn->close();
?>
