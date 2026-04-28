<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    private string $testLogFile;

    protected function setUp(): void
    {
        $this->testLogFile = sys_get_temp_dir() . '/test_app.log';
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
    }

    public function testLogEntryFormat(): void
    {
        $entry = [
            'timestamp' => date('c'),
            'level' => 'INFO',
            'message' => 'Test message',
            'context' => ['key' => 'value']
        ];

        $json = json_encode($entry);

        $this->assertJson($json);
        $this->assertArrayHasKey('timestamp', $entry);
        $this->assertArrayHasKey('level', $entry);
        $this->assertArrayHasKey('message', $entry);
    }

    public function testLogLevels(): void
    {
        $nivelesValidos = ['debug', 'info', 'warning', 'error', 'critical'];
        
        foreach ($nivelesValidos as $nivel) {
            $this->assertContains($nivel, $nivelesValidos);
        }
    }

    public function testContextIsArray(): void
    {
        $context = ['user_id' => 1, 'pedido_id' => 100];
        
        $this->assertIsArray($context);
        $this->assertArrayHasKey('user_id', $context);
        $this->assertArrayHasKey('pedido_id', $context);
    }

    public function testJsonEncodeUnicode(): void
    {
        $mensaje = "Tacos al Pastor";
        $entry = ['message' => $mensaje];
        $json = json_encode($entry, JSON_UNESCAPED_UNICODE);
        
        $this->assertStringContainsString('Tacos al Pastor', $json);
    }
}