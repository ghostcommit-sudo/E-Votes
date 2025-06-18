<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="login-container">
        <h2 class="text-center mb-4">Student Login</h2>
        
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->get('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->get('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <a href="<?= base_url('auth/google') ?>" class="btn btn-light border d-block">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" 
                     alt="Google Logo" style="height: 24px; margin-right: 8px;">
                Sign in with Google
            </a>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted">Are you an administrator? <a href="<?= base_url('admin-system/login') ?>">Login here</a></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 