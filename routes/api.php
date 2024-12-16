
<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\TaskController;
  
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
     
Route::middleware('auth:api')->group( function () {
    Route::resource('tasks', TaskController::class);
    Route::get('task-status-change/{id}',[TaskController::class,'changeStatus'])->name('task.status-change');
});
Route::apiResource('category',CategoryController::class);
Route::get('task-search',[TaskController::class,'taskSearch'])->name('task.search');
