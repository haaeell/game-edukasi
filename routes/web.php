<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AccountSettingsController as AdminAccountSettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GameCardController as AdminGameCardController;
use App\Http\Controllers\Admin\GameCardSetController as AdminGameCardSetController;
use App\Http\Controllers\Admin\PesertaController as AdminPesertaController;
use App\Http\Controllers\Admin\RoomReportController as AdminRoomReportController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Game\RoomAccessController;
use App\Http\Controllers\Game\RoomInteractionController;
use App\Http\Controllers\User\ArticleController as UserArticleController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\GameRoomController as UserGameRoomController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\VideoController as UserVideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard');
    }

    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function (): void {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/settings/account', [AdminAccountSettingsController::class, 'edit'])->name('settings.account.edit');
        Route::put('/settings/account', [AdminAccountSettingsController::class, 'update'])->name('settings.account.update');
        Route::resource('articles', AdminArticleController::class)->except('show');
        Route::resource('videos', AdminVideoController::class)->except('show');
        Route::resource('game-card-sets', AdminGameCardSetController::class);
        Route::get('game-card-sets/{gameCardSet}/cards/create', [AdminGameCardController::class, 'create'])->name('game-cards.create');
        Route::post('game-card-sets/{gameCardSet}/cards', [AdminGameCardController::class, 'store'])->name('game-cards.store');
        Route::get('game-cards/{card}/edit', [AdminGameCardController::class, 'edit'])->name('game-cards.edit');
        Route::put('game-cards/{card}', [AdminGameCardController::class, 'update'])->name('game-cards.update');
        Route::delete('game-cards/{card}', [AdminGameCardController::class, 'destroy'])->name('game-cards.destroy');
        Route::post('game-cards/{card}/move-up', [AdminGameCardController::class, 'moveUp'])->name('game-cards.move-up');
        Route::post('game-cards/{card}/move-down', [AdminGameCardController::class, 'moveDown'])->name('game-cards.move-down');
        Route::get('room-reports', [AdminRoomReportController::class, 'index'])->name('room-reports.index');
        Route::post('room-reports/{gameRoom}/end', [AdminRoomReportController::class, 'end'])->name('room-reports.end');
        Route::delete('room-reports/{gameRoom}', [AdminRoomReportController::class, 'destroy'])->name('room-reports.destroy');
        Route::get('room-reports/{gameRoom}', [AdminRoomReportController::class, 'show'])->name('room-reports.show');
        Route::get('room-reports/{gameRoom}/pdf', [AdminRoomReportController::class, 'pdf'])->name('room-reports.pdf');
        Route::get('peserta', [AdminPesertaController::class, 'index'])->name('peserta.index');
        Route::get('peserta/{peserta}', [AdminPesertaController::class, 'show'])->name('peserta.show');
        Route::post('peserta/{peserta}/toggle-status', [AdminPesertaController::class, 'toggleStatus'])->name('peserta.toggle-status');
    });

Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function (): void {
        Route::get('/dashboard', UserDashboardController::class)->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/articles', [UserArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/{slug}', [UserArticleController::class, 'show'])->name('articles.show');
        Route::get('/videos', [UserVideoController::class, 'index'])->name('videos.index');
        Route::get('/videos/{slug}', [UserVideoController::class, 'show'])->name('videos.show');
        Route::get('/game', [UserGameRoomController::class, 'index'])->name('game.index');
        Route::get('/game/create-room', [UserGameRoomController::class, 'create'])->name('game.create');
        Route::post('/game/create-room', [UserGameRoomController::class, 'store'])->name('game.store');
    });

Route::get('/game/join', [RoomAccessController::class, 'showJoinForm'])->name('game.join');
Route::post('/game/join', [RoomAccessController::class, 'join'])->name('game.join.store');
Route::get('/game/invitation/{token}', [RoomAccessController::class, 'handleInvitation'])->name('game.invitation');
Route::get('/game/room/{code}', [RoomAccessController::class, 'showRoom'])->name('game.rooms.show');
Route::post('/game/room/{code}/start', [RoomAccessController::class, 'start'])->middleware('auth')->name('game.rooms.start');
Route::post('/game/room/{code}/anonymous-toggle', [RoomAccessController::class, 'toggleAnonymous'])->name('game.rooms.toggle-anonymous');
Route::post('/game/room/{code}/invite', [RoomInteractionController::class, 'invite'])->middleware('auth')->name('game.rooms.invite');
Route::post('/game/room/{code}/next-card', [RoomInteractionController::class, 'nextCard'])->middleware('auth')->name('game.rooms.next');
Route::post('/game/room/{code}/shuffle-card', [RoomInteractionController::class, 'shuffleCard'])->name('game.rooms.shuffle');
Route::post('/game/room/{code}/reset-deck', [RoomInteractionController::class, 'resetDeck'])->middleware('auth')->name('game.rooms.reset-deck');
Route::post('/game/room/{code}/previous-card', [RoomInteractionController::class, 'previousCard'])->middleware('auth')->name('game.rooms.previous');
Route::post('/game/room/{code}/end', [RoomInteractionController::class, 'end'])->middleware('auth')->name('game.rooms.end');
Route::get('/game/room/{code}/status', [RoomInteractionController::class, 'status'])->name('game.rooms.status');
Route::get('/game/room/{code}/participants', [RoomInteractionController::class, 'participants'])->name('game.rooms.participants');
Route::get('/game/room/{code}/messages', [RoomInteractionController::class, 'messages'])->name('game.rooms.messages');
Route::post('/game/room/{code}/messages', [RoomInteractionController::class, 'sendMessage'])->name('game.rooms.messages.store');
Route::post('/game/room/{code}/feedback', [RoomInteractionController::class, 'submitFeedback'])->name('game.rooms.feedback');
