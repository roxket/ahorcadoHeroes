<?php

//iniciamos la sesion antes que nada
session_start();
//Revisamos si la sesion existe. Si existe, redirigimos a juego.php
if (isset($_SESSION['nombre'])) {
    header("location: ../controllers/juego.php");
}
//Si no existen datos en la sesion, la creamos.
else {
    if (isset($_POST['nombre'])) { // revisamos si se existen las variables en el post
        $_SESSION['nombre'] = $_POST['nombre']; // guardamos el usuario en la variable de sesion
        //redirigimos hacia el juego.php
        header("location: ../controllers/juego.php");
    } else {
        header("location: ../../index.php");
    }
}
?> 