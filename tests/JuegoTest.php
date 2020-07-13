<?php

use \PHPUnit\Framework\TestCase;
use App\Juego;

/*
 * @covers TestJuego
 * 
 * @testdox Clase Juego
 */
class JuegoTest extends TestCase{

    public $juego;

    public function setUp(): void {
        $this->juego = new Juego();
        $this->juego->empezarJuego();
    }

    /*
     *  @testdox Comprueba la instancia de partida
     */
    public function testInstancia() {
        $this->assertInstanceOf(Juego::class, $this->juego);
    }

    public function testJuegoAcabado(){
        $this->juego->setResultado(true);
        $this->assertTrue($this->juego->juegoAcabado());
    }
}