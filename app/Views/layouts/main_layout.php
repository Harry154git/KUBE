<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul halaman akan dinamis, dengan judul default jika tidak di-set -->
    <title><?= $this->renderSection('title', 'My E-Commerce') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            min-height: 80vh;
        }
        .footer {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/home">My E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <!-- Link Navigasi Kiri -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/home"><i class="bi bi-house-door-fill me-1"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/cart"><i class="bi bi-cart-fill me-1"></i>Keranjang</a>
                    </li>
                </ul>

                <!-- Form Pencarian -->
                <form class="d-flex mx-auto" action="/search" method="get" style="width: 50%;">
                    <input class="form-control me-2" type="search" name="q" placeholder="Cari produk impianmu..." aria-label="Search">
                    <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                </form>

                <!-- Dropdown Pengguna Kanan -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <!-- Menampilkan nama pengguna dari session -->
                            <?= esc(session()->get('nama_lengkap')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person-lines-fill me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="/settings"><i class="bi bi-gear-fill me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- ===== AKHIR NAVBAR ===== -->


    <!-- ===== KONTEN UTAMA HALAMAN ===== -->
    <main class="main-content">
        <!-- Bagian ini akan diisi oleh konten dari view lain -->
        <?= $this->renderSection('content') ?>
    </main>
    <!-- ===== AKHIR KONTEN UTAMA ===== -->


    <!-- ===== FOOTER ===== -->
    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span>&copy; <?= date('Y') ?> My E-Commerce. All Rights Reserved.</span>
        </div>
    </footer>
    <!-- ===== AKHIR FOOTER ===== -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>