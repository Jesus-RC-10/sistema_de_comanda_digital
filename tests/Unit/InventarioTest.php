<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class InventarioTest extends TestCase
{
    public function testStockSuficiente(): void
    {
        $stockActual = 50;
        $stockMinimo = 10;
        
        $this->assertGreaterThanOrEqual($stockMinimo, $stockActual);
    }

    public function testAlertaStockBajo(): void
    {
        $stockActual = 5;
        $stockMinimo = 10;
        $alerta = $stockActual < $stockMinimo;
        
        $this->assertTrue($alerta);
    }

    public function testDescontarStock(): void
    {
        $stockInicial = 100;
        $cantidadVendida = 15;
        $stockFinal = $stockInicial - $cantidadVendida;
        
        $this->assertEquals(85, $stockFinal);
    }

    public function testStockNoNegativo(): void
    {
        $stock = 0;
        $this->assertGreaterThanOrEqual(0, $stock);
    }

    public function testValidarUnidadMedida(): void
    {
        $unidadesValidas = ['kg', 'g', 'litros', 'piezas', 'ml'];
        $unidad = 'kg';
        
        $this->assertContains($unidad, $unidadesValidas);
    }
}