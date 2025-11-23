<?php
session_start();

// Si no ha iniciado sesión
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["rol"])) {
    header("Location: ../Vista/Login.html");
    exit;
}

require_once "../Controlador/seguridad.php";
permitirSolo(["admin" ,"recepcionista" , "veterinario"]); // o los roles que quieras permitir
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sanando Huellitas</title>
  <link rel="stylesheet" href="estilos.css">
  <link rel="stylesheet" href="estilosR.css">
  <script src="https://kit.fontawesome.com/d748c5e5cf.js" crossorigin="anonymous"></script>

  <!-- ESTILO ELEGANTE DEL HEADER + BOTÓN DERECHA -->
  <style>
    header {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      background: white;
      padding: 10px 20px;
      color: black;
      box-shadow: 0 3px 8px rgba(0,0,0,0.4);
    }

    #toggleMenu {
      background: transparent;
      border: none;
      color: #F28482;
      font-size: 24px;
      cursor: pointer;
      margin-right: 15px;
    }

    .usuario-info {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 15px;
      font-size: 16px;
    }

    .usuario-info span {
      font-weight: bold;
    }

    .logout {
      background: #ff4d4d;
      padding: 8px 15px;
      border-radius: 8px;
      text-decoration: none;
      color: white;
      font-weight: bold;
      transition: 0.3s ease;
    }

    .logout:hover {
      background: #d10000;
    }

    .logo {
      width: 55px;
      margin-right: 10px;
    }
  </style>

</head>

<body>

  <!-- ===== HEADER ===== -->
  <header>
    <button id="toggleMenu">☰</button>
    <img src="imagenes/mm.png" alt="Logo Sanando Huellita" class="logo">
    <h1>Sanando Huellitas</h1>

    <div class="usuario-info">
      <span>Rol: <?php echo $_SESSION["rol"]; ?></span>
      <a href="../Controlador/logout.php" class="logout">Cerrar sesión</a>
    </div>
  </header>

  <!-- ===== MENU LATERAL ===== -->
  <aside id="menuLateral" class="oculto">
    <ul>
      <li><a href="Usuarios.php">USUARIOS</a></li>
      <li><a href="Empleados.php">EMPLEADOS</a></li>
      <li><a href="Servicio.php">SERVICIOS</a></li>
      <li><a href="Clientes.php">CLIENTES</a></li>
      <li><a href="Mascotas.php">MASCOTAS</a></li>
      <li><a href="Citas.php">CITAS</a></li>
      <li><a href="Inventari.php">INVENTARIO</a></li>
    </ul>
  </aside>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <main>
    <section class="banner">
      <img src="imagenes/Banner1.jpeg" alt="Banner principal" class="imagen-banner">
      <div class="texto-banner">
        <h2>Sanando Huellitas</h2>
        <p>Sanando huellitas, paz entre vida y dolor</p>
        <button class="boton">Saber más</button>
      </div>
    </section>

    <section class="servicios">
      <div class="servicio">
        <h3></h3>
      </div>
      <div class="servicio">
        <img src="imagenes/cuidado.png" alt="Cuidado">
        <h3>Cuidado</h3>
      </div>
      <div class="servicio">
        <img src="imagenes/veterinario.png" alt="Veterinario">
        <h3>Veterinario</h3>
      </div>
    </section>

    <section class="historia">
      <h2>Nuestra Historia</h2>
      <p>
        “Sanando Huellitas” nació del amor hacia los animales y el deseo de brindarles una vida mejor. 
        Desde nuestros inicios, hemos trabajado para rescatar, cuidar y dar una segunda oportunidad a 
        cientos de mascotas. Creemos que cada huellita cuenta, y nuestro compromiso es sanar tanto sus 
        cuerpos como sus corazones.
      </p>
    </section>
  </main>

  <!-- ===== SCRIPT MENU ===== -->
  <script>
    const boton = document.getElementById('toggleMenu');
    const menu = document.getElementById('menuLateral');
    const main = document.querySelector('main');

    boton.addEventListener('click', () => {
      menu.classList.toggle('oculto');
      main.classList.toggle('reducido');
    });
  </script>

</body>
</html>
