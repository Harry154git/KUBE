<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- RUTE PUBLIK (Bisa diakses tanpa login) ---
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->match(['get', 'post'], 'register', 'AuthController::register');

// Rute untuk pencarian produk
// Pastikan controller ini benar, sebelumnya Anda menggunakan SearchController
$routes->get('/search', 'ProductController::search'); 


// --- RUTE TERPROTEKSI (Harus login untuk akses) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/home', 'HomeController::index');
    
    // Rute untuk Produk
    // Pastikan controller ini benar, sebelumnya Anda menggunakan SearchController
    $routes->get('product/(:num)', 'ProductController::detail/$1');

    // Rute untuk Keranjang
    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add', 'CartController::add');
    $routes->post('cart/update', 'CartController::update');
    $routes->get('cart/remove/(:num)', 'CartController::remove/$1');

    // Rute placeholder untuk checkout
    $routes->get('checkout', 'CheckoutController::index');

    // --- RUTE BARU UNTUK PROFIL & PENGATURAN ---
    $routes->get('profile', 'UserController::profile');
    $routes->match(['get', 'post'], 'settings', 'UserController::settings');
});