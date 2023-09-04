<?php
//Headers para peticiones http metodo 1
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']); // Allow the requesting origin
    header("HTTP/1.1 204 No Content");
    exit();
}
//Headers para peticiones http si metodo 1 falla
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

$servidor = "localhost"; 
$usuario = "root"; 
$contrasenia = ""; 
$nombreBaseDatos = "empresa";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);

if (isset($_GET["consultar"])) {
    $sqlEmpleados = mysqli_query($conexionBD, "SELECT * FROM empleados WHERE id=".$_GET["consultar"]);
    if(mysqli_num_rows($sqlEmpleados) > 0){
    
    
        $empleados = mysqli_fetch_all($sqlEmpleados, MYSQLI_ASSOC);
        echo json_encode($empleados);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["borrar"])) {
    $sqlEmpleados = mysqli_query($conexionBD, "DELETE FROM empleados WHERE id=".$_GET["borrar"]);
    if($sqlEmpleados) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if(isset($_GET["insertar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $correo = $data->correo;
    if(($correo != "") && ($nombre != "")) {
        $sqlEmpleados = mysqli_query($conexionBD, "INSERT INTO empleados(nombre,correo) VALUES('$nombre','$correo') ");
        echo json_encode(["success" => 1]);
    }
    exit();
}

if(isset($_GET["actualizar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = (isset($data->id)) ? $data->id : $_GET["actualizar"];
    $nombre = $data->nombre;
    $correo = $data->correo;
    $sqlEmpleados = mysqli_query($conexionBD, "UPDATE empleados SET nombre='$nombre',correo='$correo' WHERE id='$id'");
    echo json_encode(["success" => 1]);
    exit();
}

$sqlEmpleados = mysqli_query($conexionBD, "SELECT * FROM empleados ");
if(mysqli_num_rows($sqlEmpleados) > 0){
    $empleados = mysqli_fetch_all($sqlEmpleados, MYSQLI_ASSOC);
    echo json_encode($empleados);
} else {
    echo json_encode([["success" => 0]]);
}
?>
