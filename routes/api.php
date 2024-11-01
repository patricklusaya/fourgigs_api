<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// SUPER ADMIN AUTHORITIES
// perform crud operations on roles
Route::resource('role', RoleController::class);


// PUBLIC ENDPOINTS
// signup user
Route::post('/signup', [AuthController::class, 'store']);
// signin
Route::post( '/signin', [AuthController::class, 'signin']);
// signin
Route::post( '/signout', [AuthController::class, 'signout'])->middleware('auth:sanctum');
// find info of a user
Route::get( '/users/{id}', [AuthController::class, 'findUser'])->middleware('auth:sanctum');

// view jobs
Route::get( '/jobs', [JobsController::class, 'index']);
Route::get( '/jobs/{id}', [JobsController::class, 'show']);


// PROTECTED  ENDPOINTS
// post jobs
Route::post( '/jobs', [JobsController::class, 'store'])->middleware('auth:sanctum');
// edit job post
Route::put( '/jobs/{id}', [JobsController::class, 'update'])->middleware('auth:sanctum');

// delete job
Route::delete( '/jobs/{id}', [JobsController::class, 'destroy'])->middleware('auth:sanctum');
// sign out
Route::post( '/signout', [AuthController::class, 'signout'])->middleware('auth:sanctum');

// application, 
Route::post( '/apply', [ApplicationController::class, 'store'])->middleware('auth:sanctum');

// respond to application,
Route::post( 'applications/{id}/status', [ApplicationController::class, 'changeStatus'])->middleware('auth:sanctum');

// get all applications for my posted jobs
Route::get( '/jobs/{id}/applications', [ApplicationController::class, 'myJobs'])->middleware('auth:sanctum');

// get all applications a seeker is in 
Route::get( 'applications/all', [ApplicationController::class, 'seekerApplications'])->middleware('auth:sanctum');

//get all jobs user applied 
Route::get( '/applications', [ApplicationController::class, 'showAppliedJobs'])->middleware('auth:sanctum');

//filter jobs
Route::post( '/jobs/filter', [JobsController::class, 'filterJobs'])->middleware('auth:sanctum');