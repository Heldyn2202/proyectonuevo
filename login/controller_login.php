<?php  

include('../app/config.php');  

// Verifica si el método de la solicitud es POST  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {  

    $email = $_POST['email'];  
    $password = $_POST['password'];  

    // Preparar la consulta para evitar inyecciones SQL  
    $sql = "SELECT * FROM usuarios WHERE email = :email";  
    $query = $pdo->prepare($sql);  
    $query->bindParam(':email', $email);  
    $query->execute();  

    // Obtener los resultados  
    $usuario = $query->fetch(PDO::FETCH_ASSOC);  

    if ($usuario) {  
        // Verificar si el usuario está activo
        if ($usuario['estado'] == '0') {
            // Usuario inactivo
            session_start();  
            $_SESSION['mensaje'] = "El usuario está inactivo. Por favor, contacte al administrador.";  
            $_SESSION['icono'] = "warning";
            header('Location:' . APP_URL . "/login/login.php");  
            exit;  
        } else {
            // Usuario activo, verificar la contraseña  
            $password_tabla = $usuario['password'];  

            if (password_verify($password, $password_tabla)) {  
                session_start();  
                $_SESSION['mensaje'] = "Bienvenido al sistema SIGE";  
                $_SESSION['icono'] = "success";  
                $_SESSION['sesion_email'] = $email;  
                // Redirigir a la página de administración  
                header('Location:' . APP_URL . "/admin");  
                exit;  
            } else {  
                // Contraseña incorrecta  
                session_start();  
                $_SESSION['mensaje'] = "Error de Usuario o Contraseña";  
                $_SESSION['icono'] = "error";  
                header('Location:' . APP_URL . "/login/login.php");  
                exit;  
            }  
        }
    } else {  
        // Usuario no encontrado  
        session_start();  
        $_SESSION['mensaje'] = "Error de Usuario o Contraseña";  
        $_SESSION['icono'] = "error";  
        header('Location:' . APP_URL . "/login/login.php");  
        exit;  
    }  
} else {  
    // Manejo de acceso directo al archivo  
    header('Location:' . APP_URL . "/login/login.php");  
    exit;  
}  
?>