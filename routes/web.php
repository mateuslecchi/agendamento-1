<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(__('route.dashboard.uri'));
});

Route::get(__('route.dashboard.uri'), function () {
    return view('dashboard');
})->middleware(['auth'])->name(__('route.dashboard.uri'));

Route::get(__('route.dashboard.calendar.uri'), function () {
    return view('dashboard-calendar');
})->middleware(['auth'])->name(__('route.dashboard.calendar.uri'));

Route::get(__('route.groups.uri'), function () {
    return view('groups');
})->middleware(['auth'])->name(__('route.groups.uri'));

Route::get(__('route.users.uri'), function () {
    return view('users');
})->middleware(['auth'])->name(__('route.users.uri'));

Route::get(__('route.blocks.uri'), function () {
    return view('blocks');
})->middleware(['auth'])->name(__('route.blocks.uri'));

Route::get(__('route.environments.uri'), function () {
    return view('environments');
})->middleware(['auth'])->name(__('route.environments.uri'));


Route::get(__('route.schedules.uri'), function () {
    return view('schedules');
})->middleware(['auth'])->name(__('route.schedules.uri'));

require __DIR__ . '/auth.php';
