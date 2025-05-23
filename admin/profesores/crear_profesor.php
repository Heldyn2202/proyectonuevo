<?php
include('../../app/config.php');
include('../../admin/layout/parte1.php');

// Verificar si el usuario tiene permisos de administrador
// Aquí deberías incluir tu lógica de verificación de permisos

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $especialidad = $_POST['especialidad'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $estado = 1; // 1 = Activo, 0 = Inactivo

    // Validar los datos
    if (empty($nombres) || empty($apellidos) || empty($cedula) || empty($usuario) || empty($password)) {
        $_SESSION['error_message'] = "Los campos cédula, nombres, apellidos, usuario y contraseña son obligatorios";
    } elseif ($password !== $password_confirm) {
        $_SESSION['error_message'] = "Las contraseñas no coinciden";
    } else {
        // Verificar si el profesor ya existe
        $sql_verificar = "SELECT * FROM profesores WHERE cedula = :cedula OR usuario = :usuario";
        $query_verificar = $pdo->prepare($sql_verificar);
        $query_verificar->bindParam(':cedula', $cedula);
        $query_verificar->bindParam(':usuario', $usuario);
        $query_verificar->execute();
        
        if ($query_verificar->fetch()) {
            $_SESSION['error_message'] = "Ya existe un profesor con esta cédula o nombre de usuario";
        } else {
            // Encriptar la contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar el nuevo profesor
            $sql_insert = "INSERT INTO profesores (cedula, nombres, apellidos, email, telefono, especialidad, usuario, password, estado) 
                          VALUES (:cedula, :nombres, :apellidos, :email, :telefono, :especialidad, :usuario, :password, :estado)";
            $query_insert = $pdo->prepare($sql_insert);
            $query_insert->bindParam(':cedula', $cedula);
            $query_insert->bindParam(':nombres', $nombres);
            $query_insert->bindParam(':apellidos', $apellidos);
            $query_insert->bindParam(':email', $email);
            $query_insert->bindParam(':telefono', $telefono);
            $query_insert->bindParam(':especialidad', $especialidad);
            $query_insert->bindParam(':usuario', $usuario);
            $query_insert->bindParam(':password', $password_hash);
            $query_insert->bindParam(':estado', $estado);
            
            if ($query_insert->execute()) {
                $_SESSION['success_message'] = "Profesor registrado correctamente";
                header("Location: listar_profesores.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Error al registrar el profesor";
            }
        }
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>
    <div class="content">
        <div class="container-fluid">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h3 class="m-0">Registro de Nuevo Profesor</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= APP_URL; ?>/admin">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="<?= APP_URL; ?>/admin/profesores">Profesores</a></li>
                                <li class="breadcrumb-item active"><a href="<?= APP_URL; ?>/admin/profesores/listar_profesores.php">Listado de Profesores</a></li>
                                <li class="breadcrumb-item active">Nuevo Profesor</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            
            <!-- Formulario principal -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datos del Profesor</h3>
                </div>
                <form method="POST" action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cedula">Cédula de Identidad</label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" 
                                           placeholder="Ej: V-12345678" required
                                           pattern="[VEve]-?\d{5,8}" 
                                           title="Formato válido: V-12345678 o E-12345678">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombres">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" 
                                           placeholder="Nombres del profesor" required
                                           pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]+" 
                                           title="Solo letras y espacios">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="apellidos">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                           placeholder="Apellidos del profesor" required
                                           pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]+" 
                                           title="Solo letras y espacios">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="correo@ejemplo.com">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           placeholder="Ej: 04121234567"
                                           pattern="[0-9]{11}" 
                                           title="11 dígitos (ej: 04121234567)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="especialidad">Especialidad</label>
                                    <select id="especialidad" name="especialidad" class="form-control">
                                        <option value="">Seleccione una especialidad</option>
                                        <option value="Informática">Informática</option>
                                        <option value="Matemáticas">Matemáticas</option>
                                        <option value="Idiomas">Idiomas</option>
                                        <option value="Ciencias">Ciencias</option>
                                        <option value="Sociales">Sociales</option>
                                        <option value="Tecnología">Tecnología</option>
                                        <option value="Otra">Otra</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="usuario">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" 
                                           placeholder="Nombre de usuario para acceso" required
                                           pattern="[A-Za-z0-9_]+" 
                                           title="Solo letras, números y guiones bajos">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Contraseña" required
                                           minlength="6">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password_confirm">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                                           placeholder="Repita la contraseña" required
                                           minlength="6">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Guardar Profesor</button>
                        <a href="<?= APP_URL; ?>/admin/profesores/listar_profesores.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('../../admin/layout/parte2.php');
include('../../layout/mensajes.php');
?>

<!-- Validación adicional con JavaScript -->
<script>
$(document).ready(function() {
    // Validar formato de cédula
    $('#cedula').on('blur', function() {
        var cedula = $(this).val();
        if (cedula !== '') {
            if (!/^[VEve]-?\d{5,8}$/.test(cedula)) {
                alert('Formato de cédula no válido. Ejemplo: V-12345678 o E-12345678');
                $(this).focus();
            }
        }
    });

    // Validar que nombres y apellidos solo contengan letras
    $('#nombres, #apellidos').on('blur', function() {
        var value = $(this).val();
        if (value !== '' && !/^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/.test(value)) {
            alert('Este campo solo puede contener letras y espacios');
            $(this).focus();
        }
    });

    // Validar formato de teléfono
    $('#telefono').on('blur', function() {
        var telefono = $(this).val();
        if (telefono !== '' && !/^\d{11}$/.test(telefono)) {
            alert('El teléfono debe tener 11 dígitos (ej: 04121234567)');
            $(this).focus();
        }
    });
    
    // Validar formato de usuario
    $('#usuario').on('blur', function() {
        var usuario = $(this).val();
        if (usuario !== '' && !/^[A-Za-z0-9_]+$/.test(usuario)) {
            alert('El usuario solo puede contener letras, números y guiones bajos');
            $(this).focus();
        }
    });
    
    // Validar coincidencia de contraseñas
    $('#password_confirm').on('blur', function() {
        if ($('#password').val() !== $(this).val()) {
            alert('Las contraseñas no coinciden');
            $(this).focus();
        }
    });
});
</script>