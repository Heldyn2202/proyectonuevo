<?php  
include('../app/config.php');  
session_start();  

// Prevenir caching de la página de login
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>  
<!DOCTYPE html>  
<html lang="es">  
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - <?=htmlspecialchars(APP_NAME, ENT_QUOTES)?></title>
    
    <!-- Google Font: Source Sans Pro -->  
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">  
    <!-- Font Awesome -->  
    <link rel="stylesheet" href="<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/public/plugins/fontawesome-free/css/all.min.css">  
    <!-- icheck bootstrap -->  
    <link rel="stylesheet" href="<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">  
    <!-- Theme style -->  
    <link rel="stylesheet" href="<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/public/dist/css/adminlte.min.css">  
    <!-- Sweetalert2 -->  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
    
    <style>
        .login-page {
            background-image: url(<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/img/fondo2.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        .login-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .login-logo img {
            transition: transform 0.3s ease;
        }
        .login-logo img:hover {
            transform: scale(1.05);
        }
        .btn-login {
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .input-group-text {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .input-group-text:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>  
<body class="hold-transition login-page">  
<div class="login-box">
    <div class="login-card card">  
        <div class="card-body login-card-body">  
            <div class="text-center mb-4">
                <figure class="login-logo">  
                    <img src="<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/img/avatar.png" alt="<?=htmlspecialchars(APP_NAME, ENT_QUOTES)?>" class="img-fluid" style="max-width: 100px;">  
                </figure>  
                <h3 class="mt-3"><b><?=htmlspecialchars(APP_NAME, ENT_QUOTES)?></b></h3>
            </div>
            
            <hr class="my-4">

            <form action="controller_login.php" method="post" id="loginForm" autocomplete="on">  
                <div class="input-group mb-3">  
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autocomplete="off" autocapitalize="off" inputmode="email">
                    <div class="input-group-append">  
                        <div class="input-group-text">  
                            <span class="fas fa-envelope"></span>  
                        </div>  
                    </div>  
                </div>  
                
                <div class="input-group mb-3">  
                    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required minlength="6" autocomplete="current-password">
                    <div class="input-group-append">  
                        <div class="input-group-text" id="togglePassword" title="Mostrar/Ocultar contraseña">  
                            <span class="fas fa-eye" id="eyeIcon"></span>  
                        </div>  
                    </div>  
                </div>  

                <div class="row mb-3">
                    <div class="col-7 d-flex align-items-center">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember" title="Mantenerme autenticado">
                                Recordarme
                            </label>
                        </div>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block btn-login">
                            <span class="fas fa-sign-in-alt"></span>
                            Acceder
                        </button>
                    </div>
                </div>
            </form>  

        </div>  
    </div>  
</div>  

<!-- jQuery -->
<script src="<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=htmlspecialchars(APP_URL, ENT_QUOTES)?>/public/dist/js/adminlte.min.js"></script>

<script>  
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');  
    const passwordInput = document.getElementById('password');  
    const eyeIcon = document.getElementById('eyeIcon');  

    togglePassword.addEventListener('click', () => {  
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';  
        passwordInput.setAttribute('type', type);  
        eyeIcon.classList.toggle('fa-eye');  
        eyeIcon.classList.toggle('fa-eye-slash');  
    });
    
    // Form submission handler
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
    });

    // Mostrar mensajes de sesión
    <?php if (isset($_SESSION['mensaje'])): ?>  
        Swal.fire({  
            icon: '<?= isset($_SESSION['icono']) ? addslashes($_SESSION['icono']) : 'info' ?>',  
            title: '<?= isset($_SESSION['titulo']) ? addslashes($_SESSION['titulo']) : (isset($_SESSION['icono']) && $_SESSION['icono'] === 'success' ? 'Éxito' : 'Error') ?>',  
            text: '<?= addslashes($_SESSION['mensaje']) ?>',  
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false
        }).then(() => {
            <?php if (isset($_SESSION['redirect'])): ?>
                window.location.href = '<?= addslashes($_SESSION['redirect']) ?>';
            <?php endif; ?>
        });  
        <?php   
            unset($_SESSION['mensaje']);
            unset($_SESSION['icono']);
            unset($_SESSION['titulo']);
            unset($_SESSION['redirect']);
        ?>  
    <?php endif; ?>  
});
</script>  

</body>  
</html>