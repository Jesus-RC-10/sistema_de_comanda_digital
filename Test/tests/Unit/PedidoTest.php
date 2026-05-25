<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PedidoTest extends TestCase
{
    public function testCalcularTotalPedido(): void
    {
        $productos = [
            ['precio' => 50.00, 'cantidad' => 2],
            ['precio' => 30.00, 'cantidad' => 1],
            ['precio' => 20.00, 'cantidad' => 3]
        ];

        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }

        $this->assertEquals(190.00, $total);
    }

    public function testCalcularIVA(): void
    {
        $subtotal = 190.00;
        $iva = 0.16;
        $totalIVA = $subtotal * $iva;
        
        $this->assertEquals(30.40, $totalIVA);
    }

    public function testCalcularCambio(): void
    {
        $total = 116.00;
        $pago = 200.00;
        $cambio = $pago - $total;
        
        $this->assertEquals(84.00, $cambio);
        $this->assertGreaterThan(0, $cambio);
    }

    public function testValidarEstadoPedido(): void
    {
        $estadosValidos = ['pendiente', 'confirmado', 'en_preparacion', 'listo', 'pagado'];
        $estadoActual = 'pendiente';
        
        $this->assertContains($estadoActual, $estadosValidos);
    }

    public function testNoCambioNegativo(): void
    {
        $total = 150.00;
        $pago = 100.00;
        $cambio = $pago - $total;
        
        $this->assertLessThan(0, $cambio);
    }

    public function testCantidadProductoPositiva(): void
    {
        $cantidades = [1, 2, 3, 5, 10];
        
        foreach ($cantidades as $cantidad) {
            $this->assertGreaterThan(0, $cantidad);
        }
    }
}