<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sanando Huellita - Empleados</title>
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
  <h1>Empleados</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.html">INICIO</a></li>
    <li><a href="Empleados.html" class="activo">EMPLEADOS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <h2>Gestión de Empleados</h2>
    <div class="imagen-empleados">
      <img src="imagenes/eeee.jpeg" alt="Empleados">
    </div>

    <p class="descripcion">
      Aquí puedes ver, editar o eliminar los empleados. Para agregar uno nuevo, haz clic en “Agregar Empleado”.
    </p>

    <!-- BUSCADOR POR NOMBRE CON LUPA -->
    <div class="busqueda-container">
      <input type="text" id="buscarNombre" placeholder="Buscar por nombre..." style="padding:8px;border-radius:6px;border:1px solid #F28482;width:100%;">
      <button id="buscarBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div class="contenido">
      <table class="table-empleados" id="tablaEmpleados">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Puesto</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Cédula</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="botones">
      <button class="btn" onclick="window.location.href='agregarEmpleado.php'">Agregar Empleado</button>
    </div>
  </div>
</main>

<script>
// --- Menú lateral ---
const menu = document.getElementById('menuLateral');
const boton = document.getElementById('toggleMenu');
const main = document.querySelector('main');

boton.addEventListener('click', () => {
  menu.classList.toggle('oculto');
  main.classList.toggle('reducido');
});

// --- Cargar empleados desde PHP ---
let listaEmpleados = [];

async function cargarEmpleados() {
  try {
    const res = await fetch("../Controlador/empleadoControlador.php?accion=listar");
    const datos = await res.json();
    listaEmpleados = datos; // Guardar globalmente
    mostrarEmpleados(datos);
  } catch (error) {
    console.error("Error al cargar empleados:", error);
  }
}

// --- Mostrar empleados en la tabla ---
function mostrarEmpleados(datos) {
  const tabla = document.querySelector("#tablaEmpleados tbody");
  tabla.innerHTML = "";

  datos.forEach(e => {
    tabla.innerHTML += `
      <tr>
        <td>${e.nombre}</td>
        <td>${e.apellido_paterno}</td>
        <td>${e.apellido_materno}</td>
        <td>${e.puesto}</td>
        <td>${e.telefono}</td>
        <td>${e.correo}</td>
        <td>${e.cedula ?? '-'}</td>
        <td>
          <button onclick="editar(${e.id_empleado})" class="accion">Editar</button>
          <button onclick="eliminar(${e.id_empleado})" class="accion">Eliminar</button>
        </td>
      </tr>`;
  });
}

// --- Filtrar mientras escribes ---
document.getElementById("buscarNombre").addEventListener("input", () => {
  const valor = document.getElementById("buscarNombre").value.toLowerCase();
  const filtrados = listaEmpleados.filter(emp => emp.nombre.toLowerCase().includes(valor));
  mostrarEmpleados(filtrados);
});

// --- También permite buscar con la lupa ---
document.getElementById("buscarBtn").addEventListener("click", () => {
  const valor = document.getElementById("buscarNombre").value.toLowerCase();
  const filtrados = listaEmpleados.filter(emp => emp.nombre.toLowerCase().includes(valor));
  mostrarEmpleados(filtrados);
});

// --- Editar empleado ---
function editar(id) {
  const empleado = listaEmpleados.find(e => e.id_empleado == id);
  if (empleado) {
    localStorage.setItem("empleadoEditar", JSON.stringify(empleado));
    window.location.href = "agregarEmpleado.php";
  } else {
    alert("No se encontró el empleado.");
  }
}

// --- Eliminar empleado ---
async function eliminar(id) {
  if (confirm("¿Seguro que deseas eliminar este empleado?")) {
    const res = await fetch("../Controlador/empleadoControlador.php?accion=eliminar", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_empleado: id })
    });

    const resultado = await res.json();
    alert(resultado.mensaje || "Empleado eliminado.");
    cargarEmpleados();
  }
}

cargarEmpleados();
</script>

</body>
</html>
