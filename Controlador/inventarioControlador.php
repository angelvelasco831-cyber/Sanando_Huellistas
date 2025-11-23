<?php
include_once(__DIR__ . '/../Modelo/conexion.php');
include_once(__DIR__ . '/../Modelo/inventarioModelo.php');

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'listar':
        $productos = InventarioModelo::obtenerProductos($conn);
        echo json_encode($productos);
        break;

    case 'agregar':
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data['nombreProducto']) && !empty($data['categoria'])){
            $ok = InventarioModelo::agregarProducto(
                $conn,
                $data['nombreProducto'],
                $data['categoria'],
                $data['cantidad'],
                $data['precio']
            );
            echo json_encode(["success"=>$ok]);
        } else {
            echo json_encode(["error"=>"Datos incompletos"]);
        }
        break;

    case 'actualizar':
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data['id'])){
            $ok = InventarioModelo::actualizarProducto(
                $conn,
                $data['id'],
                $data['nombreProducto'],
                $data['categoria'],
                $data['cantidad'],
                $data['precio']
            );
            echo json_encode(["success"=>$ok]);
        } else {
            echo json_encode(["error"=>"ID no recibido"]);
        }
        break;

    case 'eliminar':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
        if($id){
            $ok = InventarioModelo::eliminarProducto($conn, $id);
            echo json_encode(["success"=>$ok, "mensaje"=>"Producto eliminado."]);
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
