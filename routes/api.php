<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\RoomController;
use App\Http\Controllers\api\RoomMemberController;
use App\Http\Controllers\api\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("auth")->group(function () {
    Route::post("/signup", [AuthController::class, "signup"]);
    Route::post("/login", [AuthController::class, "login"]);
});

Route::middleware("auth:api")
    ->prefix("rooms")
    ->group(function () {
        Route::get("/", [RoomController::class, "index"]);
        Route::get("/latest/chat/{id}", [
            RoomController::class,
            "getRoomLatestChat",
        ]);
        Route::post("/", [RoomController::class, "store"]);
        Route::put("/{id}", [RoomController::class, "update"]);
        Route::delete("/{id}", [RoomController::class, "destroy"]);
    });

Route::middleware("auth:api")
    ->prefix("room-members")
    ->group(function () {
        Route::put("/{id}", [RoomMemberController::class, "update"]);
    });

Route::middleware("auth:api")
    ->prefix("chats")
    ->group(function () {
        Route::post("/", [ChatController::class, "store"]);
        Route::post("/images", [ChatController::class, "storeImage"]);
    });
