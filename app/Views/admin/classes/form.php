<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?= isset($class) ? 'Edit' : 'Add' ?> Class</h5>
    </div>
    <div class="card-body">
        <form action="<?= isset($class) ? base_url('admin-system/classes/update/' . $class['id']) : base_url('admin-system/classes/store') ?>" 
              method="post" 
              class="needs-validation" 
              novalidate>
            
            <div class="mb-3">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" 
                       class="form-control" 
                       id="name" 
                       name="name" 
                       value="<?= isset($class) ? $class['name'] : '' ?>" 
                       required>
                <div class="invalid-feedback">
                    Please enter the class name.
                </div>
                <div class="form-text">
                    Example: X IPA 1, XI IPS 2, etc.
                </div>
            </div>

            <div class="text-end">
                <a href="<?= base_url('admin-system/classes') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= isset($class) ? 'Update' : 'Create' ?> Class
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?> 