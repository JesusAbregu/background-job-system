<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class JobController extends Controller
{
    /**
     * Mostrar los registros de trabajos en segundo plano.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $logFilePath = storage_path('logs/background_jobs.log');

        // Verificar si el archivo de log existe
        if (file_exists($logFilePath)) {
            $logs = file($logFilePath);

            // Procesar cada línea para extraer la fecha y el mensaje del log
            $logs = array_map(function ($log) {
                preg_match('/(\[.*?\]) (.*)/', $log, $matches);

                return [
                    'date' => $matches[1] ?? 'Desconocida',  // Extraer la fecha
                    'message' => $matches[2] ?? 'Sin mensaje'  // Extraer el mensaje
                ];
            }, $logs);
        } else {
            $logs = [];  // Si el archivo no existe, devolver un array vacío
        }

        return view('admin.jobs.index', compact('logs'));
    }
}
