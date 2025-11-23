<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin", "recepcionista"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agregar / Editar Mascota</title>
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
.cuestionario select {
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
  <h1>Agregar / Editar Mascota</h1>
</header>

<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.php">INICIO</a></li>
    <li><a href="Mascotas.php" class="activo">MASCOTAS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <form id="formMascota">
      <div class="cuestionario">

        <label for="nombre">Nombre de la Mascota</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="tipo">Tipo de Mascota</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecciona el tipo</option>
            <option value="Perro">Perro</option>
            <option value="Gato">Gato</option>
            <option value="Pájaro">Pájaro</option>
        </select>

        <label for="raza">Raza</label>
        <select id="raza" name="raza" required>
            <option value="">Selecciona una raza</option>
        </select>

        <label for="nacimiento">Fecha de nacimiento</label>
        <input type="date" id="nacimiento" name="nacimiento" required>

        <label for="cliente">Dueño / Cliente</label>
        <select id="cliente" name="cliente" required>
            <option value="">Selecciona un cliente</option>
        </select>

      </div>

      <div class="botones-form">
        <button type="submit" class="btn">Guardar</button>
        <button type="button" class="btn" onclick="window.location.href='Mascotas.php'">Cancelar</button>
      </div>
    </form>
  </div>
</main>

<script>
// Menú
const menu = document.getElementById('menuLateral');
const boton = document.getElementById('toggleMenu');
const main = document.querySelector('main');

boton.addEventListener('click', () => {
  menu.classList.toggle('oculto');
  main.classList.toggle('reducido');
});

// Cargar clientes de BD
fetch("../Controlador/clienteControlador.php?accion=listar")
  .then(res => res.json())
  .then(data => {
    const select = document.getElementById("cliente");
    data.forEach(c => {
      let opt = document.createElement("option");
      opt.value = c.id_cliente;
      opt.textContent = c.nombre;
      select.appendChild(opt);
    });
  });

// Razas dinámicas según tipo
const razas = {
  "Perro": ["Labrador", "Poodle", "Chihuahua", "Pastor Alemán"],
  "Gato": ["Siamés", "Persa", "Bengalí", "Esfinge"],
  "Pájaro": ["Canario", "Perico", "Guacamaya", "Cacatúa"]
};

document.getElementById("tipo").addEventListener("change", () => {
  const tipo = document.getElementById("tipo").value;
  const razaSelect = document.getElementById("raza");

  razaSelect.innerHTML = '<option value="">Selecciona una raza</option>';

  if (razas[tipo]) {
    razas[tipo].forEach(r => {
      let opt = document.createElement("option");
      opt.value = r;
      opt.textContent = r;
      razaSelect.appendChild(opt);
    });
  }
});


// ID si es edición
const mascotaId = localStorage.getItem("mascotaEditarId");

if(mascotaId){
  fetch(`../Controlador/mascotaControlador.php?accion=listar`)
    .then(res => res.json())
    .then(data => {
      const m = data.find(item => item.id_mascota == mascotaId);
      if(m){
        document.getElementById("nombre").value = m.nombre;
        document.getElementById("tipo").value = m.tipo;

        // cargar razas segun tipo
        document.getElementById("tipo").dispatchEvent(new Event("change"));

        document.getElementById("raza").value = m.raza;
        document.getElementById("nacimiento").value = m.nacimiento;
        document.getElementById("cliente").value = m.cliente;
      }
    });
}

// Guardar / actualizar
document.getElementById("formMascota").addEventListener("submit", async (e)=>{
  e.preventDefault();

  const payload = {
    nombre: nombre.value,
    tipo: tipo.value,
    raza: raza.value,
    nacimiento: nacimiento.value,
    cliente: cliente.value
  };

  let accion = "agregar";
  if(mascotaId){
    payload.id_mascota = mascotaId;
    accion = "actualizar";
  }

  const res = await fetch(`../Controlador/mascotaControlador.php?accion=${accion}`, {
    method:"POST",
    headers:{ "Content-Type":"application/json" },
    body: JSON.stringify(payload)
  });

  const result = await res.json();
  alert(result.mensaje || "Operación realizada.");

  localStorage.removeItem("mascotaEditarId");
  window.location.href = "Mascotas.php";
});
</script>

</body>
</html>
