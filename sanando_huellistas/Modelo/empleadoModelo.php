<?php
include_once(__DIR__ . '/conexion.php');

class EmpleadoModelo {

    // Obtener todos los empleados
    public static function obtenerEmpleados($conn) {
        $sql = "SELECT * FROM empleados";
        $result = $conn->query($sql);
        $empleados = [];
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }
        return $empleados;
    }

    // Agregar un nuevo empleado
    public static function agregarEmpleado($conn, $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $puesto, $cedula = null) {
        $stmt = $conn->prepare("INSERT INTO empleados (nombre, apellido_paterno, apellido_materno, telefono, correo, puesto, cedula) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $puesto, $cedula);
        return $stmt->execute();
    }

    // Actualizar un empleado existente
    public static function actualizarEmpleado($conn, $id_empleado, $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $puesto, $cedula = null) {
        $stmt = $conn->prepare("UPDATE empleados SET nombre=?, apellido_paterno=?, apellido_materno=?, telefono=?, correo=?, puesto=?, cedula=? WHERE id_empleado=?");
        $stmt->bind_param("sssssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $puesto, $cedula, $id_empleado);
        return $stmt->execute();
    }

    // Eliminar un empleado
    public static function eliminarEmpleado($conn, $id_empleado) {
    $stmt = $conn->prepare("DELETE FROM empleados WHERE id_empleado=?");
    $stmt->bind_param("i", $id_empleado);
    return $stmt->execute();
}

}
?>
