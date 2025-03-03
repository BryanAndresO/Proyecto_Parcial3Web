<?php
session_start();

echo $_GET['tipo'];

if ($_GET['tipo']=="Vehiculo"){
    $_SESSION['BOTON'] = $_GET['tipo'];
    $_SESSION['BOTON'] = 9;
}else{
    $_SESSION['BOTON'] = $_GET['tipo'];
    $_SESSION['BOTON'] = 6;
}

header("Location: usuario/index.php");
exit();