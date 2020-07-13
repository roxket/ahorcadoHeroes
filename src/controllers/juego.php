<?php

namespace App;

require "../../vendor/autoload.php";

use App\Partida;

//iniciamos la sesion para guardar datos
session_start();

//si el juego no ha comenzado, cargamos una partida
if (!isset($_SESSION['Juego']['Partida'])) {
    $_SESSION['Juego']['Partida'] = new Partida();
}
?>

<html>
    <head>
        <title>Ahorcado</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid">
            <div id="content" style="background:transparent !important" class="jumbotron">
            <form class="form-group mb-2" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <h2 class="display-6 text-center">hola <?php print_r($_SESSION['nombre']); ?>, vamos a jugar!</h2>
                <?php
                $_SESSION['Juego']['Partida']->jugarPartida();
                ?>
            </form>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>