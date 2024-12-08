<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\JobRunner; // Asegúrate de que esta clase exista y esté correctamente ubicada.

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
public function executeJob(Request $request)
{
    $validated = $request->validate([
        'class' => 'required|string|regex:/^[a-zA-Z0-9\\\\]+$/', // Solo permite clases con caracteres alfanuméricos y backslashes
        'method' => 'required|string|regex:/^[a-zA-Z0-9_]+$/', // Solo permite métodos con caracteres alfanuméricos y guiones bajos
        'params' => 'array',
    ]);

    // Restricción a clases y métodos permitidos
    $allowedClasses = ['App\\Jobs\\SomeJobClass', 'App\\Jobs\\AnotherJobClass']; // Define las clases permitidas
    if (!in_array($validated['class'], $allowedClasses)) {
        abort(403, 'Unauthorized class');
    }

    // Ejecución del trabajo
    $this->jobRunner->runBackgroundJob($validated['class'], $validated['method'], $validated['params']);
    
    return response()->json(['status' => 'Job is running in the background']);
}
 
