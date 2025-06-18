<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Students</h5>
        <div>
            <button class="btn btn-success me-2" onclick="exportToExcel('studentsTable', 'students_data.xlsx')">
                <i class="fas fa-file-excel me-2"></i>Export to Excel
            </button>
            <a href="<?= base_url('admin-system/students/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Student
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="studentsTable">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Class</th>
                        <th>Has Voted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= $student['nis'] ?></td>
                            <td><?= $student['name'] ?></td>
                            <td><?= $student['email'] ?></td>
                            <td><?= $student['class_name'] ?></td>
                            <td>
                                <?php if ($student['has_voted']): ?>
                                    <span class="badge bg-success">Yes</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin-system/students/edit/' . $student['id']) ?>" 
                                   class="btn btn-sm btn-info me-1" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete('<?= base_url('admin-system/students/delete/' . $student['id']) ?>')"
                                        data-bs-toggle="tooltip" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Initialize DataTable
    $(document).ready(function() {
        initializeDataTable('#studentsTable');
    });
</script>
<?= $this->endSection() ?> 