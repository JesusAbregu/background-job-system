<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Log;

class BackgroundJobRunner
{
    /**
     * Ejecuta un trabajo en segundo plano.
     *
     * @param string $className La clase que contiene el método a ejecutar.
     * @param string $methodName El método a ejecutar.
     * @param array $parameters Los parámetros a pasar al método.
     * @return void
     * @throws Exception Si la clase o el método no son válidos.
     */
    public static function runBackgroundJob(string $className, string $methodName, array $parameters = [])
    {
        try {
            // Verificar que la clase exista
            if (!class_exists($className)) {
                throw new Exception("Clase no encontrada: " . $className);
            }

            // Instanciar la clase y verificar que el método exista
            $classInstance = new $className();
            if (!method_exists($classInstance, $methodName)) {
                throw new Exception("Método no encontrado: " . $methodName);
            }

            // Construir el comando para ejecutar en segundo plano
            $paramsString = implode(' ', array_map('escapeshellarg', $parameters));
            $command = "php artisan job:run $className $methodName $paramsString";

            // Ejecutar según el sistema operativo
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                pclose(popen("start /B " . $command, "r"));
            } else {
                exec($command . " > /dev/null 2>&1 &");
            }

            // Registrar en el log
            Log::info("Trabajo ejecutado en segundo plano: $className::$methodName con parámetros: " . json_encode($parameters));
        } catch (Exception $e) {
            // Manejo de errores
            Log::error("Error al ejecutar trabajo en segundo plano: " . $e->getMessage());
            throw $e;
        }
    }
}
