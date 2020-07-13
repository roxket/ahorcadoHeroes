<?php

/*
 * Modelo del juego
 */

namespace App;

class Juego {

    var $salud;           //int - salud del jugador de 0 a 100
    var $finalizado;      //bool - estado del juego, false: funcionando, true: finalizado
    var $puntuacion;      //int - puntuacion del jugador, acumulador
    var $resultado;       //bool - resultado final del juego, true: ganador
    

    public function empezarJuego() {
        $this->salud = 100;
        $this->finalizado = false;
        $this->puntuacion = 0;
        $this->resultado = false;
    }

    public function getSalud() {
        return $this->salud;
    }

    public function getFinalizado() {
        return $this->finalizado;
    }

    public function getPuntuacion() {
        return $this->puntuacion;
    }

    public function getResultado() {
        return $this->resultado;
    }

    public function setSalud($salud): void {
        $this->salud = $salud;
    }

    public function setFinalizado($finalizado): void {
        $this->finalizado = $finalizado;
    }

    public function setPuntuacion($puntuacion): void {
        $this->puntuacion = $puntuacion;
    }

    public function setResultado($resultado): void {
        $this->resultado = $resultado;
    }

    public function finalizarJuego() {
        $this->finalizado = true;
    }

    public function contarPuntuacion($puntos = 0) {
        return $this->puntuacion += $puntos;
    }

    public function contarSalud($pildoras = 0) {
        return ceil($this->salud += $pildoras);
    }

    public function juegoAcabado() {
        if ($this->resultado) {
            return true;
        } else if ($this->finalizado) {
            return true;
        } else if ($this->salud < 0) {
            return true;
        } else {
            return false;
        }
    }

    public function errorAlert($msg) {
        return "<div class=\"alert alert-danger text-center\">$msg</div>";
    }

    public function successAlert($msg) {
        return "<div class=\"alert alert-success text-center\">$msg</div>";
    }

}
