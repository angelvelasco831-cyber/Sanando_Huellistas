<?php
class InventarioModelo {

    public static function obtenerProductos($conn){
        $sql = "SELECT * FROM inventario";
        $resultado = $conn->query($sql);
        $productos = [];
        if($resultado){
            while($fila = $resultado->fetch_assoc()){
                $productos[] = $fila;
            }
        }
        return $productos;
    }

    public static function agregarProducto($conn, $nombre, $categoria, $cantidad, $precio){
        $sql = "INSERT INTO inventario (nombreProducto, categoria, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssid", $nombre, $categoria, $cantidad, $precio);
        return $stmt->execute();
    }

    public static function actualizarProducto($conn, $id, $nombre, $categoria, $cantidad, $precio){
        $sql = "UPDATE inventario SET nombreProducto=?, categoria=?, cantidad=?, precio=? WHERE id_producto=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssidi", $nombre, $categoria, $cantidad, $precio, $id);
        return $stmt->execute();
    }

    public static function eliminarProducto($conn, $id){
        $sql = "DELETE FROM inventario WHERE id_producto=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
