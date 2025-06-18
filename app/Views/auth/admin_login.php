<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="login-container">
        <h2 class="text-center mb-4">Admin Login</h2>
        
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->get('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->get('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    <?php foreach (session()->get('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin-system/login') ?>" method="post" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control <?= session()->has('errors.username') ? 'is-invalid' : '' ?>" 
                       id="username" name="username" value="<?= old('username') ?>" required>
                <?php if (session()->has('errors.username')): ?>
                    <div class="invalid-feedback">
                        <?= session()->get('errors.username') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control <?= session()->has('errors.password') ? 'is-invalid' : '' ?>" 
                       id="password" name="password" required>
                <?php if (session()->has('errors.password')): ?>
                    <div class="invalid-feedback">
                        <?= session()->get('errors.password') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted">Are you a student? <a href="<?= base_url('login') ?>">Login here</a></p>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 