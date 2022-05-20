<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Scrape\ScrapeController;

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
    return view('home');
});

Route::group(['prefix' => 'scraping', 'controller' => ScrapeController::class], function() {
    Route::get('/', 'scraping')->name('scraping');
    Route::get('/clear', 'clearRate')->name('scraping.clear');
});
