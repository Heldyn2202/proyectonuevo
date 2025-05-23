<?php
session_start();

if(isset($_SESSION['sesion_email'])) {
   // echo "el usuarios paso por el login";
    $email_sesion = $_SESSION['sesion_email'];
    $query_sesion = $pdo->prepare ("SELECT * FROM usuarios as usu
                                    INNER JOIN roles as rol ON rol.id_rol = usu.rol_id 
                                    INNER JOIN personas as per ON per.usuario_id = usu.id_usuario
                                    WHERE usu.email = '$email_sesion' AND usu.estado = '1' ");
    $query_sesion->execute();

    $datos_sesion_usuarios = $query_sesion->fetchAll(PDO::FETCH_ASSOC);
    foreach ($datos_sesion_usuarios as $datos_sesion_usuario){
       $$email_sesion = $datos_sesion_usuario['email'];
       $id_rol_sesion_usuario = $datos_sesion_usuario['id_rol'];
       $rol_sesion_usuario = $datos_sesion_usuario['nombre_rol'];
       $nombres_sesion_usuario = $datos_sesion_usuario['nombres'];
       $apellidos_sesion_usuario = $datos_sesion_usuario['apellidos'];
       $ci_sesion_usuario = $datos_sesion_usuario['ci'];
    }

    $url = $_SERVER["PHP_SELF"];
    $conta = strlen($url);
    $rest = substr($url, 18, $conta);


    $sql_roles_permisos = "SELECT * FROM roles_permisos as rolper 
                       INNER JOIN permisos as per ON per.id_permiso = rolper.permiso_id 
                       INNER JOIN roles as rol ON rol.id_rol = rolper.rol_id 
                       where rolper.estado = '1' ";
    $query_roles_permisos = $pdo->prepare($sql_roles_permisos);
    $query_roles_permisos->execute();
    $roles_permisos = $query_roles_permisos->fetchAll(PDO::FETCH_ASSOC);
    $contadorpermiso = 1;
    foreach ($roles_permisos as $roles_permiso){
        if($id_rol_sesion_usuario = $roles_permiso['rol_id']){
            //echo $roles_permiso['url'];
            if($rest == $roles_permiso['url']){
                // echo "permiso autorizado - ";
                $contadorpermiso = $contadorpermiso + 3;
            }else{
                // echo "no autorizadó";
            }

        }
    }
}else{
    echo "el usuario no paso por el login";
    header('Location:'.APP_URL."/login");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=APP_NAME;?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?=APP_URL;?>/public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=APP_URL;?>/public/dist/css/adminlte.min.css">

    <!-- jQuery -->
    <script src="<?=APP_URL;?>/public/plugins/jquery/jquery.min.js"></script>

    <!-- Sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Iconos de bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Datatables -->
    <link rel="stylesheet" href="<?=APP_URL;?>/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?=APP_URL;?>/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?=APP_URL;?>/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>.sidebar-dark-yellow .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-yellow .nav-sidebar>.nav-item>.nav-link.active {
        color:white;
    }</style>

    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-info navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            

<ul class="navbar-nav ml-auto">
     <li class="nav-item dropdown">
            <a class="nav-link"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
              <span>
                <div class="d-felx badge-pill">
                  <span class="fa fa-user mr-2"></span>
                  <span><b></b></span>
                  <span class="fa fa-angle-down ml-2"></span>
                </div>
              </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
            <h4 class="dropdown-header">
            <div class="d-flex justify-content-center">  <span class="initials-circle img-circle elevation-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px; background-color: #ddd; color: #333;">
        <?php echo substr(ucwords($email_sesion), 0, 1); ?>
    </span>
</div>
<br>
<i class="fa fa-user"></i>
            <?php echo ucwords($email_sesion)?>
            </h4>
            <div class="dropdown-divider"></div>
              <a class="dropdown-item"href="javascript:void(0)" id="logout-button"><span class="fas fa-sign-in-alt"></span>  Cerrar sesion</a>
            </div>
      </li>
    </ul>
<script>  
    document.getElementById("logout-button").addEventListener("click", function(event) {  
        event.preventDefault(); // Evita la redirección inmediata  

        // Muestra el mensaje de confirmación de SweetAlert2  
        Swal.fire({  
            title: '¿Estás seguro?',  
            text: "¿Quieres cerrar sesión?",  
            icon: 'warning',  
            showCancelButton: true,  
            confirmButtonColor: '#3085d6',  
            cancelButtonColor: '#d33',  
            confirmButtonText: 'Sí, cerrar sesión!',  
            cancelButtonText: 'Cancelar'  
        }).then((result) => {  
            if (result.isConfirmed) {  
                // Si el usuario confirma, redirigere a la página de logout  
                window.location.href = "<?=APP_URL;?>/login/logout.php";  
            }  
        });  
    });  
</script>
            
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-info  elevation-6">
        <!-- Sidebar -->
        <div class="sidebar">
        <div class="dropdown">
   	<a href="<?=APP_URL;?>/admin" class="brand-link">
        <h4 class="text-center p-0 m-0"><b>SIGE</b></h4>
    </a>  
    </div>
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?=APP_URL;?>/img/logo.jpg" class="img-circle elevation" alt="UserIcon">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?=$email_sesion;?></a>
                </div>
            </div>


            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                         <li class="nav-item">
                        <a href="<?=APP_URL;?>/admin" class="nav-link active" >
                            <i class="nav-icon fas"><i class="bi bi-door-open"></i></i>
                     
                            <p>
                                Inicio
                            </p>
                        </a>
                    </li>
     
                    <?php
                    if( ($rol_sesion_usuario="ADMINISTRADOR") || ($rol_sesion_usuario="DIRECTOR ACADÉMICO") || ($rol_sesion_usuario="SUBDIRECTOR")){ ?>
                        
                    <?php
                    }
                    ?>
                   

                   <?php
                    if( ($rol_sesion_usuario=="ADMINISTRADOR") || ($rol_sesion_usuario=="DIRECTOR ACADÉMICO") || ($rol_sesion_usuario=="SECRETARIA")){ ?>
                        <li class="nav-item">
                            <a href="<?=APP_URL;?>/admin/representantes" class="nav-link active">
                                <i class="nav-icon fas"><i class="bi bi-person-video3"></i></i>
                                <p>
                                    Representantes
                                </p>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    if( ($rol_sesion_usuario=="ADMINISTRADOR") || ($rol_sesion_usuario=="DIRECTOR ACADÉMICO") || ($rol_sesion_usuario=="DIRECTOR ADMINISTRATIVO") || ($rol_sesion_usuario=="SECRETARIA")|| ($rol_sesion_usuario=="CONTADOR")){ ?>
                        <li class="nav-item">
                            <a href="<?=APP_URL;?>/admin/estudiantes" class="nav-link active">
                                <i class="nav-icon fas"><i class="bi bi-person-video"></i></i>
                                <p>
                                Estudiantes
                                </p>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    if( ($rol_sesion_usuario=="ADMINISTRADOR") || ($rol_sesion_usuario=="DIRECTOR ACADÉMICO") || ($rol_sesion_usuario=="DIRECTOR ADMINISTRATIVO") || ($rol_sesion_usuario=="SECRETARIA")|| ($rol_sesion_usuario=="CONTADOR")){ ?>
                        <li class="nav-item">
                            <a href="<?=APP_URL;?>/admin/profesores/listar_profesores.php" class="nav-link active">
                                <i class="nav-icon fas"><i class="fas fa-chalkboard-teacher"></i></i>
                                <p>
                                Profesores
                                </p>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                   


                    <?php
if ($rol_sesion_usuario == "ADMINISTRADOR") { ?>
    <li class="nav-item">
        <a href="<?= APP_URL; ?>/admin/reportes" class="nav-link active">
            <i class="nav-icon fas"><i class="bi bi-file-earmark-text"></i></i>
            <p>
                Reportes
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= APP_URL; ?>/admin/notas" class="nav-link active">
            <i class="nav-icon fas"><i class="bi bi-book"></i></i>
            <p>
                Notas y Horarios
            </p>
        </a>
    </li>
    <li class="nav-item">
                            <a href="<?=APP_URL;?>/admin/configuraciones" class="nav-link active">
                                <i class="nav-icon fas"><i class="bi bi-gear"></i></i>
                                <p>
                                Modulo administrativo
                                </p>
                            </a>
                        </li>
<?php
}
?>





                    
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>