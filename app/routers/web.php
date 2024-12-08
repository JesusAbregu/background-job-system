use App\Http\Controllers\JobController;

Route::post('/run-job', [JobController::class, 'executeJob']);
