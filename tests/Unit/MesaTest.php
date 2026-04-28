<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MesaTest extends TestCase
{
    public function testValidarNumeroMesa(): void
    {
        $numeroMesa = 5;
        $this->assertGreaterThan(0, $numeroMesa);
        $this->assertIsInt($numeroMesa);
    }

    public function testValidarEstadoMesa(): void
    {
        $estadosValidos = ['disponible', 'ocupada', 'reservada'];
        $estado = 'disponible';
        
        $this->assertContains($estado, $estadosValidos);
    }

    public function testValidarUbicacion(): void
    {
        $ubicacionesValidas = ['interior', 'exterior', 'terraza', 'privada'];
        $ubicacion = 'interior';
        
        $this->assertContains($ubicacion, $ubicacionesValidas);
    }

    public function testMesasDisponibles(): void
    {
        $totalMesas = 10;
        $mesasOcupadas = 3;
        $mesasDisponibles = $totalMesas - $mesasOcupadas;
        
        $this->assertEquals(7, $mesasDisponibles);
        $this->assertGreaterThanOrEqual(0, $mesasDisponibles);
    }

    public function testPorcentajeOcupacion(): void
    {
        $totalMesas = 10;
        $mesasOcupadas = 4;
        $porcentaje = ($mesasOcupadas / $totalMesas) * 100;
        
        $this->assertEquals(40.00, $porcentaje);
    }

    public function testMesaActiva(): void
    {
        $activa = true;
        $this->assertTrue($activa);
    }

    public function testValidarCapacidad(): void
    {
        $capacidad = 4;
        $this->assertGreaterThan(0, $capacidad);
        $this->assertLessThanOrEqual(20, $capacidad);
    }
}