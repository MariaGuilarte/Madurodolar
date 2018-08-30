<?php 
  include("conexion.php");
  require_once (__DIR__ . "/vendor/autoload.php");
  include("vendor/simple-html-dom/simple-html-dom/simple_html_dom.php");
  use PHPImageWorkshop\ImageWorkshop as ImageWorkshop;
  
  // Dolar bolivarcucuta
  $html = file_get_html("http://www.bolivarcucuta.com/");  
  $dolar = $html->find("div#indica #dolar");

  $dolarInner = "";
  
  foreach( $dolar as $d ){
    $dolarInner = $d->innertext;
  }

  $dolarcucuta = explode(" ", $dolarInner)[0];
  // echo $dolarcucuta . "<br>";
  
  // Airtm
  $html = file_get_html("http://www.airtmrates.com/");
  $result = $html->find("#table1 tr:nth-child(137) td:nth-child(5)");
  $bolivar = 0;
  
  foreach( $result as $b){
    $bolivar = $b->innertext . "<br>";
  }
  
  $dolarcucuta = explode(" ", $bolivar)[0];
  
  // Dolar dolartoday
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://s3.amazonaws.com/dolartoday/data.json");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $res = curl_exec ($ch);
  curl_close ($ch);

  $res = utf8_encode($res);
  $res = json_decode($res);
  $dolartoday = $res->USD->dolartoday;
  // echo $dolartoday . "<br>";
  
  // Config date timezone
  $dtz = new DateTimeZone("America/Caracas");
  $now = new DateTime(date("Y-m-d"), $dtz);
  
  $time = time();
  $date =  date("Y-m-d (H:i:s)", $time);
  // $date = $now->format("Y-m-d");
  
  // Promedio
  $prom = ($dolarcucuta + $dolartoday) / 2;
  
  // Sql
  $bd->query("INSERT INTO registros (dolar_cucuta, dolar_today, prom, date) VALUES ('$dolarcucuta', '$dolartoday', '$prom', '$date')");
?>