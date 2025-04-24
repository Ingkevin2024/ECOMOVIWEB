<!DOCTYPE html>
<html>
<head>
    <title>Registrar Vehículo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecomovi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and get form data with error checking
    $plac_veh = isset($_POST['plac_veh']) ? strtoupper($_POST['plac_veh']) : '';
    $tip_veh = isset($_POST['tip_veh']) ? $_POST['tip_veh'] : '';
    $tarj_prop_veh = isset($_POST['tarj_prop_veh']) ? $_POST['tarj_prop_veh'] : '';
    $tecno_m = isset($_POST['tecno_m']) ? $_POST['tecno_m'] : '';
    $soat = isset($_POST['soat']) ? $_POST['soat'] : '';
    $mar_veh = isset($_POST['mar_veh']) ? $_POST['mar_veh'] : '';
    $lin_veh = $_POST['lin_veh'];
    $color_veh = $_POST['color_veh'];
    $num_motor_veh = $_POST['num_motor_veh'];
    $clase_veh = $_POST['clase_veh'];
    $combus_veh = $_POST['combus_veh'];
    $capaci_veh = $_POST['capaci_veh'];
    $num_chasis_veh = $_POST['num_chasis_veh'];
    $model_veh = $_POST['model_veh'];

    // Validate required fields
    if (empty($plac_veh) || empty($tip_veh) || empty($tarj_prop_veh)) {
        echo "<script>
                alert('Por favor, complete todos los campos requeridos');
                window.location.href = 'registrarv.html';
              </script>";
        exit();
    }

    // Handle file uploads
    $upload_dir = "uploads/";
    
    // Create upload directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Process foto_tecno
    $foto_tecno = "";
    if(isset($_FILES['foto_tecno']) && $_FILES['foto_tecno']['error'] == 0) {
        $foto_tecno = $upload_dir . uniqid() . "_" . $_FILES['foto_tecno']['name'];
        move_uploaded_file($_FILES['foto_tecno']['tmp_name'], $foto_tecno);
    }

    // Process foto_soat
    $foto_soat = "";
    if(isset($_FILES['foto_soat']) && $_FILES['foto_soat']['error'] == 0) {
        $foto_soat = $upload_dir . uniqid() . "_" . $_FILES['foto_soat']['name'];
        move_uploaded_file($_FILES['foto_soat']['tmp_name'], $foto_soat);
    }

    // Process vehicle_photo (optional)
    $vehicle_photo = "";
    if(isset($_FILES['vehicle_photo']) && $_FILES['vehicle_photo']['error'] == 0) {
        $vehicle_photo = $upload_dir . uniqid() . "_" . $_FILES['vehicle_photo']['name'];
        move_uploaded_file($_FILES['vehicle_photo']['tmp_name'], $vehicle_photo);
    }

    // Prepare SQL statement
    $sql = "INSERT INTO vehiculos (plac_veh, tip_veh, tarj_prop_veh, tecno_m, foto_tecno, 
            soat, foto_soat, mar_veh, lin_veh, color_veh, num_motor_veh, clase_veh, 
            combus_veh, capaci_veh, num_chasis_veh, model_veh, vehicle_photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssss", 
        $plac_veh, $tip_veh, $tarj_prop_veh, $tecno_m, $foto_tecno, 
        $soat, $foto_soat, $mar_veh, $lin_veh, $color_veh, 
        $num_motor_veh, $clase_veh, $combus_veh, $capaci_veh, 
        $num_chasis_veh, $model_veh, $vehicle_photo);

    // Add this check before the insert query
    // Modify the duplicate check section
    $check_placa = $conn->prepare("SELECT plac_veh FROM vehiculos WHERE plac_veh = ?");
    $check_placa->bind_param("s", $plac_veh);
    $check_placa->execute();
    $result = $check_placa->get_result();
    
    if ($result->num_rows > 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Placa ya registrada',
                text: 'La placa $plac_veh ya se encuentra registrada en el sistema',
                confirmButtonColor: '#28a745'
            }).then(function() {
                window.history.back();
            });
        </script>";
        exit();
    }
    
    // Continue with your existing insert query if the plate doesn't exist
    $stmt = $conn->prepare("INSERT INTO vehiculos (plac_veh, mar_veh, tip_veh) VALUES (?, ?, ?)");
    if ($stmt->execute()) {
        echo "<script>
                alert('Vehículo registrado exitosamente');
                window.location.href = 'iniusu.html';
              </script>";
    } else {
        echo "<script>
                alert('Error al registrar el vehículo: " . $stmt->error . "');
                window.location.href = 'registrarv.html';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
