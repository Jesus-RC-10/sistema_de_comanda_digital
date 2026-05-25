<?php

/**
 * Logger - Sistema de logging centralizado
 * 
 * Uso:
 *   Logger::info('Mensaje de información');
 *   Logger::error('Error occurred', ['user_id' => 1]);
 *   Logger::warning('Alerta', ['stock' => 5]);
 */

class Logger
{
    private static string $logFile = __DIR__ . '/../logs/app.log';
    private static string $errorLogFile = __DIR__ . '/../logs/error.log';

    public static function setLogFile(string $path): void
    {
        self::$logFile = $path;
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        $entry = [
            'timestamp' => date('c'),
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'CLI',
            'user_id' => $_SESSION['user_id'] ?? null
        ];

        $json = json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;

        file_put_contents(self::$logFile, $json, FILE_APPEND);

        if (in_array(strtolower($level), ['error', 'critical'])) {
            file_put_contents(self::$errorLogFile, $json, FILE_APPEND);
        }
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('info', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('warning', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        if (getenv('APP_DEBUG') === 'true') {
            self::log('debug', $message, $context);
        }
    }

    public static function logPedido(string $action, int $pedidoId, array $data = []): void
    {
        self::info("Pedido #$pedidoId: $action", array_merge(['pedido_id' => $pedidoId], $data));
    }

    public static function logAuth(string $action, ?int $userId = null): void
    {
        self::info("Auth: $action", ['user_id' => $userId, 'action' => $action]);
    }
}