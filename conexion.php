<?php 
  $usuario = "root";
  $pass = "";
  
  try{
    $bd = new PDO("mysql:host=localhost;dbname=madurodolar;", $usuario, $pass);
    // $bd = null;
  }catch(PDOException $e){
    print "¡Error!: " . $e->getMessage() . "<br>";
    die();
  }
?>