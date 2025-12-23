<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/post-login', [AuthController::class, 'postLogin'])->name('login_post');


Route::middleware(['auth', 'admin'])->group(function () {
  Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');
        Route::get('/logout', [AdminController::class, 'logout'])
            ->name('admin_logout');

});

/* Admin Module */
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

         // Contacts
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/list', [ContactController::class, 'list'])->name('contacts.list');

        Route::post('/contacts/merge-preview', [ContactController::class, 'mergePreview'])
            ->name('contacts.merge.preview');

        Route::post('/contacts/do-merge', [ContactController::class, 'doMerge'])
            ->name('contacts.merge.do');

        Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
        Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
        Route::post('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
        Route::get('/settings/edit/{setting}', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');
});
