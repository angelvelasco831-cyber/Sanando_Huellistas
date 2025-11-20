<?php
require_once 'conexion.php';

class ServicioModelo {

    // Listar todos los servicios
    public static function obtenerServicios($conn) {
        $sql = "SELECT * FROM servicios";
        $result = $conn->query($sql);
        $servicios = [];
        if($result){
            while ($row = $result->fetch_assoc()) {
                $servicios[] = $row;
            }
        }
        return $servicios;
    }

    // Agregar un servicio
    public static function agregarServicio($conn, $nombre, $descripcion, $precio, $duracion) {
        $stmt = $conn->prepare(
            "INSERT INTO servicios (nombre, descripcion, precio, duracion) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $duracion);
        return $stmt->execute();
    }

    // Actualizar un servicio
    public static function actualizarServicio($conn, $id_servicio, $nombre, $descripcion, $precio, $duracion) {
        $stmt = $conn->prepare(
            "UPDATE servicios SET nombre=?, descripcion=?, precio=?, duracion=? WHERE id_servicio=?"
        );
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $duracion, $id_servicio);
        return $stmt->execute();
    }

    // Eliminar un servicio
    public static function eliminarServicio($conn, $id_servicio) {
        $stmt = $conn->prepare("DELETE FROM servicios WHERE id_servicio=?");
        $stmt->bind_param("i", $id_servicio);
        return $stmt->execute();
    }
}
?>
