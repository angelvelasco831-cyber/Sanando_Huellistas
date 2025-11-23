<?php
header("Content-Type: application/json");

include_once(__DIR__ . '/../Modelo/conexion.php');
include_once(__DIR__ . '/../Modelo/usuarioM.php');

$accion = $_GET['accion'] ?? '';

switch($accion){

    /* ------------------------------------------
       LISTAR
    ------------------------------------------ */
    case "listar":
        echo json_encode(UsuarioM::listar($conn));
        break;

    /* ------------------------------------------
       AGREGAR
    ------------------------------------------ */
    case "agregar":
        $data = json_decode(file_get_contents("php://input"), true);

        if(!$data){
            echo json_encode(["success"=>false, "mensaje"=>"No llegaron datos"]);
            exit;
        }

        $ok = UsuarioM::agregar($conn, $data);

        echo json_encode([
            "success"=>$ok,
            "mensaje"=>$ok ? "Usuario agregado" : "Error al agregar"
        ]);
        break;

    /* ------------------------------------------
       ACTUALIZAR
    ------------------------------------------ */
    case "actualizar":
        $data = json_decode(file_get_contents("php://input"), true);

        if(!$data){
            echo json_encode(["success"=>false, "mensaje"=>"No llegaron datos"]);
            exit;
        }

        $ok = UsuarioM::actualizar($conn, $data);

        echo json_encode([
            "success"=>$ok,
            "mensaje"=>$ok ? "Actualizado" : "Error al actualizar"
        ]);
        break;

    /* ------------------------------------------
       ELIMINAR
    ------------------------------------------ */
    case "eliminar":
        $data = json_decode(file_get_contents("php://input"), true);

        if(!isset($data['id'])){
            echo json_encode(["success"=>false, "mensaje"=>"Falta el ID"]);
            exit;
        }

        $ok = UsuarioM::eliminar($conn, $data['id']);

        echo json_encode([
            "success"=>$ok,
            "mensaje"=>$ok ? "Eliminado" : "No se pudo eliminar"
        ]);
        break;

    /* ------------------------------------------
       DEFAULT
    ------------------------------------------ */
    default:
        echo json_encode(["error"=>"Acción no válida"]);
}

$conn->close();
?>
