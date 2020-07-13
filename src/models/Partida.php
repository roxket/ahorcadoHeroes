<?php

/*
 * Modelo de la partida
 */

namespace App;

require_once __DIR__ . "\Juego.php";

class Partida extends Juego {

    var $intentos;                          //int - intentos por palabras
    var $letrasIntentadas = array();        //array - letras intentadas
    var $heroeIndex;                        //int - index de la palabra seleccionada
    var $letrasEnLaPalabra = array();       //array - array de letras de la palabra
    var $listaDeHeroes = array();           //array - lista de heroes
    var $alfabeto = array(//array - todas las letras del alfabeto
        "a", "b", "c", "d", "e", "f", "g", "h",
        "i", "j", "k", "l", "m", "n", "o", "p",
        "q", "r", "s", "t", "u", "v", "w", "x",
        "y", "z");
    var $simbolos = array(//array - simbolos en las palabras
        " ", "\,", "\'", "\"", "\/", "\\", "\[",
        "\]", "\+", "\-", "\_", "\=", "\!", "\~",
        "\~", "\@", "\.", "\?", "\$", "\#", "\%",
        "\^", "\&", "\*", "\(", "\)", "\{", "\}",
        "\|", "\:", "\;");

    /*
     * Constructor por defecto
     */

    function __construct() {
        
    }

    public function partida() {
        /*
         * instanciamos la clase padre y recogemos sus atributos y metodos
         */
        Juego::empezarJuego();
    }

    /*
     * establecer los intentos por defecto
     */

    private function setIntentos($chances = 0) {
        $this->intentos += $chances;
    }

    /*
     * Cargamos el archivo con los heroes a adivinar
     */

    private function cargarHeroes($archivoHeroes = "../assets/heroes.txt") {
        if (file_exists($archivoHeroes)) {
            $archivo = fopen($archivoHeroes, "r");
            while ($heroe = fscanf($archivo, "%s %s %s %s %s %s %s %s %s %s\n")) {
                $frase = "";
                if (is_string($heroe[0])) {
                    foreach ($heroe as $valor) {
                        $frase .= $valor . " ";
                        array_push($this->listaDeHeroes, trim($frase));
                    }
                }
            }
        }
    }

    /*
     * Escoger un heroe al azar
     */

    private function setHeroe() {
        //si la lista de heroes está vacía, cargamos los heroes en la lista
        if (empty($this->listaDeHeroes)) {
            $this->cargarHeroes();
        }
        //reseteamos la palabra si existiera
        if (!empty($this->listaDeHeroes)) {
            $this->heroeIndex = rand(0, count($this->listaDeHeroes) - 1);
        }
        //convertimos el string en array
        $this->heroeToArray();
    }

    /*
     * Convertimos el nombre del heroe (string) en array
     */

    private function heroeToArray() {
        $this->letrasEnLaPalabra = array();
        for ($i = 0; $i < strlen($this->listaDeHeroes[$this->heroeIndex]); $i++) {
            array_push($this->letrasEnLaPalabra, strtolower($this->listaDeHeroes[$this->heroeIndex][$i]));
        }
    }

    /*
     * Comprabar si el valor es una letra
     */

    private function esLetra($letra) {
        if (in_array($letra, $this->alfabeto)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Comprobar si la letra intentada se encuentra en el heroe seleccionado
     */

    private function adivinarLetra($letra) {

        if ($this->juegoAcabado()) {
            return;
        }
        if (!is_string($letra) || strlen($letra) != 1 || !$this->esLetra($letra)) {
            return $this->errorAlert("Oops! Por favor introduce una letra.");
        }
        //comprobar si la letra ya fue intentada previamente
        if (in_array($letra, $this->letrasIntentadas)) {
            return $this->errorAlert("Oops! Ya has intentado con esa letra.");
        }
        //permitir solo letras minusculas
        $letra = strtolower($letra);

        //si el heroe tiene la letra introducida
        if (!(strpos(strtolower($this->listaDeHeroes[$this->heroeIndex]), $letra) === false)) {
            //incrementar la puntucion en relacion al total de intentos
            if ($this->salud > (100 / ceil($this->intentos / 5))) {
                $this->contarPuntuacion(5);
            } else if ($this->salud > (100 / ceil($this->intentos / 4))) {
                $this->contarPuntuacion(4);
            } else if ($this->salud > (100 / ceil($this->intentos / 3))) {
                $this->contarPuntuacion(3);
            } else if ($this->salud > (100 / ceil($this->intentos / 2))) {
                $this->contarPuntuacion(2);
            } else {
                $this->contarPuntuacion(1);
            }

            //añadimos la letra al array de letras
            array_push($this->letrasIntentadas, $letra);

            //si se han encontrado todas las letras de la palabra
            if (implode(array_intersect($this->letrasEnLaPalabra, $this->letrasIntentadas), "") ==
                    str_replace($this->simbolos, "", strtolower($this->listaDeHeroes[$this->heroeIndex]))) {
                $this->finalizado = true;
            } else {
                return $this->successAlert("Has acertado!");
            }
        } else { //la palabra no contiene la letra
            //reducir salud
            $this->contarSalud(ceil(100 / $this->intentos) * -1);

            //añadir la letra al array de letras intentadas
            array_push($this->letrasIntentadas, $letra);

            if ($this->juegoAcabado()) {
                return;
            } else {
                return $this->errorAlert("La letra $letra no se encuentra en este heroe.");
            }
        }
    }

    /*
     * Iniciamos la partida
     */

    private function nuevaPartida($intentosMaximos = 5) {
        //Iniciamos el juego
        $this->partida(); //$this->empezarJuego();
        //limpiamos el array de letras intentadas
        $this->letrasIntentadas = array();

        //configurar los intentos maximos
        if ($intentosMaximos) {
            $this->setIntentos($intentosMaximos);
        }
        //se selecciona un heroe al azar
        $this->setHeroe();
    }

    public function jugarPartida() {
        //el jugador pulsa el boton empezar partida
        if (isset($_POST['nuevojuego']) || empty($this->listaDeHeroes)) {
            $this->nuevaPartida();
        }

        //el jugador intenta adivinar una letra
        if (!$this->juegoAcabado() && isset($_POST['letra'])) {
            echo $this->adivinarLetra($_POST['letra']);
        }

        //mostramos el juego
        $this->mostrarJuego();
    }

    /**
     * mostramos la interfaz del juego
     * */
    private function mostrarJuego() {
        //mientras el juego no se acaba
        if (!$this->juegoAcabado()) {
            echo "<div id=\"imagen\">" . $this->mostrarImagen() . "</div>
                  <div class=\"alert alert-light text-center mt-3 h3\" id=\"letraAdivinada\">" . $this->palabraResuelta() . "</div>
                  <div id=\"letraSeleccionada\">
                        <div class=\" text-center\">
                            <h3>Introduce una letra:</h3>
                        </div>
                   </div>
                   
                <div class=\"form-group mx-sm-3 mb-2\">
                   <input class=\"form-control col-xs-2 text-center\" type=\"text\" name=\"letra\" value=\"\" size=\"2\" maxlength=\"1\" />
                </div>
                <div class=\"col text-center\">
                   <input class=\"btn btn-primary mb-2\" type=\"submit\" name=\"submit\" value=\"Adivinar\" />
                 </div>";

            if (!empty($this->letrasIntentadas)) {
                echo "<div class=\"alert alert-primary text-center\" id=\"letraAdivinadas\">Letras adivinadas: " . implode($this->letrasIntentadas, ", ") . "</div>";
            }
        } else {
            //juego ganado
            if ($this->finalizado) {
                echo $this->successAlert("Felicidades! has ganado!.<br/>
                               Tu puntuación es: $this->puntuacion");
            } else if ($this->getSalud() < 0) {
                echo $this->errorAlert("Game Over!<br/>
                                Tu puntuación es: $this->puntuacion");

                echo "<div id=\"imagen\">" . $this->mostrarImagen() . "</div>";
            }
            echo "
                   <div class=\"col text-center\">
                    <div id=\"empezarJuego\"><input class=\"btn btn-danger\" type=\"submit\" name=\"nuevojuego\" value=\"Jugar de nuevo\" /></div>
                   </div>
                 ";
        }
    }

    /*
     * mostramos la imagen del estado de la partida
     */

    private function mostrarImagen() {
        $cont = 1;

        for ($i = 100; $i >= 0; $i -= ceil(100 / $this->intentos)) {
            if ($this->getSalud() == $i) {
                if (file_exists("../assets/" . ($cont - 1) . ".jpg")) {
                    return "<img src=\"../assets/" . ($cont - 1) . ".jpg\" alt=\"ahorcado\" title=\"ahorcado\" class=\"rounded mx-auto d-block\"/>";
                } else {
                    return "ERROR: assets/" . ($cont - 1) . ".jpg no encontrado.";
                }
            }

            $cont++;
        }

        return "<img src=\"../assets/" . ($cont - 1) . ".jpg\" alt=\"ahorcado\" title=\"ahorcado\" />";
    }

    /**
     * mostramos la parte de la palabra resuelta
     * */
    private function palabraResuelta() {

        $acumLetras = "";

        for ($i = 0; $i < count($this->letrasEnLaPalabra); $i++) {
            $encontrada = false;

            foreach ($this->letrasIntentadas as $letra) {
                if ($letra == $this->letrasEnLaPalabra[$i]) {
                    $acumLetras .= $this->letrasEnLaPalabra[$i];
                    $encontrada = true;
                }
            }

            if (!$encontrada && $this->esLetra($this->letrasEnLaPalabra[$i])) {
                $acumLetras .= "_ ";
            } else if (!$encontrada) {
                if ($this->letrasEnLaPalabra[$i] == " ") {
                    $acumLetras .= "&nbsp;&nbsp;&nbsp;";
                } else {
                    $acumLetras .= $this->letrasEnLaPalabra[$i];
                }
            }
        }

        return $acumLetras;
    }

}
