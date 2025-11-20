<?php
include_once(__DIR__ . '/conexion.php');

class ClienteModelo {

  public static function obtenerClientes($conn) {
    $sql = "SELECT * FROM clientes";
    $result = $conn->query($sql);
    $clientes = [];
    while($row = $result->fetch_assoc()){
      $clientes[] = $row;
    }
    return $clientes;
  }

  public static function agregarCliente($conn, $nombre, $telefono, $correo) {
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, telefono, correo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $telefono, $correo);
    return $stmt->execute();
  }

  public static function actualizarCliente($conn, $id, $nombre, $telefono, $correo) {
  $stmt = $conn->prepare("UPDATE clientes SET nombre=?, telefono=?, correo=? WHERE id_cliente=?");
  $stmt->bind_param("sssi", $nombre, $telefono, $correo, $id);
  return $stmt->execute();
}

public static function eliminarCliente($conn, $id) {
  $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente=?");
  $stmt->bind_param("i", $id);
  return $stmt->execute();
}

}
?>
