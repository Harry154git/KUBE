<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login E-Commerce</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
        .login-container { background-color: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #555; }
        input { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.7rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button:hover { background-color: #0056b3; }
        .error { background-color: #f8d7da; color: #721c24; padding: 0.7rem; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 1rem; text-align: center;}
        /* Style untuk link register */
        .register-link { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; }
        .register-link a { color: #007bff; text-decoration: none; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <!-- Menambahkan notifikasi sukses dari registrasi -->
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 0.7rem; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 1rem; text-align: center;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <!-- LINK KE REGISTER DITAMBAHKAN DI SINI -->
        <div class="register-link">
            <p>Belum punya akun? <a href="/register">Register di sini</a></p>
        </div>
    </div>
</body>
</html>
