<?php
include_once(__DIR__ . '/../Modelo/conexion.php');
include_once(__DIR__ . '/../Modelo/mascotaModelo.php');

$accion = $_GET['accion'] ?? '';

switch($accion){

    /* ---------------------------------------------------
       LISTAR
    --------------------------------------------------- */
    case 'listar':
        $mascotas = MascotaModelo::obtenerMascotas($conn);
        echo json_encode($mascotas);
        break;

    /* ---------------------------------------------------
       AGREGAR
    --------------------------------------------------- */
    case 'agregar':
        $data = json_decode(file_get_contents("php://input"), true);

        if(!empty($data['nombre']) && 
           !empty($data['tipo']) && 
           !empty($data['raza']) && 
           !empty($data['nacimiento']) &&
           !empty($data['cliente'])){

            $ok = MascotaModelo::agregarMascota(
                $conn, 
                $data['nombre'], 
                $data['tipo'], 
                $data['raza'], 
                $data['nacimiento'], 
                $data['cliente']
            );

            echo json_encode([
                "success" => $ok, 
                "mensaje" => $ok ? "Mascota agregada correctamente" : "Error al agregar"
            ]);

        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    /* ---------------------------------------------------
       ACTUALIZAR
    --------------------------------------------------- */
    case 'actualizar':
        $data = json_decode(file_get_contents("php://input"), true);

        if(!empty($data['id_mascota'])){

            $ok = MascotaModelo::actualizarMascota(
                $conn, 
                $data['id_mascota'], 
                $data['nombre'], 
                $data['tipo'], 
                $data['raza'], 
                $data['nacimiento'], 
                $data['cliente']
            );

            echo json_encode([
                "success" => $ok, 
                "mensaje" => $ok ? "Mascota actualizada correctamente" : "Error al actualizar"
            ]);

        } else {
            echo json_encode(["error" => "Falta el ID de la mascota"]);
        }
        break;

    /* ---------------------------------------------------
       ELIMINAR
    --------------------------------------------------- */
    case 'eliminar':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id_mascota'] ?? null;

        if($id){
            $ok = MascotaModelo::eliminarMascota($conn, $id);
            echo json_encode([
                "success" => $ok, 
                "mensaje" => $ok ? "Mascota eliminada correctamente" : "No se pudo eliminar"
            ]);
        } else {
            echo json_encode(["error" => "ID no recibido"]);
        }
        break;

    /* ---------------------------------------------------
       DEFAULT
    --------------------------------------------------- */
    default:
        echo json_encode(["error" => "Acción no reconocida"]);
        break;
}

$conn->close();
?>
