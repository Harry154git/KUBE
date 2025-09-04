<nav class="navbar navbar-expand-lg navbar-purun sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="/home">
            <img src="/assets/img/logo-kube.png" alt="KUBE Logo" class="brand-logo me-2">
            <span class="brand-text">KUBE</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <form class="d-flex mx-auto search-bar-purun" action="/search" method="get">
                <input class="form-control" type="search" name="q" placeholder="Cari produk anyaman purun..." aria-label="Search">
                <button class="btn" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <ul class="navbar-nav ms-auto d-flex flex-row align-items-center gap-3">
                <li class="nav-item">
                    <a class="nav-link icon-link" href="/cart"><i class="bi bi-cart-fill"></i></a>
                </li>

                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="d-none d-lg-inline"><?= esc(session()->get('full_name')) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person-lines-fill me-2"></i>Profile</a></li>
                            <?php if(session()->get('is_seller')): ?>
                                <li><a class="dropdown-item" href="<?= route_to('seller.dashboard') ?>"><i class="bi bi-shop me-2"></i>Toko Saya</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?= route_to('seller.activate') ?>"><i class="bi bi-shop-window me-2"></i>Buka Toko</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/order/history"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>