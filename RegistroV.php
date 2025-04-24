<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$conn = new mysqli("localhost", "root", "", "ecomovi");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Add these new queries
$resultMovilidad = $conn->query("SELECT * FROM movilidad");
// Modificar la consulta de puntos para obtener el total correcto
$resultPuntos = $conn->query("
    SELECT plac_veh, COALESCE(SUM(puntos), 0) as total_puntos 
    FROM movilidad 
    WHERE fecha_final IS NOT NULL 
    GROUP BY plac_veh
");

// Modificar la verificación de puntos en el procesamiento POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehiculo_id'], $_POST['recompensa_id'])) {
    $vehiculosId = $_POST['vehiculo_id'];
    $recompensaId = $_POST['recompensa_id'];

    // Obtener puntos totales del vehículo desde la tabla movilidad
    $stmtPuntos = $conn->prepare("
        SELECT COALESCE(SUM(puntos), 0) as puntos_totales 
        FROM movilidad 
        WHERE plac_veh = ? AND fecha_final IS NOT NULL
    ");
    $stmtPuntos->bind_param("s", $vehiculosId);
    $stmtPuntos->execute();
    $resultadoPuntos = $stmtPuntos->get_result()->fetch_assoc();
    $puntosTotales = $resultadoPuntos['puntos_totales'];

    // Obtener información de la recompensa
    $stmtRecompensa = $conn->prepare("SELECT puntos, disponible, nom_reco, descripcion FROM recompensa WHERE nom_reco = ?");
    $stmtRecompensa->bind_param("s", $recompensaId);
    $stmtRecompensa->execute();
    $recompensa = $stmtRecompensa->get_result()->fetch_assoc();

    // Verificar si hay suficientes puntos y la recompensa está disponible
    // Modificar la verificación de puntos y el proceso de redención
    if ($recompensa && $puntosTotales >= $recompensa['puntos'] && $recompensa['disponible'] > 0) {
    // Calcular puntos a descontar
    $puntosDescontar = $recompensa['puntos'];
    $puntosRestantes = $puntosDescontar;
    
    // Get all valid records for this vehicle ordered by date
    $stmtGetRecords = $conn->prepare("
        SELECT id_mov, puntos 
        FROM movilidad 
        WHERE plac_veh = ? 
        AND fecha_final IS NOT NULL 
        AND puntos > 0 
        ORDER BY fecha_final DESC
    ");
    $stmtGetRecords->bind_param("s", $vehiculosId);
    $stmtGetRecords->execute();
    $result = $stmtGetRecords->get_result();
    
    // Update points for each record until we've deducted all necessary points
    while (($row = $result->fetch_assoc()) && $puntosRestantes > 0) {
        $puntosADescontar = min($row['puntos'], $puntosRestantes);
        $nuevosPuntos = $row['puntos'] - $puntosADescontar;
        
        $stmtUpdatePuntos = $conn->prepare("
            UPDATE movilidad 
            SET puntos = ? 
            WHERE id_mov = ?
        ");
        $stmtUpdatePuntos->bind_param("ii", $nuevosPuntos, $row['id_mov']);
        $stmtUpdatePuntos->execute();
        
        $puntosRestantes -= $puntosADescontar;
    }

    // Remove the duplicate point deduction code and continue with reward process
    // Actualizar disponibilidad de la recompensa
    $stmtUpdateRecompensa = $conn->prepare("
        UPDATE recompensa 
        SET disponible = disponible - 1 
        WHERE nom_reco = ? AND disponible > 0
    ");
    $stmtUpdateRecompensa->bind_param("s", $recompensaId);
    $stmtUpdateRecompensa->execute();

    // Registrar el canjeo (corregido según la estructura de la tabla)
    $stmtInsert = $conn->prepare("
        INSERT INTO canjeos (plac_veh, nom_reco, fecha) 
        VALUES (?, ?, NOW())
    ");
    $stmtInsert->bind_param("ss", $vehiculosId, $recompensaId);
    $stmtInsert->execute();

        // CORREO
        date_default_timezone_set('America/Bogota');
        $ahora = date('Y-m-d H:i:s');
        $vencimiento = date('Y-m-d H:i:s', strtotime($ahora . ' +12 hours'));
        $codigoRedencion = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $ubicacionMaps = 'https://www.google.com/maps?q=4.7110,-74.0721';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ingkevinrivera25@gmail.com';
            $mail->Password = 'wttq tooj egmv ergj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('ingkevinrivera25@gmail.com', 'EcoMovi');
            $mail->addAddress('ingkevinrivera25@gmail.com', 'Usuario');
            $mail->isHTML(true);
            $mail->isHTML(true);
             $mail->Subject = '🎉 Redención de puntos confirmada - EcoMovi';
          $mail->Body = '
    <div style="text-align: center;">
        <img src="https://i.ibb.co/SXzV5TBX/logo-blanco.png" alt="EcoMovi Logo" style="width: 180px; margin-bottom: 20px;">
        <h2 style="color:#4CAF50;">¡Redención Exitosa!</h2>
        <p style="font-size: 16px; line-height: 1.5;">
            Tu solicitud de redención de puntos ha sido completada con éxito.<br>
            Los puntos del vehículo con la placa <strong style="color:#e67e22;">' . $vehiculo['plac_veh'] . '</strong> han sido utilizados correctamente para obtener tu recompensa <strong>"' . $recompensa['nom_reco'] . '"</strong>.
        </p>

        <div style="background-color:#f2f2f2; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <p style="font-size: 15px; margin: 0;">
                <strong>Descripción de la recompensa:</strong><br>
                <span style="color: #666; font-size: 14px;">' . nl2br(htmlspecialchars($recompensa['descripcion'])) . '</span>
            </p>
        </div>

        <div style="background-color:#f2f2f2; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <p style="font-size: 15px; margin: 0;">
                Preséntate con el siguiente <strong>código de la recompensa</strong> para reclamar tu recompensa:
            </p>
            <p style="font-size: 22px; color: #4CAF50; margin: 10px 0;"><strong>' . $codigoRedencion . '</strong></p>
            <p style="font-size: 14px; color: #555; margin: 0;">
                Ubicación de redención: <a href="' . $ubicacionMaps . '" target="_blank" style="color: #2c7;">Ver en Google Maps</a>
            </p>
        </div>
        <p style="font-size: 13px; color: #e67e22; background-color: #fff6e0; padding: 10px; border-radius: 6px; margin-top: 10px;">
  <strong>Importante:</strong> Para poder reclamar tu recompensa, es indispensable presentar los documentos actualizados del vehículo. Asegúrate de tener valido el <strong>SOAT</strong>  y la <strong>revisión técnico-mecánica</strong>. Estos documentos serán verificados en el momento de la entrega. Te recomendamos revisarlos con anticipación para evitar contratiempos.
</p>


        <p style="font-size: 15px; color: #555; margin-top: 20px;">
            Tienes <strong>12 horas</strong> a partir de este momento para hacerla efectiva.<br>
            <strong>Fecha y hora de solicitud:</strong> ' . $ahora . '<br>
            <strong>Fecha y hora de vencimiento:</strong> ' . $vencimiento . '
        </p>
        
        <p style="font-size: 13px; color: #e74c3c; background-color: #fef2f2; padding: 10px; border-radius: 6px; margin-top: 10px;">
            <strong>Nota:</strong> Si pasan las 12 horas y no has reclamado la recompensa, los puntos serán devueltos automáticamente.
        </p>

        <p style="font-size: 15px; color: #555;">Gracias por confiar en nosotros y ser parte de una movilidad más ecológica.</p>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">

    <div style="text-align: center; margin-top: 20px;">
        <p style="font-size: 14px; color: #777;">Síguenos en nuestras redes sociales</p>
        <a href="https://facebook.com" style="margin: 0 10px;"><img src="https://cdn-icons-png.flaticon.com/24/145/145802.png" alt="Facebook"></a>
        <a href="https://instagram.com" style="margin: 0 10px;"><img src="https://cdn-icons-png.flaticon.com/24/2111/2111463.png" alt="Instagram"></a>
        <a href="https://wa.me/573001234567" style="margin: 0 10px;"><img src="https://cdn-icons-png.flaticon.com/24/733/733585.png" alt="WhatsApp"></a>
    </div>

    <p style="text-align:center; font-size: 12px; color: #aaa; margin-top: 30px;">
        © 2025 EcoMovi - Todos los derechos reservados
    </p>
</div>';

$mail->AltBody = 'Tu redención ha sido completada. Código de redención: ' . $codigoRedencion . '. Tienes 12 horas desde ahora (' . $ahora . ') hasta ' . $vencimiento . ' para reclamarla. Ubicación: ' . $ubicacionMaps . '. Placa: ' . $vehiculo['plac_veh'] . '.';

            $mail->send();
        } catch (Exception $e) {
            $mensajeRedencion .= "\n(No se pudo enviar correo: {$mail->ErrorInfo})";
        }

        $mensajeRedencion = "¡Recompensa redimida con éxito! Código enviado al correo.";
    } else {
        $mensajeRedencion = "No tienes suficientes puntos o la recompensa ya no está disponible.";
    }
    // Redirect with message
    header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=" . urlencode($mensajeRedencion));
    exit();
}



$resultVehiculos = $conn->query("SELECT * FROM vehiculos");
$resultRecompensas = $conn->query("SELECT * FROM recompensa");
$recompensas = [];
while ($row = $resultRecompensas->fetch_assoc()) {
    $recompensas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EcoMovi - Redimir recompensa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        p{
            color: white;
        }

        h5{
            color:white;
        }
        .botoncito{
            position: fixed;
            left: 20px;
            top: 20px;
            z-index: 1000;
            padding: 15px 25px;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
        }

        .botoncito:hover {
            transform: translateY(-3px);
            color: white;
        }

        .card .btn {
            padding: 10px 20px;
            margin: 5px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            width: 80%;
            display: block;
            margin: 10px auto;
        }

        .btn-success {
        background: linear-gradient(45deg, #2ecc71, #27ae60);
        border: none;
        box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }

        .btn-info {
        background: linear-gradient(45deg, #3498db, #2980b9);
        border: none;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        color: white !important;
        }

        .btn-warning {
        background: linear-gradient(45deg, #f1c40f, #f39c12);
        border: none;
        box-shadow: 0 4px 15px rgba(241, 196, 15, 0.3);
        color: white !important;
        }

        .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .card {
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    border: none;
    padding: 25px 15px;
    background:  rgba(255, 255, 255, 0.274) !important;
    transition: all 0.3s ease;
    min-height: 320px;
    display: flex;
    flex-direction: column;
}


        .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card-info {
            margin-bottom: 20px;
          
        }

        .buttons-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: auto;
        }

        .card .btn {
            padding: 12px 20px;
            margin: 0;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn i {
            font-size: 1.1em;
        }
    .list-group-item {
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 10px !important;
        border: 1px   ;
        background: rgba(255, 255, 255, 0.274);
        transition: all 0.3s ease;
    }
    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .list-group-item img {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .list-group-item .btn {
        padding: 5px 15px;
        border-radius: 20px;
    }
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
@keyframes brillo {
            0% {
                box-shadow: 0 0 15px 5px #00ff00;
            }

            50% {
                box-shadow: 0 0 35px 15px #00ff00;
            }

            100% {
                box-shadow: 0 0 15px 5px #00ff00;
            }
        }

        .borde-verde {
            border: 3.5px solid #00ff00;
            box-shadow: 0 0 25px 10px #00ff00;
            animation: brillo 1s infinite ease-in-out;
        }


</style>
</head>
<body>
    <a href="iniusu.html" class="botoncito">
    <i class="fas fa-arrow-left"></i>Regresar
    </a>

    <?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4" style="z-index: 1050;" role="alert">
        <?= htmlspecialchars($_GET['mensaje']) ?>
        <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="alert" ></button>
    </div>
    <?php endif; ?>

    <div class="container mt-5">
    <link rel="stylesheet" href="estilousuario.css">
    <link rel="icon" href="logo blanco.png" type="image/png">


    <h1 class="text-center">Vehículos Registrados</h1>
    <div class="par text-center">
        <p><b>En este apartado encontrará información básica sobre los vehículos registrados a su nombre</b></p>
    </div>

    <div class="row">
        <?php foreach ($resultVehiculos as $vehiculos): 
            // Get points for this vehicle
            $puntos = 0;
            if ($resultPuntos) {
                $resultPuntos->data_seek(0);
                while ($row = $resultPuntos->fetch_assoc()) {
                    if ($row['plac_veh'] === $vehiculos['plac_veh']) {
                        $puntos = $row['total_puntos'];
                        break;
                    }
                }
            }
        ?>
       
  

            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="card-info">
                            <b><h5 class="card-title"><?= htmlspecialchars($vehiculos['mar_veh']) ?></h5></b>
                            <p>Placa: <?= htmlspecialchars($vehiculos['plac_veh']) ?><br>
                               Puntos Totales: <?= $puntos ?></p>
                        </div>
                        <div class="buttons-container">
                            <button class="btn btn-success reward-btn" data-bs-toggle="modal" data-bs-target="#rewardsModal"
                                    onclick="showRecompensas('<?= $vehiculos['plac_veh'] ?>')">
                                <i class="fas fa-gift"></i> Recompensa
                            </button>
                            <a href="seguimiento de movilidad.php?id=<?= htmlspecialchars($vehiculos['plac_veh']) ?>" 
                               class="btn btn-info">
                                <i class="fas fa-route"></i> Registrar Movilidad
                            </a>
                            <a href="continuar_formulario.php?plac_veh=<?= htmlspecialchars($vehiculos['plac_veh']) ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Completar registro
                            </a>
                           
                        </div>
                       
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
 

    <div class="text-center mt-5 mb-5">
        <img class="Icono" src="logo blanco.png" alt="Logo" width="300px">
    </div>
    


<!-- Modal -->
<div class="modal fade" id="rewardsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Recompensas disponibles</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="vehiculo_id" id="vehiculo_id">
                    <ul class="list-group list-group-flush" id="listaRecompensas"></ul>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .modal-header {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        background: linear-gradient(45deg, #4CAF50, #45a049);
    }
    
    .list-group-item {
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 10px !important;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .list-group-item img {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .list-group-item .btn {
        padding: 5px 15px;
        border-radius: 20px;
    }
    
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>
<!-- JavaScript -->
<script>

    
   // 17. Recompensas pasadas desde PHP a JavaScript
const recompensas = <?= json_encode($recompensas) ?>;

// 18. Función que se llama al presionar "Recompensa"
// Update the form input names in JavaScript
function showRecompensas(vehiculoId) {
    document.getElementById('vehiculo_id').value = vehiculoId;
    const lista = document.getElementById('listaRecompensas');
    lista.innerHTML = '';

    recompensas.forEach(r => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex align-items-center';

        const img = document.createElement('img');
        img.src = r.imagen_url || 'placeholder.jpg';
        img.alt = 'Imagen Recompensa';
        img.style = 'width: 50px; height: 50px; object-fit: cover; margin-right: 10px;';

        const desc = document.createElement('div');
        desc.innerHTML = `${r.nom_reco} - <b>Puntos: ${r.puntos}</b>`;

        li.appendChild(img);
        li.appendChild(desc);

        if (parseInt(r.disponible)) {
            const btn = document.createElement('button');
            btn.type = 'submit';
            btn.name = 'recompensa_id';
            btn.value = r.nom_reco;
            btn.className = 'btn btn-success btn-sm ms-auto';
            btn.innerText = 'Redimir';
            li.appendChild(btn);
        } else {
            const span = document.createElement('span');
            span.className = 'text-danger ms-auto';
            span.innerText = 'Agotado';
            li.appendChild(span);
        }

        lista.appendChild(li);
    });
}

// 21. Muestra una alerta si hubo redención
<?php if (!empty($mensajeRedencion)): ?>
    alert("<?= addslashes($mensajeRedencion) ?>");
<?php endif; ?>

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</html>
