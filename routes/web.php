<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\URPController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
})->name('home');

// Route::get('/', function () {
//     return redirect('/login');
// })->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(FileController::class)->group(function () {
        Route::get('file/{filename}', 'viewFile')->where('filename', '.*')->name('files.view');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::post('/upload-avatar',  'uploadAvatar')->name('upload.avatar');
        Route::post('/profile-update',  'update')->name('profile.update');
        // Route::get('/notification-settings', 'NotificationSettings')->name('notification.settings');
        // Route::post('/update-notification-settings/{id}', 'updateNotificationSettings')->name('update.notification.settings');
    });

    Route::controller(TodoController::class)->group(function () {
        // to store new todos
        Route::post('/todos', 'store')->name('todos.store')->middleware('can:add.todo');

        //to update existing todo
        Route::put('/todos/{todo}', 'update')->name('todos.update')->middleware('can:edit.todo');

        //to mark todo as complete
        Route::post('todos/{todo}/complete', 'markComplete')->name('todos.complete');

        //to mark todo as incomplete
        Route::post('/todos/{todo}/incomplete', 'markIncomplete')->name('todos.incomplete');

        //to show all the todo items
        Route::get('todos/all_todos', 'showAll')->name('todos.all_todos')->middleware('can:all.todo');

        Route::delete('todos/{id}', 'delete')->name('todos.delete')->middleware('can:delete.todo');
    });

    Route::controller(URPController::class)->group(function () {

        //Roles
        Route::get('get/roles', 'RetrieveRoles')->name('get.roles');
        Route::get('/roles', 'AllRoles')->name('all.roles')->middleware('can:all.roles');
        Route::get('/roles/add', 'AddRoles')->name('add.roles')->middleware('can:add.roles');
        Route::post('/roles/store', 'StoreRoles')->name('store.roles')->middleware('can:add.roles');
        Route::get('/roles/edit/{id}', 'EditRoles')->name('edit.roles')->middleware('can:edit.roles');
        Route::post('/roles/update', 'UpdateRoles')->name('update.roles')->middleware('can:edit.roles');
        Route::post('/roles/delete', 'DeleteRoles')->name('delete.roles')->middleware('can:delete.roles');

        //Roles in Permission
        Route::get('/roles/permission/edit/{id}', 'EditRolesPermission')->name('edit.roles.permission')->middleware('can:edit.rolesinpermission');
        Route::post('/roles/permission/update/{id}', 'UpdateRolesPermission')->name('update.roles.permission');

        //All Users
        Route::get('/all/users', 'AllUser')->name('all.users')->middleware('can:all.users');
        Route::get('/add/users', 'AddUser')->name('add.admin')->middleware('can:add.admin');
        Route::post('/store/users', 'StoreUser')->name('store.admin')->middleware('can:add.admin');
        Route::get('/edit/users/{id}', 'EditUser')->name('edit.admin')->middleware('can:edit.admin');
        Route::post('/update/users/{id}', 'UpdateUser')->name('update.admin')->middleware('can:edit.admin');
        Route::post('/delete/users', 'DeleteUser')->name('delete.admin')->middleware('can:delete.admin');

        Route::post('/update-allowed-devices', 'updateAllowedDevices')->name('update.allowed.devices')->middleware('can:edit.admin');

        Route::get('user/{id}/devices', 'getUserDevices')->name('user.devices')->middleware('can:edit.admin');
    });
});

require __DIR__ . '/auth.php';
