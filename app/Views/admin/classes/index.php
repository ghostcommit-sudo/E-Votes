<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Classes</h5>
        <a href="<?= base_url('admin-system/classes/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Class
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="classesTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Total Students</th>
                        <th>Students Voted</th>
                        <th>Voting Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?= $class['name'] ?></td>
                            <td><?= $class['total_students'] ?></td>
                            <td><?= $class['students_voted'] ?></td>
                            <td>
                                <?php 
                                    $percentage = $class['total_students'] > 0 
                                        ? ($class['students_voted'] / $class['total_students']) * 100 
                                        : 0;
                                ?>
                                <div class="progress">
                                    <div class="progress-bar" 
                                         role="progressbar" 
                                         style="width: <?= $percentage ?>%"
                                         aria-valuenow="<?= $percentage ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?= number_format($percentage, 1) ?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="<?= base_url('admin-system/classes/edit/' . $class['id']) ?>" 
                                   class="btn btn-sm btn-info me-1" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete('<?= base_url('admin-system/classes/delete/' . $class['id']) ?>')"
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
        initializeDataTable('#classesTable');
    });
</script>
<?= $this->endSection() ?>