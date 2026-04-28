<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ProductoTest extends TestCase
{
    public function testPrecioConIVA(): void
    {
        $precio = 100.00;
        $iva = 0.16;
        $precioConIVA = $precio * (1 + $iva);
        
        $this->assertEquals(116.00, $precioConIVA);
    }

    public function testValidarNombreProducto(): void
    {
        $nombre = "Tacos al Pastor";
        $this->assertNotEmpty($nombre);
        $this->assertIsString($nombre);
        $this->assertGreaterThan(0, strlen($nombre));
    }

    public function testValidarPrecioPositivo(): void
    {
        $precio = 50.00;
        $this->assertGreaterThan(0, $precio);
        $this->assertIsFloat($precio);
    }

    public function testStockNoNegativo(): void
    {
        $stock = 10;
        $this->assertGreaterThanOrEqual(0, $stock);
        $this->assertIsInt($stock);
    }

    public function testCalcularDescuento(): void
    {
        $precio = 100.00;
        $descuento = 10; // 10%
        $precioFinal = $precio - ($precio * $descuento / 100);
        
        $this->assertEquals(90.00, $precioFinal);
    }

    public function testValidarDescripcion(): void
    {
        $descripcion = "Deliciosos tacos con piña";
        $this->assertIsString($descripcion);
        $this->assertLessThanOrEqual(500, strlen($descripcion));
    }
}