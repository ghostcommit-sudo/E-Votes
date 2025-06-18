<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="login-container">
        <h2 class="text-center mb-4">Complete Your Information</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if ($validation->getErrors()): ?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach ($validation->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Debug Info -->
        <?php if (ENVIRONMENT !== 'production'): ?>
            <div class="alert alert-info">
                <strong>Debug Info:</strong><br>
                Form Action: <?= base_url('auth/student-info') ?><br>
                CSRF Token: <?= csrf_hash() ?><br>
                Google Data: <?= json_encode($googleData) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/student-info') ?>" method="post" id="studentInfoForm" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="nis" class="form-label">NIS (Student ID)</label>
                <input type="text" class="form-control <?= ($validation->hasError('nis')) ? 'is-invalid' : '' ?>" 
                       id="nis" name="nis" value="<?= old('nis') ?>" required>
                <div class="invalid-feedback">
                    <?= $validation->getError('nis') ?: 'Please enter your NIS.' ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="class_id" class="form-label">Class</label>
                <select class="form-select <?= ($validation->hasError('class_id')) ? 'is-invalid' : '' ?>" 
                        id="class_id" name="class_id" required>
                    <option value="">Select your class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= (old('class_id') == $class['id']) ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('class_id') ?: 'Please select your class.' ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Continue</button>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation script
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