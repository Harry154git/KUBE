<div class="auth-modal-overlay" id="authModal">
    <div class="auth-modal-container">
        <button class="auth-modal-close-btn" id="authModalCloseBtn">&times;</button>
        
        <!-- Login Form -->
        <div id="loginFormContainer" class="auth-form active">
            <h2>Login</h2>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            
            <form action="/login" method="post">
                <?= csrf_field() ?>
                <!-- Input tersembunyi untuk redirect kembali ke halaman asal -->
                <input type="hidden" name="redirect_url" id="redirectUrlLogin">
                
                <div class="input-group">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="link-group">
                <p>Belum punya akun? <a id="showRegister">Daftar di sini</a></p>
            </div>
        </div>

        <!-- Register Form -->
        <div id="registerFormContainer" class="auth-form">
            <h2>Buat Akun Baru</h2>
            <!-- Menampilkan error validasi dari controller -->
             <?php if (session()->getFlashdata('validation')): ?>
                <div class="error">
                    <?= session()->getFlashdata('validation')->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect_url" id="redirectUrlRegister">
                
                <div class="input-group">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" name="full_name" placeholder="Nama Lengkap" value="<?= old('full_name') ?>" required>
                </div>
                <div class="input-group">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" name="email" placeholder="Alamat Email" value="<?= old('email') ?>" required>
                </div>
                <div class="input-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password_confirm" placeholder="Konfirmasi Password" required>
                </div>
                <button type="submit">Register</button>
            </form>
             <div class="link-group">
                <p>Sudah punya akun? <a id="showLogin">Login di sini</a></p>
            </div>
        </div>

    </div>
</div>
