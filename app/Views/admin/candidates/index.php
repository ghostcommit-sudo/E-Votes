<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Candidates</h5>
        <a href="<?= base_url('admin-system/candidates/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Candidate
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="candidatesTable">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Vision</th>
                        <th>Mission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candidates as $candidate): ?>
                        <tr>
                            <td>
                                <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" 
                                     alt="<?= $candidate['name'] ?>" 
                                     class="img-thumbnail" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td><?= $candidate['name'] ?></td>
                            <td class="text-truncate" style="max-width: 200px;"><?= $candidate['vision'] ?></td>
                            <td class="text-truncate" style="max-width: 200px;"><?= $candidate['mission'] ?></td>
                            <td>
                                <a href="<?= base_url('admin-system/candidates/edit/' . $candidate['id']) ?>" 
                                   class="btn btn-sm btn-info me-1" 
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete('<?= base_url('admin-system/candidates/delete/' . $candidate['id']) ?>')"
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
        initializeDataTable('#candidatesTable');
    });
</script>
<?= $this->endSection() ?> 