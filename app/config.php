<?php  

if (!defined('SERVIDOR')) {  
    define('SERVIDOR', 'localhost');  
}  

if (!defined('USUARIO')) {  
    define('USUARIO', 'root');  
}  

if (!defined('PASSWORD')) {  
    define('PASSWORD', '');  
}  

if (!defined('BD')) {  
    define('BD', 'sige');  
}  

if (!defined('APP_NAME')) {  
    define('APP_NAME', 'U.E.N ROBERTO MARTINEZ CENTENO');  
}  

if (!defined('APP_URL')) {  
    define('APP_URL', 'http://localhost/proyectonuevo/sige');  
}  

if (!defined('KEY_API_MAPS')) {  
    define('KEY_API_MAPS', '');  
}  

$dsn = "mysql:dbname=" . BD . ";host=" . SERVIDOR . ";charset=utf8mb4";  

try {  
    $pdo = new PDO($dsn, USUARIO, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));  
} catch (PDOException $e) {  
    echo $e->getMessage();  
    echo "Error: No se pudo conectar a la base de datos";  
}  

date_default_timezone_set("America/Caracas");  
$fechaHora = date('Y-m-d');  

$fecha_actual = date('Y-m-d');  
$dia_actual = date('d');  
$mes_actual = date('m');  
$ano_actual = date('Y');  
$ano_siguiente = date("Y") + 1;  

$estado_de_registro = '1';




