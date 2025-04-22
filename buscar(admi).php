<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ECOMOVI - BUSCAR USUARIO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
    body {
    height: 100vh;
    margin: 0;
    justify-content: center;
    align-items: center;
    background: url(images/fonfi.jpeg) no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }


        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 3%; /* Reducido el padding */
            font-weight: bold;
            background: rgba(0, 0, 0, 0);
            height: 100px; /* Altura fija más pequeña */
        }

        h1 {
            color: white;
            font-size: 24px; /* Reducido el tamaño de fuente */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            text-align: center;
            flex: 1; /* Permite que ocupe el espacio disponible */
        }

        ul li {
            display: inline-block;
            padding: 4px 20px; /* Reducido el padding */
        }

        /* Ajuste para el logo */
        nav img {
            width: 200px; /* Reducido el tamaño del logo */
            height: auto;
        }
        

        h1 {
            color: white;
            font-size: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-left: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            display: inline-block;
            padding: 8px 28px;
        }

        a {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
        #search-input {
            padding: 12px;
            width: 110%;
            max-width: 580px;
            font-size: 1.1rem;
            border: 2px solid #125c07bc;
            border-radius: 10px;
            background-color: #ffffff;
            color: #333;
            transition: border-color 0.3s ease-in-out;
            display: block;
            margin: 30px auto; /* Aumentado el margen superior e inferior */
            border: 2px solid #00FF00;
            box-shadow: 0 0 10px #00FF00;
        }

        .vehicle {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            border: 2px solid #ccc;
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.9);
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .vehicle:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
        }

        .vehicle img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .vehicle-info {
            padding: 15px;
            text-align: left;
        }

        .vehicle-info p {
            margin: 5px 0;
            color: #333;
        }

        #error-message {
            color: red;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 25%;
            top: 43%;
            width: 90%;
            height: 90%;
            background-color: rgba(255, 255, 255, 0); /* Fondo blanco casi opaco */
            justify-content: center;
            align-items: center;
        
            border-radius: 8px; /* Opcional: esquinas redondeadas para mejor estética */
        }


        .modal-content {
                background: white;
                padding: 20px;
                border-radius: 10px;
                max-width: 60%;
                max-height: 70vh; /* Limitar la altura del modal */
                overflow-y: auto; /* Hacer scroll dentro del modal si es necesario */
                border: 5px solid #00FF00; /* Borde verde de 2px */
                box-shadow: 0 0 10px #00FF00; /* Sombra verde suave */
        }

        .modal-content h2 {
            color: #125c07;
            font-size: 22px;
        }

        .modal-content ul {
            list-style: none;
            padding: 0;
        }

        .modal-content li {
            font-size: 16px;
            color: #333;
            margin: 8px 0;
        }

        .close {
            color: red;
            font-size: 24px;
            cursor: pointer;
            float: right;
            font-weight: bold;
        }

        .close:hover {
            color: darkred;
        }

        .vehiculo-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 10px;
            max-height: 400px; /* Evita que los vehículos ocupen toda la pantalla */
            overflow-y: auto;
            left: 90px;
            top: 1000px;

        }

        .vehiculo-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 280px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
            border: 2px solid #00FF00; /* Borde de 2px de color verde */
            box-shadow: 0 0 10px #00FF00; /* Sombra verde alrededor del borde */
            padding-bottom: 50px;
            position: relative;
            
        }

        .vehiculo-card:hover {
            transform: scale(1.05);
        }

        .vehiculo-card h3 {
            color: #125c07;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .vehiculo-card p {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
        }

        /* Estilos para las imágenes dentro de la tarjeta */
        .vehiculo-card img {
            max-width: 400%;
            height: auto;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 10px;
        }
        .documentos-container {

                width: 100%;
                display: flex;
                justify-content: center; /* Centra horizontalmente */
                align-items: flex-start; /* Alinea arriba */
                gap: 40px; /* Espacio entre las imágenes */
                flex-wrap: wrap;
                padding: 20px 0;
                margin-top: -20px;
            }


            .documento-item {
                text-align: center;
                background: none;
                border: none;
                box-shadow: none;
                padding: 0;
                margin: 20px auto;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            

        /* Botón */
        .btn-documentos {
            background-color: #125c07bc;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .documento-item img {
            width: 100%;
            max-width: 400px;     /* Ajusta según lo grande que la quieras */
            height: auto;
            object-fit: contain;
            background: none;
            border: none;
            border-radius: 0;
            box-shadow: none;
            pointer-events: none;
            -webkit-user-drag: none;

        }
        /* Línea debajo del título */
        .documento-item h3::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background-color: #00FF00;
            margin: 10px auto 0;
            border-radius: 3px;
        }

        .documento-item.no-foto::after {
            content: "No hay foto disponible";
            display: block;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: red;
            margin-top: 10px;
        }


        .btn-documentos:hover {
            background-color:  #71e95e92;
        }
        .usuario-info {
            background: linear-gradient(135deg, #ffffff, #e6f3d9);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            max-width: 700px;
            width: 90%; /* Se adapta a pantallas pequeñas */
            margin: 50px auto; /* Centrado automático */
            border: 2px solid #00FF00; /* Borde verde */
            box-shadow: 0 0 28px #00FF00; /* Sombra verde */
            transition: transform 0.3s ease-in-out;
            animation: fade-in 1s ease-in-out;
        }
        

        .usuario-info:hover {
            transform: scale(1.02);
        }

        .usuario-info h2 {
            color: #125c07;
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .usuario-info p {
            font-size: 18px;
            color: #333;
            margin: 8px 0;
            font-weight: 500;
        }


        .btn-ver-vehiculos {
            background-color: #125c07;
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-ver-vehiculos:hover {
            background-color: #0a4705;
            transform: scale(1.05);
        }

        /* Ajuste para pantallas más pequeñas */
        @media (max-width: 600px) {
            .usuario-info {
                max-width: 90%;
                padding: 20px;
            }
        }
        .usuario-no-registrado {
            text-align: center; /* Centra el texto */
            font-size: 20px; /* Aumenta el tamaño */
            color: red; /* Texto en rojo */
            font-weight: bold; /* Negrita */
            padding: 20px; /* Espaciado */
            background-color: #ffe6e600; /* Fondo suave rojo claro */
            border-radius: 10px; /* Bordes redondeados */
            margin: 20px auto; /* Centrado en la página */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0); /* Sombra suave */
        }


        .notificacion-verde {
        
                text-align: center;
                font-size: 16px;
                color: #168216;
                font-weight: bold;
                background-color: #d4edda;
                padding: 8px;
                border-radius: 8px;
                width: 70%; /* Para que se ajuste dentro de la carta */
                margin: 9px auto 0; /* Espacio en la parte superior y centrado */
                box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
                border: 2px solid #28a745;
                position: relative;
                bottom: 0; /* Asegura que esté al fondo dentro de la carta */
            
        }
        </style>
   <script>
        function abrirModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function cerrarModal(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
</head>
<link rel="icon" href="logo blanco.png" type="icon">

<body>
    <header>
        <nav>
            <img src="logo blanco.png" alt="Título" width="300px">
            <h1>BUSQUEDA DE USUARIO</h1>
            <ul>
                <li><a href="paginaadministrador.html">INICIO</a></li>
            </ul>
        </nav>
    </header>

    <div>
        <form method="POST" action="" id="search-form">
            <input type="text" name="identificacion" id="search-input" 
                   placeholder="Escribe el número de Identificación..." required
                   value="<?php echo isset($_POST['identificacion']) ? $_POST['identificacion'] : ''; ?>">
        </form>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["identificacion"])) {
        $servidor = "localhost";
        $usuario = "root";
        $contrasena = "";
        $baseDatos = "ecomovi";

        $conn = new mysqli($servidor, $usuario, $contrasena, $baseDatos);

        if ($conn->connect_error) {
            die("<p>Error de conexión: " . $conn->connect_error . "</p>");
        }

        $identificacion = $conn->real_escape_string($_POST["identificacion"]);

        $sql = "SELECT nom_usu, apell_usu, num_doc_usu FROM usuarios WHERE num_doc_usu = '$identificacion'";
        $resultado = $conn->query(query: $sql);

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            echo "<div class='usuario-info'>";
            echo "<h2>Nombre: " . $usuario['nom_usu'] . " " . $usuario['apell_usu'] . "</h2>";
            echo "<p>CC: " . $usuario['num_doc_usu'] . "</p>";
            echo "<button class='btn-ver-vehiculos' onclick=\"abrirModal('vehiculoModal')\">Ver Vehículos</button>";
            echo "</div>";

            $sqlVehiculos = "SELECT plac_veh, mar_veh, model_veh, foto_soat, tecno_m FROM vehiculos";
$resultVehiculos = $conn->query($sqlVehiculos);

        } else {
            echo "<p class='usuario-no-registrado'>⚠ EL USUARIO NO ESTÁ REGISTRADO</p>";
        }
    }
    ?>

    <div id="vehiculoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('vehiculoModal')">&times;</span>
            <h2>Vehículos Registrados</h2>
            <?php
            if (isset($resultVehiculos) && $resultVehiculos->num_rows > 0) {
                $cantidadVehiculos = $resultVehiculos->num_rows;
                echo "<div class='vehiculo-container'>";
                
                while ($vehiculo = $resultVehiculos->fetch_assoc()) {
                    echo "<div class='vehiculo-card'>";
                    echo "<h3>Placa: " . $vehiculo['plac_veh'] . "</h3>";
                    echo "<p><strong>Marca:</strong> " . $vehiculo['mar_veh'] . "</p>";
                    echo "<p><strong>Modelo:</strong> " . $vehiculo['model_veh'] . "</p>";
                    
                    echo "<button class='btn-documentos' onclick=\"abrirModal('docModal_" . $vehiculo['plac_veh'] . "')\">Ver Documentos</button>";
                    echo "</div>";
                }
                echo "<p class='notificacion-verde'><strong>$cantidadVehiculos</strong> vehículos están registrados</p>";
                echo "</div>";
            } else {
                echo "<p>No hay vehículos registrados.</p>";
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($resultVehiculos) && $resultVehiculos->num_rows > 0) {
        $resultVehiculos->data_seek(0);
        while ($vehiculo = $resultVehiculos->fetch_assoc()) {
            echo "<div id='docModal_" . $vehiculo['plac_veh'] . "' class='modal' style='display: none;'>";
            echo "<div class='modal-content'>";
            echo "<span class='close' onclick=\"cerrarModal('docModal_" . $vehiculo['plac_veh'] . "')\">&times;</span>";
            echo "<h2>Documentos de " . $vehiculo['plac_veh'] . "</h2>";

            echo "<div class='documentos-container'>";

            // Mostrar la imagen del SOAT si está disponible
            echo "<div class='documento-item'><p>SOAT:</p>";
            if (isset($vehiculo['foto_soat']) && !empty($vehiculo['foto_soat'])) {
                echo "<img src='" . $vehiculo['foto_soat'] . "' alt='SOAT' width='200px'>";
            } else {
                echo "<p>SOAT no disponible</p>";
            }
            echo "</div>";

            // Mostrar la imagen de la Tecnomecánica si está disponible
            echo "<div class='documento-item'><p>Tecnomecánica:</p>";
            if (isset($vehiculo['foto_tecno']) && !empty($vehiculo['foto_tecno'])) {
                echo "<img src='" . $vehiculo['foto_tecno'] . "' alt='Tecnomecánica' width='200px'>";
            } else {
                echo "<p>Tecnomecánica no disponible</p>";
            }
            echo "</div>";

            echo "</div>"; // Cierra el contenedor de documentos

            echo "</div></div>"; // Cierra el modal
        }
    }
?>
</body>
</html>