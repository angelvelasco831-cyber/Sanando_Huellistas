<?php
require_once '../Controlador/seguridad.php';
permitirSolo(["admin", "recepcionista"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Usuario</title>

<link rel="stylesheet" href="estilosEM.css">
<link rel="stylesheet" href="estilosEMR.css">

<style>
  /* ----------- MENÚ LATERAL FUNCIONANDO ----------- */
  #menuLateral{
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background: #F28482;
    padding-top: 80px;
    transition: left 0.3s ease;
    z-index: 999;
  }

  #menuLateral.oculto{
    left: -250px;
  }

  #menuLateral.mostrar{
    left: 0;
  }

  #toggleMenu{
    font-size: 22px;
    padding: 8px 12px;
    background: transparent;
    border: none;
    cursor: pointer;
    color: #F28482;
  }

  /* ----------- FORMULARIO MEJORADO ----------- */
  .area-empleado{
    max-width: 500px;
    margin: 30px auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.15);
  }

  #formUsuario label{
    display: block;
    font-weight: bold;
    margin-bottom: 6px;
    margin-top: 15px;
  }

  #formUsuario input,
  #formUsuario select{
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #bbb;
    margin-bottom: 10px;
  }

  .password-container{
    position: relative;
  }

  .ojito{
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #555;
  }

  .botones{
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
  }

  .botones .btn{
    width: 48%;
  }

</style>

</head>

<body>

<header>
  <button id="toggleMenu">☰</button>
  <img src="imagenes/mm.png" class="logo">
  <h1>Agregar / Editar Usuario</h1>
</header>

<!-- MENÚ LATERAL -->
<aside id="menuLateral" class="oculto">
  <ul>
    <li><a href="Menu.php">INICIO</a></li>
    <li><a href="Usuarios.php" class="activo">USUARIOS</a></li>
  </ul>
</aside>

<main>
  <div class="area-empleado">
    <h2>Datos del Usuario</h2>

    <form id="formUsuario">

      <input type="hidden" id="id" name="id">

      <label>Usuario</label>
      <input type="text" id="usuario" required>

      <label>Contraseña</label>
      <div class="password-container">
        <input type="password" id="password" required>
        <span class="ojito" id="togglePass">👁</span>
      </div>

      <label>Nombre Completo</label>
      <input type="text" id="nombre" required>

      <label>Rol</label>
      <select id="rol" required>
        <option value="">Selecciona...</option>
        <option value="admin">Administrador</option>
        <option value="recepcionista">Recepcionista</option>
        <option value="veterinario">Veterinario</option>
      </select>

      <div class="botones">
        <button class="btn" type="submit">Guardar</button>
        <button class="btn cancelar-btn" type="button">Cancelar</button>
      </div>

    </form>
  </div>
</main>

<script>
/* ----------- FUNCIONAMIENTO DEL MENÚ ----------- */
document.getElementById("toggleMenu").addEventListener("click", ()=>{
  const menu = document.getElementById("menuLateral");
  menu.classList.toggle("mostrar");
  menu.classList.toggle("oculto");
});

/* ----------- OJO EN CONTRASEÑA ----------- */
document.getElementById("togglePass").addEventListener("click", ()=>{
  const pass = document.getElementById("password");

  if(pass.type === "password"){
    pass.type = "text";
  }else{
    pass.type = "password";
  }
});

/* ----------- CARGAR DATOS SI ES EDICIÓN ----------- */
const edit = localStorage.getItem("usuarioEditar");

if(edit){
  const u = JSON.parse(edit);
  document.getElementById("id").value = u.id;
  document.getElementById("usuario").value = u.usuario;
  document.getElementById("password").value = u.password;
  document.getElementById("nombre").value = u.nombre;
  document.getElementById("rol").value = u.rol;
}

/* ----------- GUARDAR / ACTUALIZAR ----------- */
document.getElementById("formUsuario").addEventListener("submit", async(e)=>{
  e.preventDefault();

  const data = {
    id: id.value,
    usuario: usuario.value,
    password: password.value,
    nombre: nombre.value,
    rol: rol.value
  };

  const accion = data.id ? "actualizar" : "agregar";

  const res = await fetch("../Controlador/usuarioControlador.php?accion="+accion, {
    method:"POST",
    headers:{ "Content-Type":"application/json" },
    body:JSON.stringify(data)
  });

  const r = await res.json();
  alert(r.mensaje);

  if(r.success){
    localStorage.removeItem("usuarioEditar");
    window.location.href="Usuarios.php";
  }
});

/* ----------- CANCELAR ----------- */
document.querySelector(".cancelar-btn").addEventListener("click", ()=>{
  localStorage.removeItem("usuarioEditar");
  window.location.href = "Usuarios.php";
});
</script>

</body>
</html>
