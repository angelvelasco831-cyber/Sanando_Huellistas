<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin", "recepcionista" , "veterinario"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sanando Huellita - Mascotas</title>
<link rel="stylesheet" href="estilosEM.css">
<link rel="stylesheet" href="estilosEMR.css">
<script src="https://kit.fontawesome.com/d748c5e5cf.js" crossorigin="anonymous"></script>
<style>
/* Estilo pequeño para el botón de búsqueda */
#buscarBtn {
  padding: 8px 12px;
  border-radius: 6px;
  border: 1px solid #F28482;
  background-color: #F28482;
  color: white;
  cursor: pointer;
  margin-left: 4px;
}
#buscarBtn:hover {
  background-color: #e06666;
}
.busqueda-container {
  display: flex;
  gap: 4px;
  margin-bottom: 12px;
  max-width: 320px;
}
</style>
</head>
<body>

<header>
  <button id="toggleMenu" aria-label="Abrir menú">☰</button>
  <img src="imagenes/mm.png" alt="Logo Sanando Huellita" class="logo" />
  <h1>Sanando Huellita</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.html">INICIO</a></li>
    <li><a href="Mascotas.html" class="activo">MASCOTAS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <h2>Gestión de Mascota</h2>

    <div class="imagen-empleados">
      <img src="imagenes/Mascota.png" alt="Mascotas">
    </div>

    <p class="descripcion">
      Aquí puedes ver, editar o eliminar las mascotas. Para agregar una nueva, haz clic en el botón “Agregar Mascota”.
    </p>

    <!-- BUSCADOR POR NOMBRE CON LUPA -->
    <div class="busqueda-container">
      <input type="text" id="buscarNombre" placeholder="Buscar por nombre..." style="padding:8px;border-radius:6px;border:1px solid #F28482;width:100%;">
      <button id="buscarBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div class="contenido">
      <table class="table-empleados" id="tablaMascotas">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Raza</th>
            <th>Fecha de nacimiento</th>
            <th>Cliente</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="botones">
      <button class="btn" onclick="window.location.href='agregarMascota.php'">Agregar Mascota</button>
    </div>
  </div>
</main>

<script>
// Menú lateral
const menu = document.getElementById('menuLateral');
const boton = document.getElementById('toggleMenu');
const main = document.querySelector('main');

boton.addEventListener('click', () => {
  menu.classList.toggle('oculto');
  main.classList.toggle('reducido');
});

// Lista global de mascotas
let listaMascotas = [];

// Cargar mascotas desde PHP
async function cargarMascotas() {
  try {
    const res = await fetch("../Controlador/mascotaControlador.php?accion=listar");
    const mascotas = await res.json();
    listaMascotas = mascotas;
    mostrarMascotas(mascotas);
  } catch(err){
    console.error("Error al cargar mascotas:", err);
  }
}

// Mostrar mascotas en la tabla
function mostrarMascotas(datos) {
  const tabla = document.querySelector("#tablaMascotas tbody");
  tabla.innerHTML = "";

  datos.forEach(m => {
    tabla.innerHTML += `
      <tr>
        <td>${m.nombre}</td>
        <td>${m.tipo}</td>
        <td>${m.raza}</td>
        <td>${m.fecha_nacimiento || "—"}</td>
        <td>${m.cliente_nombre || "—"}</td>
        <td>
          <button onclick="editar(${m.id_mascota})" class="accion">Editar</button>
          <button onclick="eliminar(${m.id_mascota})" class="accion">Eliminar</button>
        </td>
      </tr>
    `;
  });
}

// Editar mascota
function editar(id){
  localStorage.setItem("mascotaEditarId", id);
  window.location.href = "agregarMascota.php";
}

// Eliminar mascota
async function eliminar(id){
  if(confirm("¿Seguro quieres eliminar esta mascota?")){
    const res = await fetch("../Controlador/mascotaControlador.php?accion=eliminar", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({id_mascota: id})
    });
    const result = await res.json();
    alert(result.mensaje || "Mascota eliminada.");
    cargarMascotas();
  }
}

// Filtrar mientras escribes
document.getElementById("buscarNombre").addEventListener("input", () => {
  const valor = document.getElementById("buscarNombre").value.toLowerCase();
  const filtrados = listaMascotas.filter(m => m.nombre.toLowerCase().includes(valor));
  mostrarMascotas(filtrados);
});

// Buscar con lupa
document.getElementById("buscarBtn").addEventListener("click", () => {
  const valor = document.getElementById("buscarNombre").value.toLowerCase();
  const filtrados = listaMascotas.filter(m => m.nombre.toLowerCase().includes(valor));
  mostrarMascotas(filtrados);
});

cargarMascotas();
</script>

</body>
</html>
