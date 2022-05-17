<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('/login', 'PengurusApotekController@login');
    $router->group(["middleware" => "auth"], function () use ($router) {
        $router->group(['prefix' => 'karyawan'], function () use ($router) {
            $router->post('/', 'PengurusApotekController@createKaryawan');
            $router->get('/profil', 'PengurusApotekController@profil');
            $router->put('/profil', 'PengurusApotekController@updateProfil');
            $router->delete('/{username}', 'PengurusApotekController@deleteKaryawan');
            $router->get('/', 'PengurusApotekController@getKaryawan');
        });
        $router->group(['prefix' => 'pelanggan'], function () use ($router) {
            $router->get('/', 'PelangganController@getPelanggan');
            $router->get('/{id}', 'PelangganController@getPelangganById');
            $router->post('/', 'PelangganController@create');
            $router->put('/{id}', 'PelangganController@update');
            $router->delete('/{id}', 'PelangganController@delete');
        });
        $router->group(['prefix' => 'transaksi'], function () use ($router) {
            $router->get('/', 'TransaksiController@getTransaksi');
            $router->get('/{id}', 'TransaksiController@getTransaksiById');
            $router->post('/{id}', 'TransaksiController@create');
            $router->put('/{id}', 'TransaksiController@update');
            $router->delete('/{id}', 'TransaksiController@delete');
        });
    });
});
