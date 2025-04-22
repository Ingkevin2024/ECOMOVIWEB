<?php
session_start(); // Inicia la sesión para guardar información del usuario entre páginas

// Datos de conexión a la base de datos
$servername = "localhost"; // Servidor de base de datos (local)
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña (vacía en este caso)
$dbname = "ecomovi"; // Nombre de la base de datos

// Crear la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si hubo un error en la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Termina el script si hay error
}

// Verifica si el formulario fue enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura los datos enviados desde el formulario
    $num_doc_usu = $_POST['num_doc_usu']; // Número de documento del usuario
    $contraseña = $_POST['contraseña']; // Contraseña ingresada (actualmente no se valida)
    $tipo_usuario = $_POST['tipo_Inicio']; // Tipo de usuario seleccionado

    // Mapeo del tipo de usuario del formulario con el valor del campo "rol" en la BD
    $rol_map = [
        'Usuario' => 'usuario',
        'Administrador' => 'admin',
        'Supervisor' => 'supervisor'
    ];

    $rol = $rol_map[$tipo_usuario]; // Obtiene el valor correspondiente al tipo de usuario

    // Consulta preparada para prevenir inyecciones SQL
    $sql = "SELECT * FROM usuarios WHERE num_doc_usu = ? AND rol = ?";
    $stmt = $conn->prepare($sql); // Prepara la consulta
    $stmt->bind_param("ss", $num_doc_usu, $rol); // Asocia los parámetros a la consulta
    $stmt->execute(); // Ejecuta la consulta
    $result = $stmt->get_result(); // Obtiene los resultados

    // Verifica si existe un usuario con esos datos
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Extrae los datos del usuario
        $_SESSION['nombre'] = $user['nombre']; // Guarda el nombre en la sesión
        $_SESSION['rol'] = $rol; // Guarda el rol en la sesión

        // Redirecciona a diferentes páginas según el tipo de usuario
        switch($tipo_usuario) {
            case 'Usuario':
                header("Location: iniusu.html"); // Página del usuario
                break;
            case 'Supervisor':
                header("Location: supervisor(PRINCIPAL).html"); // Página del supervisor
                break;
            case 'Administrador':
                header("Location: paginaadministrador.html"); // Página del administrador
                break;
            default:
                header("Location: INICIOSESION.html"); // En caso de un valor inesperado
                break;
        }
        exit(); // Finaliza el script después de redirigir
    } else {
        // Si no se encuentra el usuario, muestra un mensaje y redirige al login
        echo "<script>
                alert('Por favor, verifique su usuario. Los datos ingresados no coinciden con nuestros registros.');
                window.location.href = 'INICIOSESION.html';
              </script>";
    }
} else {
    // Si el formulario no fue enviado por POST, también redirige
    echo "<script>
            alert('Por favor, verifique su usuario. Los datos ingresados no coinciden con nuestros registros.');
            window.location.href = 'INICIOSESION.html';
          </script>";
}

// Cierra la consulta preparada y la conexión
$stmt->close();
$conn->close();
?>
