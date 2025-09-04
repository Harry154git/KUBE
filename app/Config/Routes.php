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

// --- PUBLIC ROUTES (Accessible without login) ---
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->match(['get', 'post'], 'register', 'AuthController::register');

// Route for product search
$routes->get('/search', 'SearchController::search');


// --- PROTECTED ROUTES (Must be logged in to access) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/home', 'HomeController::index');
    
    // Product Routes
    $routes->get('product/(:num)', 'ProductController::detail/$1');

    // Cart Routes
    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add', 'CartController::add');
    $routes->post('cart/update', 'CartController::update');
    $routes->get('cart/remove/(:num)', 'CartController::remove/$1');
    $routes->post('cart/remove-batch', 'CartController::removeBatch', ['as' => 'cart.remove_batch']);

    // User Profile & Settings Routes
    $routes->get('profile', 'UserController::profile');
    $routes->match(['get', 'post'], 'settings', 'UserController::settings');

    // --- NEW ROUTES FOR ORDER HISTORY & TRACKING ---
    $routes->get('order/history', 'OrderController::history'); // Mengubah 'order-history'
    $routes->get('order/track/(:segment)', 'OrderController::track/$1');
    $routes->post('order/confirm-payment', 'OrderController::confirmPayment', ['as' => 'order.confirm_payment']);
    $routes->post('order/receive', 'OrderController::receiveOrder', ['as' => 'order.receive']);
    // --- END OF NEW ROUTES ---

    // --- NEW ROUTES FOR ADDRESS MANAGEMENT ---
    $routes->get('addresses', 'AddressController::index');
    $routes->post('addresses/create', 'AddressController::create');
    $routes->post('addresses/update/(:num)', 'AddressController::update/$1');
    $routes->get('addresses/delete/(:num)', 'AddressController::delete/$1');
    $routes->get('addresses/set-primary/(:num)', 'AddressController::setPrimary/$1');
    // --- END OF ADDRESS MANAGEMENT ROUTES ---

    // --- NEW ROUTES FOR CHECKOUT ---
    $routes->get('checkout', 'CheckoutController::index');
    $routes->post('checkout/initiate', 'CheckoutController::initiate');
    $routes->post('checkout/process', 'CheckoutController::process');
    $routes->get('order/success/(:num)', 'CheckoutController::success/$1');
    // --- END OF CHECKOUT ROUTES ---

    // --- CHAT FEATURE ROUTES ---
    $routes->get('chat/conversations', 'ChatController::index');
    $routes->get('chat/messages/(:num)', 'ChatController::getMessages/$1');
    $routes->post('chat/send/(:num)', 'ChatController::sendMessage/$1');
    $routes->get('chat/start/store/(:num)', 'ChatController::startWithSeller/$1', ['as' => 'chat.start_with_seller']); // Mengubah 'toko'
    $routes->get('chat/order/(:num)', 'ChatController::startChatFromOrder/$1', ['as' => 'chat.from_order']);

    // Route baru untuk memulai chat dari halaman produk
    $routes->get('chat/ask/(:num)/(:num)', 'ChatController::startChatFromProduct/$1/$2', ['as' => 'chat.from_product']);
    // --- END OF CHAT ROUTES ---

    // --- SELLER ROUTES ---
    $routes->group('seller', function($routes) {
        // Activation page
        $routes->match(['get', 'post'], 'activate', 'SellerController::activate', ['as' => 'seller.activate']);
        
        // Pages after becoming a seller
        $routes->get('dashboard', 'SellerController::dashboard', ['as' => 'seller.dashboard']);
        $routes->get('orders', 'SellerController::orders', ['as' => 'seller.orders']);
        $routes->post('orders/update-status', 'SellerController::updateOrderStatus', ['as' => 'seller.updateOrderStatus']);
        $routes->match(['get', 'post'], 'settings', 'SellerController::settings', ['as' => 'seller.settings']);
        // (BARU) Route untuk melihat detail pesanan
        $routes->get('orders/detail/(:num)', 'SellerController::orderDetail/$1', ['as' => 'seller.orders.detail']);
        // (BARU) Route untuk memproses pengiriman pesanan
        $routes->post('orders/ship', 'SellerController::shipOrder', ['as' => 'seller.orders.ship']);
        // app/Config/Routes.php (di dalam grup seller)
        $routes->post('orders/cancel', 'SellerController::cancelOrder', ['as' => 'seller.orders.cancel']);

        // --- SELLER PRODUCT MANAGEMENT ROUTES ---
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