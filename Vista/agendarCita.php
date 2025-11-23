<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin", "recepcionista"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agendar Cita</title>
<link rel="stylesheet" href="estilosEM.css">
<link rel="stylesheet" href="estilosEMR.css">
<style>
.cuestionario { display: flex; flex-direction: column; gap: 12px; max-width: 480px; margin: 0 auto; }
.cuestionario label { font-weight: 600; color: #F28482; text-align: left; }
.cuestionario input, .cuestionario select, .cuestionario textarea { padding: 10px; border-radius: 8px; border: 1px solid #F28482; font-size: 1rem; }
.botones-form { display: flex; gap: 10px; justify-content: center; margin-top: 12px; }
</style>
</head>
<body>

<header>
  <button id="toggleMenu">☰</button>
  <img src="imagenes/mm.png" alt="Logo" class="logo">
  <h1>Agendar Cita</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.html">INICIO</a></li>
    <li><a href="Citas.html" class="activo">CITAS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <form id="formCita">
      <div class="cuestionario">
        <label for="cliente">Nombre del Cliente</label>
        <select id="cliente" name="cliente" required>
          <option value="">Selecciona un cliente</option>
        </select>

        <label for="mascota">Nombre de la Mascota</label>
        <select id="mascota" name="mascota" required>
          <option value="">Selecciona una mascota</option>
        </select>

        <label for="servicio">Servicio</label>
        <select id="servicio" name="servicio" required>
          <option value="">Selecciona un servicio</option>
        </select>

        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" name="fecha" required>
        <label for="hora">Hora</label>
        <input type="time" id="hora" name="hora" required>
        <label for="observaciones">Observaciones</label>
        <textarea id="observaciones" name="observaciones" rows="3" placeholder="Opcional"></textarea>
      </div>
      <div class="botones-form">
        <button type="submit" class="btn">Guardar Cita</button>
        <button type="button" class="btn" onclick="window.location.href='Citas.php'">Cancelar</button>
      </div>
    </form>
  </div>
</main>

<script>
const menu = document.getElementById('menuLateral');
const boton = document.getElementById('toggleMenu');
const main = document.querySelector('main');
boton.addEventListener('click', () => { menu.classList.toggle('oculto'); main.classList.toggle('reducido'); });

let clientes = [];
let mascotas = [];
const editarId = localStorage.getItem("editarCitaId");

// Cargar clientes y mascotas desde la base de datos
async function cargarDatos() {
  const clientesRes = await fetch("../Controlador/clienteControlador.php?accion=listar");
  clientes = await clientesRes.json();
  const mascotasRes = await fetch("../Controlador/mascotaControlador.php?accion=listar");
  mascotas = await mascotasRes.json();

  const clienteSelect = document.getElementById("cliente");
  clienteSelect.innerHTML = '<option value="">Selecciona un cliente</option>';
  clientes.forEach(c => { clienteSelect.innerHTML += `<option value="${c.id_cliente}">${c.nombre}</option>`; });

  const servicioSelect = document.getElementById("servicio");
  servicioSelect.innerHTML = '<option value="">Selecciona un servicio</option>';
  const serviciosRes = await fetch("../Controlador/servicioControlador.php?accion=listar");
  const servicios = await serviciosRes.json();
  servicios.forEach(s => { servicioSelect.innerHTML += `<option value="${s.nombre}">${s.nombre}</option>`; });

  if(editarId) cargarEdicion();
}

// Actualizar mascotas según cliente
function actualizarMascotas(clienteId) {
  const mascotaSelect = document.getElementById("mascota");
  mascotaSelect.innerHTML = '<option value="">Selecciona una mascota</option>';
  mascotas.filter(m => m.cliente == clienteId).forEach(m => {
    mascotaSelect.innerHTML += `<option value="${m.id_mascota}">${m.nombre}</option>`;
  });
}

// Si se selecciona un cliente, actualizar mascotas
document.getElementById("cliente").addEventListener("change", e => {
  actualizarMascotas(e.target.value);
});

// Si se selecciona una mascota, llenar cliente automáticamente
document.getElementById("mascota").addEventListener("change", e => {
  const mascotaSeleccionada = mascotas.find(m => m.id_mascota == e.target.value);
  if(mascotaSeleccionada){
    document.getElementById("cliente").value = mascotaSeleccionada.cliente;
  }
});

// Cargar datos si es edición
async function cargarEdicion() {
  const citasRes = await fetch("../Controlador/citaControlador.php?accion=listar");
  const citas = await citasRes.json();
  const c = citas.find(cita => cita.id_cita == editarId);
  if(c){
    const clienteObj = clientes.find(cl => cl.nombre == c.cliente);
    if(clienteObj) document.getElementById("cliente").value = clienteObj.id_cliente;

    actualizarMascotas(document.getElementById("cliente").value);
    const mascotaObj = mascotas.find(m => m.nombre == c.mascota && m.cliente == document.getElementById("cliente").value);
    if(mascotaObj) document.getElementById("mascota").value = mascotaObj.id_mascota;

    document.getElementById("servicio").value = c.servicio;
    document.getElementById("fecha").value = c.fecha;
    document.getElementById("hora").value = c.hora;
    document.getElementById("observaciones").value = c.observaciones;
  }
}

// Guardar cita
document.getElementById("formCita").addEventListener("submit", async e => {
  e.preventDefault();
  const cita = {
    cliente: document.getElementById("cliente").value,
    mascota: document.getElementById("mascota").value,
    servicio: document.getElementById("servicio").value,
    fecha: document.getElementById("fecha").value,
    hora: document.getElementById("hora").value,
    observaciones: document.getElementById("observaciones").value
  };
  let url = "../Controlador/citaControlador.php?accion=agregar";
  if(editarId){ cita.id = editarId; url = "../Controlador/citaControlador.php?accion=actualizar"; }

  const res = await fetch(url, { method: "POST", headers: {"Content-Type":"application/json"}, body: JSON.stringify(cita) });
  const result = await res.json();
  if(result.success){
    localStorage.removeItem("editarCitaId");
    window.location.href = "Citas.php";
  } else { alert(result.error || "Ocurrió un error."); }
});

cargarDatos();
</script>

</body>
</html>
