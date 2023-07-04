<?php

use App\Http\Livewire\Rtl;

use Illuminate\Http\Request;
use App\Http\Livewire\Tables;
use App\Http\Livewire\Billing;
use App\Http\Livewire\Profile;
use App\Http\Livewire\TestWire;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\StaticSignIn;
use App\Http\Livewire\StaticSignUp;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\VirtualReality;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Item\Index as Item;
use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\Category\Index as Category;
use romanzipp\QueueMonitor\Services\QueueMonitor;

use App\Http\Livewire\LaravelExamples\UserProfile;
use App\Http\Livewire\LaravelExamples\UserManagement;
use App\Http\Livewire\ImportItems\Index as ImportItems;
use App\Http\Livewire\ImportItems\Import as ImportExcel;
use App\Http\Livewire\ImportItems\ImportWire as ImportWire;
use App\Http\Livewire\ItemInventory\Index as ItemInventory;



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
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');

    Route::get('/login/forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password/{id}', ResetPassword::class)->name('reset-password')->middleware('signed');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/dashboard1', TestWire::class)->name('dashboard1');
    Route::get('/billing', Billing::class)->name('billing');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/tables', Tables::class)->name('tables');
    Route::get('/static-sign-in', StaticSignIn::class)->name('sign-in');
    Route::get('/static-sign-up', StaticSignUp::class)->name('static-sign-up');
    Route::get('/rtl', Rtl::class)->name('rtl');
    Route::get('/virtual-reality', VirtualReality::class)->name('virtual-reality');
    Route::get('/user-profile', UserProfile::class)->name('user-profile');
    Route::get('/user-management', UserManagement::class)->name('user-management');

    // new routes for assets management
    Route::get('/categories', Category::class)->name('categories');
    Route::get('/items', Item::class)->name('items');
    Route::get('/inventory', ItemInventory::class)->name('inventory');
    Route::get('/import', ImportItems::class)->name('import');
    Route::get('/import-excel', ImportExcel::class)->name('import-excel');
    Route::get('/import-excel-job', ImportWire::class)->name('import-excel-job');


    // Route::prefix('jobs')->group(function () {
    //     Route::queueMonitor();
    // });
    // Route::prefix('jobs')->group(function () {
    //     QueueMonitor::routes();
    // });

    
    
});
