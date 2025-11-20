<?php
include_once('conexion.php');

class CitaModelo {

    public static function obtenerCitas($conn) {
        $sql = "SELECT id_cita, cliente, mascota, servicio, fecha, hora, observaciones FROM citas";
        $result = $conn->query($sql);
        $citas = [];
        while($row = $result->fetch_assoc()){
            $citas[] = $row;
        }
        return $citas;
    }

    public static function agregarCita($conn, $cliente, $mascota, $servicio, $fecha, $hora, $observaciones) {
        $stmt = $conn->prepare("INSERT INTO citas (cliente, mascota, servicio, fecha, hora, observaciones) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $cliente, $mascota, $servicio, $fecha, $hora, $observaciones);
        return $stmt->execute();
    }

    public static function actualizarCita($conn, $id, $cliente, $mascota, $servicio, $fecha, $hora, $observaciones) {
        $stmt = $conn->prepare("UPDATE citas SET cliente=?, mascota=?, servicio=?, fecha=?, hora=?, observaciones=? WHERE id_cita=?");
        $stmt->bind_param("ssssssi", $cliente, $mascota, $servicio, $fecha, $hora, $observaciones, $id);
        return $stmt->execute();
    }

    public static function eliminarCita($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM citas WHERE id_cita=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
