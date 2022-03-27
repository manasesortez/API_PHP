<?php
function conectar(){
    //credenciales de xampp
    $server = "localhost";
    $username = "root";
    $password = "";
    $bd = "miagenda";

    try{
        $idConexion = mysqli_connect($server, $username, $password, $bd);
    }catch(Exception $e){
        $idConexion = $e . " " . mysqli_error($idConexion);
    }
    return $idConexion;

}

function desconectar($idConexion){
    try{
        mysqli_close($idConexion);
        $estado = 1;
    }catch(Exception $e){
        $estado = 0;
    }
    return $estado;
}

function agregarContacto($nombre,$telefono){
    $idConexion = conectar();
    $sql = "INSERT INTO contactos (nombre, telefono) VALUES ('$nombre', '$telefono')";
    if(mysqli_query($idConexion, $sql)){
        $estado = 1;
    }else{
        $estado ="Error: " . mysqli_error($idConexion);
    }
    desconectar($idConexion);
    return $estado;
}

function listarContacto($filtro){
    $idConexion = conectar();
    $datosFila = array();
    $consulta = "SELECT id_contacto, nombre, telefono FROM contactos WHERE (nombre LIKE '%$filtro%' OR  telefono LIKE '%$filtro') ORDER BY nombre ASC";
    $query = mysqli_query($idConexion, $consulta);
    $nfilas = mysqli_num_rows($query);
    if($nfilas != 0){
        while($aDatos = mysqli_fetch_array($query)){
            $jsonfila = array();

            $id_contacto = $aDatos["id_contacto"];
            $nombre = $aDatos["nombre"];
            $telefono = $aDatos["telefono"];

            $jsonfila["id_contacto"] = $id_contacto;
            $jsonfila["nombre"] = $nombre;
            $jsonfila["telefono"] = $telefono;

            $datosFila[] = $jsonfila;
        }
    }
    desconectar($idConexion);
    return array_values($datosFila);
}
?>