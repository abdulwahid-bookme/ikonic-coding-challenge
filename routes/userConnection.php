<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Suggestion;
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


Route::get('/suggestions', [App\Http\Controllers\NetworkController::class, 'getSuggestions'])->name('suggestions')->middleware('auth');

Route::get('/connect-request', [App\Http\Controllers\NetworkController::class, 'connectRequest'])->name('connect')->middleware('auth');

Route::get('/fresh-counts', [App\Http\Controllers\NetworkController::class, 'freshCounts'])->name('fresh')->middleware('auth');

Route::get('/get-sent-requests', [App\Http\Controllers\NetworkController::class, 'getSentRequests'])->middleware('auth');

Route::get('/withdraw-request', [App\Http\Controllers\NetworkController::class, 'withdrawRequest'])->middleware('auth');

Route::get('/received-request', [App\Http\Controllers\NetworkController::class, 'getReceivedRequests'])->middleware('auth');

Route::get('/accept-request', [App\Http\Controllers\NetworkController::class, 'acceptRequest'])->middleware('auth');

Route::get('/get-connections', [App\Http\Controllers\NetworkController::class, 'getConnections'])->middleware('auth');

Route::get('/common-connections/{id}', [App\Http\Controllers\NetworkController::class, 'commonConnections'])->middleware('auth');