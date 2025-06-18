<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?= isset($period) ? 'Edit' : 'Add' ?> Election Period</h5>
    </div>
    <div class="card-body">
        <form action="<?= isset($period) ? base_url('admin-system/periods/update/' . $period['id']) : base_url('admin-system/periods/store') ?>" 
              method="post" 
              class="needs-validation" 
              novalidate>
            
            <div class="mb-3">
                <label for="name" class="form-label">Period Name</label>
                <input type="text" 
                       class="form-control" 
                       id="name" 
                       name="name" 
                       value="<?= isset($period) ? $period['name'] : '' ?>" 
                       required>
                <div class="invalid-feedback">
                    Please enter the period name.
                </div>
                <div class="form-text">
                    Example: 2023/2024, Semester 1 2023, etc.
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="start_date" 
                               name="start_date" 
                               value="<?= isset($period) ? date('Y-m-d', strtotime($period['start_date'])) : '' ?>" 
                               required>
                        <div class="invalid-feedback">
                            Please select the start date.
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="end_date" 
                               name="end_date" 
                               value="<?= isset($period) ? date('Y-m-d', strtotime($period['end_date'])) : '' ?>" 
                               required>
                        <div class="invalid-feedback">
                            Please select the end date.
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="<?= base_url('admin-system/periods') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= isset($period) ? 'Update' : 'Create' ?> Period
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Validate end date is after start date
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(this.value);
        
        if (endDate <= startDate) {
            this.setCustomValidity('End date must be after start date');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
<?= $this->endSection() ?> 