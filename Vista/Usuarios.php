<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>
<link rel="stylesheet" href="estilosEM.css">
<link rel="stylesheet" href="estilosEMR.css">
<script src="https://kit.fontawesome.com/d748c5e5cf.js" crossorigin="anonymous"></script>

<style>
#buscarBtn{
  padding: 8px 12px;
  border-radius: 6px;
  background:#F28482;
  color:white;
  border:1px solid #F28482;
  cursor:pointer;
}
#buscarBtn:hover{ background:#d96a6a; }
.busqueda-container{
  display:flex;
  gap:4px;
  margin-bottom:12px;
  max-width:320px;
}
</style>
</head>

<body>

<header>
  <button id="toggleMenu">☰</button>
  <img src="imagenes/mm.png" class="logo">
  <h1>Usuarios</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.php">INICIO</a></li>
    <li><a href="Usuarios.php" class="activo">USUARIOS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <h2>Gestión de Usuarios</h2>

    <p>Administra los usuarios del sistema. Agrega, edita o elimina registros.</p>

    <div class="busqueda-container">
      <input id="buscarNombre" placeholder="Buscar usuario..." style="padding:8px;border-radius:6px;border:1px solid #F28482;width:100%;">
      <button id="buscarBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div class="contenido">
      <table class="table-empleados" id="tablaUsuarios">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Nombre</th>
            <th>Creado En</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="botones">
      <button class="btn" onclick="window.location.href='agregarUsuario.php'">Agregar Usuario</button>
    </div>
  </div>
</main>

<script>
const menu = document.getElementById("menuLateral");
document.getElementById("toggleMenu").addEventListener("click", ()=>{
  menu.classList.toggle("oculto");
});

let listaUsuarios = [];

async function cargarUsuarios(){
  const res = await fetch("../Controlador/usuarioControlador.php?accion=listar");
  const datos = await res.json();
  listaUsuarios = datos;
  mostrarUsuarios(datos);
}

function mostrarUsuarios(datos){
  const tabla = document.querySelector("#tablaUsuarios tbody");
  tabla.innerHTML = "";

  datos.forEach(u=>{
    tabla.innerHTML += `
    <tr>
      <td>${u.usuario}</td>
      <td>${u.rol}</td>
      <td>${u.nombre}</td>
      <td>${u.creado_en}</td>
      <td>
        <button class="accion" onclick="editar(${u.id})">Editar</button>
        <button class="accion" onclick="eliminar(${u.id})">Eliminar</button>
      </td>
    </tr>`;
  });
}

document.getElementById("buscarNombre").addEventListener("input",()=>{
  const valor = buscarNombre.value.toLowerCase();
  mostrarUsuarios(listaUsuarios.filter(u=>u.usuario.toLowerCase().includes(valor)));
});

document.getElementById("buscarBtn").addEventListener("click",()=>{
  const valor = buscarNombre.value.toLowerCase();
  mostrarUsuarios(listaUsuarios.filter(u=>u.usuario.toLowerCase().includes(valor)));
});

function editar(id){
  const user = listaUsuarios.find(u=>u.id==id);
  if(user){
    localStorage.setItem("usuarioEditar", JSON.stringify(user));
    window.location.href="agregarUsuario.php";
  }
}

async function eliminar(id){
  if(!confirm("¿Eliminar usuario?")) return;

  const res = await fetch("../Controlador/usuarioControlador.php?accion=eliminar", {
    method:"POST",
    headers:{ "Content-Type":"application/json" },
    body:JSON.stringify({id})
  });

  const data = await res.json();
  alert(data.mensaje);
  cargarUsuarios();
}

cargarUsuarios();
</script>

</body>
</html>
