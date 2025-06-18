<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Election Periods</h5>
        <a href="<?= base_url('admin-system/periods/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Period
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="periodsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Total Votes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periods as $period): ?>
                        <tr>
                            <td><?= $period['name'] ?></td>
                            <td><?= date('d M Y', strtotime($period['start_date'])) ?></td>
                            <td><?= date('d M Y', strtotime($period['end_date'])) ?></td>
                            <td>
                                <?php
                                    $now = time();
                                    $start = strtotime($period['start_date']);
                                    $end = strtotime($period['end_date']);
                                    
                                    if ($now < $start) {
                                        echo '<span class="badge bg-warning">Upcoming</span>';
                                    } elseif ($now > $end) {
                                        echo '<span class="badge bg-secondary">Ended</span>';
                                    } else {
                                        echo '<span class="badge bg-success">Active</span>';
                                    }
                                ?>
                            </td>
                            <td><?= $period['total_votes'] ?></td>
                            <td>
                                <a href="<?= base_url('admin-system/periods/edit/' . $period['id']) ?>" 
                                   class="btn btn-sm btn-info me-1" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete('<?= base_url('admin-system/periods/delete/' . $period['id']) ?>')"
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
        initializeDataTable('#periodsTable');
    });
</script>
<?= $this->endSection() ?> 