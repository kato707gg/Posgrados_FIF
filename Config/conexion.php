<?php

function Conectar(){
   $Servidor = "localhost";
   $Usuario = "root";
   $Password = "";
   $BD = "sistema_maestria";

   // Conexión orientada a objetos
   $Con = new mysqli($Servidor, $Usuario, $Password, $BD);

   // Verificar conexión
   if ($Con->connect_error) {
       die("Error de conexión: " . $Con->connect_error);
   }

   return $Con;
}

function Ejecutar($Con, $SQL){
   $Query = $Con->query($SQL) or die($Con->error);
   return $Query;
}

function Cerrar($Con){
   $Con->close();
}

?>
