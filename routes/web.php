<?php
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function(){
    Route::get('/',[FrontendController::class,'index'])->name('dashboard');
    Route::get('/time-logs',[FrontendController::class,'timeLogs'])->name('time-logs');
    Route::post('/time-logs/add',[FrontendController::class,'addTimeLog'])->name('time-logs.add');
    Route::get('/time-logs/all',[FrontendController::class,'allTimeLog'])->name('time-logs.all');
    Route::get('/project-summary',[FrontendController::class,'projectSummary'])->name('project-summary');
    Route::get('/project-summary/view/{id}',[FrontendController::class,'projectView'])->name('project-summary.view');
    Route::get('/project-summary/all',[FrontendController::class,'allProjectSummary'])->name('project-summary.all');
    Route::any('/logout',[AuthController::class,'logout'])->name('logout');

    Route::group(['middleware' => 'admin','prefix' => 'admin','as' => 'admin.'],function(){
        Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
        Route::get('/change-password',[DashboardController::class,'ChangePassword'])->name('change-password');
        Route::post('/update-password',[DashboardController::class,'updatePassword'])->name('update-password');

        Route::get('/users',[UserController::class,'index'])->name('users');
        Route::get('/users/all',[UserController::class,'all'])->name('users.all');
        Route::post('/users/add-update',[UserController::class,'createUpdate'])->name('users.add-update');
        Route::post('/users/status/{id}',[UserController::class,'updateStatus'])->name('users.status');
        Route::delete('/users/delete/{id}',[UserController::class,'destroy'])->name('users.destroy');

        Route::get('/activities',[ActivityController::class,'index'])->name('activities');
        Route::get('/activities/all',[ActivityController::class,'all'])->name('activities.all');
        Route::post('/activities/add-update',[ActivityController::class,'createUpdate'])->name('activities.add-update');
        Route::post('/activities/status/{id}',[ActivityController::class,'updateStatus'])->name('activities.status');
        Route::delete('/activities/delete/{id}',[ActivityController::class,'destroy'])->name('activities.destroy');

        Route::get('/clients',[ClientController::class,'index'])->name('clients');
        Route::get('/clients/all',[ClientController::class,'all'])->name('clients.all');
        Route::post('/clients/add-update',[ClientController::class,'createUpdate'])->name('clients.add-update');
        Route::post('/clients/status/{id}',[ClientController::class,'updateStatus'])->name('clients.status');
        Route::delete('/clients/delete/{id}',[ClientController::class,'destroy'])->name('clients.destroy');

        Route::get('/projects',[ProjectController::class,'index'])->name('projects');
        Route::get('/projects/all',[ProjectController::class,'all'])->name('projects.all');
        Route::post('/projects/add-update',[ProjectController::class,'createUpdate'])->name('projects.add-update');
        Route::post('/projects/status/{id}',[ProjectController::class,'updateStatus'])->name('projects.status');
        Route::delete('/projects/delete/{id}',[ProjectController::class,'destroy'])->name('projects.destroy');

        Route::get('/project-logs',[ProjectLogController::class,'index'])->name('project-logs');
        Route::get('/project-logs/all',[ProjectLogController::class,'all'])->name('project-logs.all');
        Route::get('/project-logs/export',[ProjectLogController::class,'exportLogs'])->name('project-logs.export');
        Route::post('/project-logs/update',[ProjectLogController::class,'update'])->name('project-logs.update');
        Route::delete('/project-logs/delete/{id}',[ProjectLogController::class,'destroy'])->name('project-logs.destroy');

        Route::get('/project-summary',[ProjectLogController::class,'projectSummary'])->name('project-summary');
        Route::get('/project-summary/view/{id}',[ProjectLogController::class,'viewProjectSummary'])->name('project-summary.view');
        Route::get('/project-summary/all',[ProjectLogController::class,'allProjectSummary'])->name('project-summary.all');

        Route::get('/employee-summary',[ProjectLogController::class,'employeeSummary'])->name('employee-summary');
        Route::get('/employee-summary/all',[ProjectLogController::class,'allEmployeeSummary'])->name('employee-summary.all');
        Route::get('/employee-summary/view/{userId}/{projectId}',[ProjectLogController::class,'viewEmployeeSummary'])->name('employee-summary.view');
    });
});
Route::middleware('guest')->group(function(){
    Route::get('login',[AuthController::class,'create'])->name('login');
    Route::post('login',[AuthController::class,'store']);
});