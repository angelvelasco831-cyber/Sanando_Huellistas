<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sanando Huellita - Servicios</title>
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
  <h1>Servicios</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.html">INICIO</a></li>
    <li><a href="Servicio.html" class="activo">SERVICIOS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <h2>Gestión de Servicios</h2>
    <div class="imagen-empleados">
      <img src="imagenes/Servicio.png" alt="Servicios">
    </div>
    <p class="descripcion">
      Aquí puedes ver, editar o eliminar los servicios ofrecidos. Para agregar uno nuevo, haz clic en “Agregar Servicio”.
    </p>

    <!-- BUSCADOR POR NOMBRE CON LUPA -->
    <div class="busqueda-container">
      <input type="text" id="buscarNombre" placeholder="Buscar por nombre..." style="padding:8px;border-radius:6px;border:1px solid #F28482;width:100%;">
      <button id="buscarBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div class="contenido">
      <table class="table-empleados" id="tablaServicios">
        <thead>
          <tr>
            <th>Nombre del Servicio</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Duración</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="botones">
      <button class="btn" onclick="window.location.href='agregarServicio.php'">Agregar Servicio</button>
    </div>
  </div>
</main>

<script>
const menu = document.getElementById('menuLateral');
const boton = document.getElementById('toggleMenu');
const main = document.querySelector('main');
let listaServicios = [];

boton.addEventListener('click', () => {
  menu.classList.toggle('oculto');
  main.classList.toggle('reducido');
});

// Cargar servicios desde tu controlador
async function cargarServicios() {
  try {
    const res = await fetch("../Controlador/servicioControlador.php?accion=listar");
    const servicios = await res.json();
    listaServicios = servicios; // guardar globalmente
    mostrarServicios(servicios);
  } catch(err) {
    console.error("Error al cargar servicios:", err);
  }
}

// Mostrar servicios en la tabla
function mostrarServicios(servicios) {
  const tabla = document.querySelector("#tablaServicios tbody");
  tabla.innerHTML = "";

  servicios.forEach(s => {
    tabla.innerHTML += `
      <tr>
        <td>${s.nombre}</td>
        <td>${s.descripcion}</td>
        <td>${s.precio}</td>
        <td>${s.duracion || "—"}</td>
        <td>
          <button onclick="editar(${s.id_servicio})" class="accion">Editar</button>
          <button onclick="eliminar(${s.id_servicio})" class="accion">Eliminar</button>
        </td>
      </tr>`;
  });
}

// Editar servicio
function editar(id) {
  localStorage.setItem("servicioEditarId", id);
  window.location.href = "agregarServicio.php";
}

// Eliminar servicio
async function eliminar(id) {
  if(confirm("¿Seguro quieres eliminar este servicio?")) {
    const res = await fetch("../Controlador/servicioControlador.php?accion=eliminar", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({id_servicio: id})
    });
    const result = await res.json();
    alert(result.mensaje || "Servicio eliminado.");
    cargarServicios();
  }
}

// 🔍 Filtrar mientras escribes
document.getElementById("buscarNombre").addEventListener("input", () => {
  const valor = document.getElementById("buscarNombre").value.toLowerCase();
  const filtrados = listaServicios.filter(s => s.nombre.toLowerCase().includes(valor));
  mostrarServicios(filtrados);
});

// 🔍 Buscar con lupa
document.getElementById("buscarBtn").addEventListener("click", () => {
  const valor = document.getElementById("buscarNombre").value.toLowerCase();
  const filtrados = listaServicios.filter(s => s.nombre.toLowerCase().includes(valor));
  mostrarServicios(filtrados);
});

cargarServicios();
</script>

</body>
</html>
