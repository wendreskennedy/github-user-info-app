<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/{username}', [UserController::class, 'getUser']);
Route::get('/{username}/followings', [UserController::class, 'getFollowings']);
