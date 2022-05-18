<?php

use App\Jobs\LogToSlack;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function (Request $request) {
    LogToSlack::dispatch('something-here', [
        'request' => [
            'ip' => $request->ip(),
            'user-agent' => $request->userAgent(),
        ]
    ]);
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/horizon-dashboard', function(){
    if ( ! Auth::user() ) {
        Auth::login(User::whereEmail('shegun.babs@gmail.com')->first());
    }

    return redirect()->to('/horizon');
});


require __DIR__.'/auth.php';
