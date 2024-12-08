<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobRunner;

class JobController extends Controller
{
    protected $jobRunner;

    public function __construct(JobRunner $jobRunner)
    {
        $this->jobRunner = $jobRunner;
    }

    public function executeJob(Request $request)
    {
        // Validación de entrada
        $validated = $request->validate([
            'class' => 'required|string|regex:/^[a-zA-Z0-9\\\\]+$/', // Solo permite clases con caracteres alfanuméricos y backslashes
            'method' => 'required|string|regex:/^[a-zA-Z0-9_]+$/', // Solo permite métodos con caracteres alfanuméricos y guiones bajos
            'params' => 'array',
        ]);

        // Clases permitidas para evitar ejecución de código malicioso
        $allowedClasses = ['App\\Jobs\\SomeJobClass', 'App\\Jobs\\AnotherJobClass']; // Define tus clases permitidas aquí
        if (!in_array($validated['class'], $allowedClasses)) {
            abort(403, 'Unauthorized class');
        }

        // Ejecución del trabajo en segundo plano
        $this->jobRunner->runBackgroundJob($validated['class'], $validated['method'], $validated['params']);

        return response()->json(['status' => 'Job is running in the background']);
    }
}
