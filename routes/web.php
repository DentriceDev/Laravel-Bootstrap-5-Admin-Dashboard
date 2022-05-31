<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/dashboard', function () {
    if (session('status')) {
        return redirect()->route('dashboard')->with('status', session('status'));
    }

    return redirect()->route('dashboard');
});

Auth::routes(['verify' => true]);

Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'verified']], function () {
    Route::get('dashboard', 'HomeController@index')->name('dashboard');
    // // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');
});

Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'Auth', 'middleware' => ['auth', 'verified', 'password.confirm']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ManageAccountController.php'))) {
        Route::get('account', 'ManageAccountController@edit')->name('account.edit');
        Route::post('password', 'ManageAccountController@update')->name('password.update');
        Route::post('profile', 'ManageAccountController@updateProfile')->name('account.updateProfile');
        Route::post('account/destroy', 'ManageAccountController@destroy')->name('account.destroyProfile');
    }
});

Route::post('notifications', '\App\Http\Livewire\NotificationsMenu@markNoticicationsAsRead')->name('notifications.markAsRead');
