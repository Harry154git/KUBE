<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// --- RUTE PUBLIK (Bisa diakses tanpa login) ---
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->match(['get', 'post'], 'register', 'AuthController::register');

// Rute untuk pencarian produk
$routes->get('/search', 'SearchController::search');


// --- RUTE TERPROTEKSI (Harus login untuk akses) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/home', 'HomeController::index');
    
    // Rute untuk Produk
    $routes->get('product/(:num)', 'ProductController::detail/$1');

    // Rute untuk Keranjang
    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add', 'CartController::add');
    $routes->post('cart/update', 'CartController::update');
    $routes->get('cart/remove/(:num)', 'CartController::remove/$1');

    // Rute placeholder untuk checkout
    $routes->get('checkout', 'CheckoutController::index');

    // Rute untuk Profil & Pengaturan Pengguna
    $routes->get('profile', 'UserController::profile');
    $routes->match(['get', 'post'], 'settings', 'UserController::settings');

    // --- RUTE PENJUAL ---
    $routes->group('seller', function($routes) {
        // Halaman aktivasi
        $routes->match(['get', 'post'], 'activate', 'SellerController::activate', ['as' => 'seller.activate']);
        
        // Halaman setelah menjadi penjual
        $routes->get('dashboard', 'SellerController::dashboard', ['as' => 'seller.dashboard']);
        $routes->get('orders', 'SellerController::orders', ['as' => 'seller.orders']);
        $routes->post('orders/update-status', 'SellerController::updateOrderStatus', ['as' => 'seller.updateOrderStatus']);
        $routes->match(['get', 'post'], 'settings', 'SellerController::settings', ['as' => 'seller.settings']);

        // --- RUTE BARU UNTUK MANAJEMEN PRODUK PENJUAL ---
        $routes->get('products', 'SellerController::products', ['as' => 'seller.products']);
        $routes->get('products/add', 'ProductController::add', ['as' => 'seller.products.add']);
        $routes->post('products/create', 'ProductController::create', ['as' => 'seller.products.create']);
        $routes->get('products/edit/(:num)', 'ProductController::edit/$1', ['as' => 'seller.products.edit']);
        $routes->post('products/update/(:num)', 'ProductController::update/$1', ['as' => 'seller.products.update']);
        $routes->get('products/delete/(:num)', 'ProductController::delete/$1', ['as' => 'seller.products.delete']);
    });
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}