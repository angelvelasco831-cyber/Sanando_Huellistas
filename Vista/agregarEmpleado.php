<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agregar / Editar Empleado</title>
<link rel="stylesheet" href="estilosEM.css">
<link rel="stylesheet" href="estilosEMR.css">
<style>
.cuestionario {
  display: flex;
  flex-direction: column;
  gap: 12px;
  max-width: 480px;
  margin: 0 auto;
}

.cuestionario label {
  font-weight: 600;
  color: #F28482;
  text-align: left;
}

.cuestionario input,
.cuestionario select,
.cuestionario textarea {
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #F28482;
  font-size: 1rem;
}

.botones-form {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-top: 12px;
}
</style>
</head>
<body>

<header>
  <button id="toggleMenu">☰</button>
  <img src="imagenes/mm.png" alt="Logo" class="logo">
  <h1>Agregar / Editar Empleado</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.html">INICIO</a></li>
    <li><a href="Empleados.html" class="activo">EMPLEADOS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <form id="formEmpleado" method="POST">
      <input type="hidden" id="id_empleado" name="id_empleado">
      <div class="cuestionario">
        <label for="nombre">Nombre del Empleado</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellidoPaterno">Apellido Paterno</label>
        <input type="text" id="apellidoPaterno" name="apellidoPaterno" required>

        <label for="apellidoMaterno">Apellido Materno</label>
        <input type="text" id="apellidoMaterno" name="apellidoMaterno" required>

        <label for="puesto">Puesto</label>
        <select id="puesto" name="puesto" required>
          <option value="">Selecciona un puesto</option>
          <option value="Veterinario">Veterinario</option>
          <option value="Recepcionista">Recepcionista</option>
          <option value="Auxiliar">Auxiliar</option>
        </select>

        <div id="cedulaDiv" style="display:none;">
          <label for="cedula">Cédula Profesional</label>
          <input type="text" id="cedula" name="cedula">
        </div>

        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" required>

        <label for="correo">Correo Electrónico</label>
        <input type="email" id="correo" name="correo" required>
      </div>

      <div class="botones-form">
        <button type="submit" class="btn">Guardar</button>
        <button type="button" class="btn" onclick="window.location.href='Empleados.php'">Cancelar</button>
      </div>
    </form>
  </div>
</main>

<script>
const menu = document.getElementById('menuLateral');
const boton = document.getElementById('toggleMenu');
const main = document.querySelector('main');
const puesto = document.getElementById("puesto");
const cedulaDiv = document.getElementById("cedulaDiv");

// Abrir/Cerrar menú lateral
boton.addEventListener('click', () => {
  menu.classList.toggle('oculto');
  main.classList.toggle('reducido');
});

// Mostrar cédula solo si es Veterinario
puesto.addEventListener("change", function() {
  if(this.value === "Veterinario"){
    cedulaDiv.style.display = "block";
    document.getElementById("cedula").required = true;
  } else {
    cedulaDiv.style.display = "none";
    document.getElementById("cedula").required = false;
  }
});

// Cargar datos si se va a editar
const empleadoEditar = localStorage.getItem("empleadoEditar");

if(empleadoEditar){
  const e = JSON.parse(empleadoEditar);
  document.getElementById("id_empleado").value = e.id_empleado;
  document.getElementById("nombre").value = e.nombre;
  document.getElementById("apellidoPaterno").value = e.apellido_paterno;
  document.getElementById("apellidoMaterno").value = e.apellido_materno;
  document.getElementById("puesto").value = e.puesto;
  document.getElementById("telefono").value = e.telefono;
  document.getElementById("correo").value = e.correo;
  if(e.puesto === "Veterinario"){
    cedulaDiv.style.display = "block";
    document.getElementById("cedula").value = e.cedula;
  }
}

// Enviar datos a PHP
document.getElementById("formEmpleado").addEventListener("submit", async function(e){
  e.preventDefault();

  const formData = new FormData(this);
  const data = Object.fromEntries(formData.entries());
  const accion = data.id_empleado ? "actualizar" : "agregar";

  const res = await fetch("../Controlador/empleadoControlador.php?accion=" + accion, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  const resultado = await res.json();
  alert(resultado.mensaje || "Operación realizada");

  if(resultado.success){
    localStorage.removeItem("empleadoEditar");
    window.location.href = "Empleados.php";
  }
});
</script>

</body>
</html>
