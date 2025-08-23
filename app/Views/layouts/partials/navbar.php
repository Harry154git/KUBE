<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/home">My E-Commerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <!-- Left Navigation Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/home"><i class="bi bi-house-door-fill me-1"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/cart"><i class="bi bi-cart-fill me-1"></i>Cart</a>
                </li>
            </ul>

            <!-- Search Form -->
            <form class="d-flex mx-auto" action="/search" method="get" style="width: 50%;">
                <input class="form-control me-2" type="search" name="q" placeholder="Find your dream product..." aria-label="Search">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <!-- Right User Dropdown -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <!-- Display user's full name from the session -->
                            <?= esc(session()->get('full_name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person-lines-fill me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="/settings"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if(session()->get('is_seller')): ?>
                                <li><a class="dropdown-item" href="<?= route_to('seller.dashboard') ?>"><i class="bi bi-shop me-2"></i>My Store</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?= route_to('seller.activate') ?>"><i class="bi bi-shop-window me-2"></i>Open a Store</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="order/history"><i class="bi bi-clock-history me-2"></i>Order History</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
