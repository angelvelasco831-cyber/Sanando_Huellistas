<?php
class UsuarioM {

    public static function listar($conn){
        $res = $conn->query("SELECT * FROM usuarios");
        $datos = [];
        while($row = $res->fetch_assoc()){
            $datos[] = $row;
        }
        return $datos;
    }

    public static function agregar($conn, $d){
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, rol, nombre) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $d['usuario'], $d['password'], $d['rol'], $d['nombre']);
        return $stmt->execute();
    }

    public static function actualizar($conn, $d){
        $stmt = $conn->prepare("UPDATE usuarios SET usuario=?, password=?, rol=?, nombre=? WHERE id=?");
        $stmt->bind_param("ssssi", $d['usuario'], $d['password'], $d['rol'], $d['nombre'], $d['id']);
        return $stmt->execute();
    }

    public static function eliminar($conn, $id){
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
