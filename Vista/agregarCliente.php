<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin", "recepcionista"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agregar / Editar Cliente</title>
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
  <h1>Agregar / Editar Cliente</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.html">INICIO</a></li>
    <li><a href="Clientes.html" class="activo">CLIENTES</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <form id="formCliente">
      <div class="cuestionario">
        <label for="nombre">Nombre del Cliente</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" required>

        <label for="correo">Correo Electrónico</label>
        <input type="email" id="correo" name="correo" required>
      </div>

      <div class="botones-form">
        <button type="submit" class="btn">Guardar</button>
        <button type="button" class="btn" onclick="window.location.href='Clientes.php'">Cancelar</button>
      </div>
    </form>
  </div>
</main>

<script>
/* Menú lateral - mismo comportamiento que en las otras vistas */
const menu = document.getElementById('menuLateral');
const boton = document.getElementById('toggleMenu');
const main = document.querySelector('main');

boton.addEventListener('click', () => {
  menu.classList.toggle('oculto');
  main.classList.toggle('reducido');
});

/* Lógica para agregar/editar usando el controlador (MVC) */
const idEditar = localStorage.getItem("editarClienteId");

if (idEditar) {
  // Obtener los datos actuales del cliente para precargar el formulario
  fetch(`../Controlador/clienteControlador.php?accion=listar`)
    .then(res => res.json())
    .then(clientes => {
      const cliente = clientes.find(c => c.id_cliente == idEditar);

      if (cliente) {
        document.getElementById("nombre").value = cliente.nombre;
        document.getElementById("telefono").value = cliente.telefono;
        document.getElementById("correo").value = cliente.correo;
      }
    })
    .catch(err => console.error("Error al cargar cliente:", err));
}

document.getElementById("formCliente").addEventListener("submit", async (e) => {
  e.preventDefault();

  const cliente = {
    id: idEditar ? parseInt(idEditar) : null,
    nombre: document.getElementById("nombre").value.trim(),
    telefono: document.getElementById("telefono").value.trim(),
    correo: document.getElementById("correo").value.trim()
  };

  const accion = idEditar ? 'actualizar' : 'agregar';

  try {
    await fetch(`../Controlador/clienteControlador.php?accion=${accion}`, {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(cliente)
    });

    localStorage.removeItem("editarClienteId");
    // volver al listado
    window.location.href = "Clientes.php";
  } catch (error) {
    console.error("Error al guardar cliente:", error);
    alert("Ocurrió un error al guardar. Revisa la consola.");
  }
});
</script>

</body>
</html>
