<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ECOMOVI - BUSCAR USUARIO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="estilo(buscar).css">
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
            <button type="submit" class="btn-consultar">Consultar</button>
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
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            echo "<div class='usuario-info'>";
            echo "<h2>Nombre: " . $usuario['nom_usu'] . " " . $usuario['apell_usu'] . "</h2>";
            echo "<p>CC: " . $usuario['num_doc_usu'] . "</p>";
            echo "<div class='buttons-container'>";
            echo "<button class='btn-ver-vehiculos' onclick=\"abrirModal('vehiculoModal')\">Ver Documentacion</button>";
            echo "<button class='btn-ver-movilidad' onclick=\"abrirModal('movilidadModal')\">Ver Movilidad</button>";
            echo "</div>";
            echo "</div>";

            // Vehicle Modal
            $sqlVehiculos = "SELECT plac_veh, mar_veh, model_veh, foto_soat, foto_tecno 
                            FROM vehiculos 
                            WHERE num_doc_usu = '$identificacion'";
            $resultVehiculos = $conn->query($sqlVehiculos);

            echo "<div id='vehiculoModal' class='modal' style='display: none;'>";
            echo "<div class='modal-content'>";
            echo "<span class='close-modal' onclick=\"cerrarModal('vehiculoModal')\">&times;</span>";
            echo "<h2>Vehículos Registrados</h2>";

            if ($resultVehiculos && $resultVehiculos->num_rows > 0) {
                while ($vehiculo = $resultVehiculos->fetch_assoc()) {
                    echo "<div class='vehiculo-card'>";
                    echo "<h3>Placa: " . $vehiculo['plac_veh'] . "</h3>";
                    echo "<p><strong>Marca:</strong> " . $vehiculo['mar_veh'] . "</p>";
                    echo "<p><strong>Modelo:</strong> " . $vehiculo['model_veh'] . "</p>";
                    
                    echo "<div class='documentos-container'>";
                    // Mostrar SOAT
                    echo "<div class='documento-item'>";
                    echo "<p>SOAT:</p>";
                    if (!empty($vehiculo['foto_soat'])) {
                        echo "<img src='" . $vehiculo['foto_soat'] . "' alt='SOAT' class='documento-imagen'>";
                    } else {
                        echo "<p class='not-available'>⚠️ SOAT no disponible</p>";
                    }
                    echo "</div>";

                    // Mostrar Tecnomecánica
                    echo "<div class='documento-item'>";
                    echo "<p>TECNOMECÁNICA:</p>";
                    if (!empty($vehiculo['foto_tecno'])) {
                        echo "<img src='" . $vehiculo['foto_tecno'] . "' alt='TECNOMECÁNICA' class='documento-imagen'>";
                    } else {
                        echo "<p class='not-available'>⚠️ TECNOMECÁNICA no disponible</p>";
                    }
                    echo "</div>";
                    echo "</div>"; // cierre documentos-container
                    
                    echo "</div>"; // cierre vehiculo-card
                }
            } else {
                echo "<p>No hay vehículos registrados para este usuario.</p>";
            }
            echo "</div>"; // close modal-content
            echo "</div>"; // close vehiculoModal

            // Mobility Modal
            echo "<div id='movilidadModal' class='modal' style='display: none;'>";
            echo "<div class='modal-content'>";
            echo "<span class='close-modal' onclick=\"cerrarModal('movilidadModal')\">&times;</span>";
            echo "<h2>Fotos de Movilidad</h2>";

            // Get the vehicle plate first
            $sqlPlaca = "SELECT plac_veh FROM vehiculos WHERE num_doc_usu = '$identificacion'";
            $resultPlaca = $conn->query($sqlPlaca);

            if ($resultPlaca && $resultPlaca->num_rows > 0) {
                $vehiculo = $resultPlaca->fetch_assoc();
                $placa = $vehiculo['plac_veh'];

                // Now query movilidad table using placa
                $sqlMovilidad = "SELECT foto_inicial, foto_final FROM movilidad WHERE plac_veh = '$placa'";
                $resultMovilidad = $conn->query($sqlMovilidad);

                if ($resultMovilidad && $resultMovilidad->num_rows > 0) {
                    while ($movilidad = $resultMovilidad->fetch_assoc()) {
                        echo "<div class='movilidad-container'>";
                        
                        // Initial Photo
                        echo "<div class='documento-item'>";
                        echo "<p>FOTO INICIAL:</p>";
                        if (!empty($movilidad['foto_inicial'])) {
                            echo "<img src='uploads/" . $movilidad['foto_inicial'] . "' alt='Foto Inicial' class='documento-imagen' onclick=\"window.open(this.src)\">";
                        } else {
                            echo "<p class='not-available'>⚠️ Foto inicial no disponible</p>";
                        }
                        echo "</div>";

                        // Final Photo
                        echo "<div class='documento-item'>";
                        echo "<p>FOTO FINAL:</p>";
                        if (!empty($movilidad['foto_final'])) {
                            echo "<img src='uploads/" . $movilidad['foto_final'] . "' alt='Foto Final' class='documento-imagen' onclick=\"window.open(this.src)\">";
                        } else {
                            echo "<p class='not-available'>⚠️ Foto final no disponible</p>";
                        }
                        echo "</div>";
                        
                        echo "</div>"; // close movilidad-container
                    }
                } else {
                    echo "<p>No hay fotos de movilidad disponibles para este usuario.</p>";
                }
            } // close if($resultPlaca)
            
            echo "</div>"; // close modal-content
            echo "</div>"; // close movilidadModal
        } // close if($resultado)
    } // close if($_SERVER)
?>
</body>
</html>
