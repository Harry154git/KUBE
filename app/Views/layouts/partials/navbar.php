<!-- (BARU) Menggunakan struktur dan class dari desain AI -->
<header class="nav-bar">
    <!-- Logo dan Nama Brand -->
    <a href="/home" class="brand-link">
        <img class="brand-logo-img" alt="Kube Logo" src="/assets/img/logo-kube.png" />
        <div class="brand-text-wrapper">
            <h2 class="brand-text">KUBE</h2>
        </div>
    </a>

    <!-- Form Pencarian -->
    <div class="search-container">
        <form action="/search" method="get" class="search-form">
            <input class="search-input" placeholder="Cari Produk Mu" type="text" name="q" />
            <button type="submit" class="search-button">
                <img class="search-icon" alt="Search" src="https://api.iconify.design/material-symbols/search-rounded.svg?color=%23ffffff" />
            </button>
        </form>
    </div>

    <!-- Ikon dan Tombol Aksi Pengguna -->
    <div class="user-actions">
        <?php if (session()->get('isLoggedIn')): ?>
            <!-- Ikon Notifikasi dan Keranjang untuk user yang login -->
            <div class="icon-group">
                <a href="/notifications" class="icon-link">
                    <img class="action-icon" alt="Notifications" src="https://api.iconify.design/mingcute/notification-fill.svg?color=%23ffffff" />
                </a>
                <a href="/cart" class="icon-link">
                    <img class="action-icon" alt="Cart" src="https://api.iconify.design/mdi/cart.svg?color=%23ffffff" />
                </a>
            </div>
            
            <!-- Dropdown Profil Pengguna -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="user-name"><?= esc(session()->get('full_name')) ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                    <?php if(session()->get('is_seller')): ?>
                        <li><a class="dropdown-item" href="<?= route_to('seller.dashboard') ?>">Toko Saya</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="<?= route_to('seller.activate') ?>">Buka Toko</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="/order/history">Riwayat Pesanan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Tombol Login/Daftar untuk tamu -->
            <button class="auth-button requires-auth">
                <div class="auth-button-text">Login | Daftar</div>
            </button>
        <?php endif; ?>
    </div>
</header>
