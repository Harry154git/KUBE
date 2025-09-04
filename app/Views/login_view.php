<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Purun E-Commerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden; /* Mencegah scroll karena pseudo-element */
            position: relative;
            color: #fff;
        }

        /* --- (BARU) Efek Latar Belakang Gambar Blur --- */
        body::before {
            content: '';
            position: absolute;
            top: -10px; right: -10px; bottom: -10px; left: -10px;
            background-image: url('/assets/img/purun-market.jpg'); /* GANTI DENGAN PATH GAMBAR ANDA */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: blur(8px) brightness(0.7);
            z-index: -1;
        }

        /* --- (REVISI) Kartu Login/Registrasi (Efek Glassmorphism) --- */
        .auth-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(45, 61, 34, 0.3);
            padding: 2rem 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        /* (BARU) Placeholder untuk Logo */
        .logo-container {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo-container img {
            width: 80px; /* Sesuaikan ukuran logo */
            height: auto;
        }

        h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        /* --- (BARU) Grup input dengan ikon --- */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
        }

        input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem; /* Padding kiri untuk ikon */
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            border-color: #d4a048;
            box-shadow: 0 0 0 3px rgba(212, 160, 72, 0.4);
        }

        button {
            width: 100%;
            padding: 0.8rem;
            background-color: #702C2F;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #2D3D22;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        /* Notifikasi (Error & Success) */
        .error, .success {
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #fff;
            border: 1px solid;
        }

        .error {
            background-color: rgba(112, 44, 47, 0.5);
            border-color: rgba(255,255,255,0.3);
        }

        .success {
            background-color: rgba(45, 61, 34, 0.5);
            border-color: rgba(255,255,255,0.3);
        }

        .link-group {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .link-group a {
            color: #d4a048;
            text-decoration: none;
            font-weight: 600;
        }

        .link-group a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo-container">
            </div>
        <h2>Login</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="/login" method="post">
            <?= csrf_field() ?>
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="link-group">
            <p>Belum punya akun? <a href="/register">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>