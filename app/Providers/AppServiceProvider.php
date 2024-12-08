<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\JobRunner; // Asegúrate de que esta clase exista y esté correctamente ubicada.

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Registrar JobRunner como un singleton en el contenedor de servicios
        $this->app->singleton(JobRunner::class, function ($app) {
            return new JobRunner();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Inicializar servicios si es necesario
    }
}
use App\Http\Controllers\JobController;

Route::post('/execute-job', [JobController::class, 'executeJob']);
// Archivo: App/Services/JobRunner.php
use Illuminate\Support\Facades\Log;

public function runBackgroundJob(string $class, string $method, array $params = [])
{
    try {
        if (!class_exists($class) || !method_exists($class, $method)) {
            throw new \Exception("La clase o método especificado no existe.");
        }

        // Instanciar la clase y ejecutar el método
        $instance = app($class);
        call_user_func_array([$instance, $method], $params);

        Log::info("Trabajo ejecutado exitosamente: {$class}::{$method}", ['params' => $params]);
    } catch (\Throwable $e) {
        Log::error("Error al ejecutar el trabajo: {$e->getMessage()}", [
            'class' => $class,
            'method' => $method,
            'params' => $params,
            'stack' => $e->getTraceAsString(),
        ]);
    }

