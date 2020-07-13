<?php

use \PHPUnit\Framework\TestCase;
use App\Partida;

/*
 * @covers TestPartida
 * 
 * @testdox Clase Partida
 */
class PartidaTest extends TestCase {

    public $partida;

    public function setUp(): void {
        $this->partida = new Partida();
        $this->partida->partida();
    }

    /*
     *  @testdox Comprueba la instancia de partida
     */
    public function testInstancia() {
        $this->assertInstanceOf(Partida::class, $this->partida);
    }
    
    /*
     *  @testdox Comprueba el metodo privado testSetIntentos
     */
    public function testEsLetra() {
        $this->partida->intentos = 1;
        $class = new \ReflectionClass(get_class($this->partida));
        $metodo = $class->getMethod('esLetra');
        $metodo->setAccessible(true);
        $strResult = $metodo->invokeArgs($this->partida, array("a"));
        $this->assertTrue($strResult);
    }
}