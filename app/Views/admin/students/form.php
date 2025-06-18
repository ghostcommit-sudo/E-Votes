<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?= isset($student) ? 'Edit' : 'Add' ?> Student</h5>
    </div>
    <div class="card-body">
        <form action="<?= isset($student) ? base_url('admin-system/students/update/' . $student['id']) : base_url('admin-system/students/store') ?>" 
              method="post" 
              class="needs-validation" 
              novalidate>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS (Student ID)</label>
                        <input type="text" 
                               class="form-control" 
                               id="nis" 
                               name="nis" 
                               value="<?= isset($student) ? $student['nis'] : '' ?>" 
                               required>
                        <div class="invalid-feedback">
                            Please enter the student's NIS.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               value="<?= isset($student) ? $student['name'] : '' ?>" 
                               required>
                        <div class="invalid-feedback">
                            Please enter the student's name.
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="<?= isset($student) ? $student['email'] : '' ?>" 
                               required>
                        <div class="invalid-feedback">
                            Please enter a valid email address.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select" 
                                id="class_id" 
                                name="class_id" 
                                required>
                            <option value="">Select a class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" 
                                        <?= (isset($student) && $student['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                    <?= $class['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Please select a class.
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="<?= base_url('admin-system/students') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= isset($student) ? 'Update' : 'Create' ?> Student
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?> 