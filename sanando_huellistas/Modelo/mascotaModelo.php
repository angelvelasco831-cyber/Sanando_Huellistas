<?php
include_once(__DIR__ . '/conexion.php');

class MascotaModelo {

    // Listar todas las mascotas (YA CON NOMBRE DEL CLIENTE)
    public static function obtenerMascotas($conn) {
        $sql = "SELECT m.*, c.nombre AS cliente_nombre
                FROM mascotas m
                LEFT JOIN clientes c ON c.id_cliente = m.cliente";

        $result = $conn->query($sql);
        $mascotas = [];
        while ($row = $result->fetch_assoc()) {
            $mascotas[] = $row;
        }
        return $mascotas;
    }

    // Agregar mascota
    public static function agregarMascota($conn, $nombre, $tipo, $raza, $fecha_nacimiento, $cliente) {
        $stmt = $conn->prepare(
            "INSERT INTO mascotas (nombre, tipo, raza, fecha_nacimiento, cliente) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $nombre, $tipo, $raza, $fecha_nacimiento, $cliente);
        return $stmt->execute();
    }

    // Actualizar mascota
    public static function actualizarMascota($conn, $id, $nombre, $tipo, $raza, $fecha_nacimiento, $cliente) {
        $stmt = $conn->prepare(
            "UPDATE mascotas 
             SET nombre=?, tipo=?, raza=?, fecha_nacimiento=?, cliente=? 
             WHERE id_mascota=?"
        );
        $stmt->bind_param("sssssi", $nombre, $tipo, $raza, $fecha_nacimiento, $cliente, $id);
        return $stmt->execute();
    }

    // Eliminar mascota
    public static function eliminarMascota($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM mascotas WHERE id_mascota=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
