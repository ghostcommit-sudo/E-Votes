<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h2 class="mb-0"><?= $totalStudents ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Votes</h5>
                    <h2 class="mb-0"><?= $totalVotes ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Voter Turnout</h5>
                    <h2 class="mb-0"><?= $totalStudents > 0 ? number_format(($totalVotes / $totalStudents) * 100, 1) : '0' ?>%</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Candidates</h5>
                    <h2 class="mb-0"><?= $totalCandidates ?></h2>
                </div>
            </div>
        </div>
    </div>

    <?php if ($activePeriod): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vote Results - <?= esc($activePeriod['name']) ?></h5>
                    <a href="<?= base_url('admin-system/dashboard/export') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-download me-1"></i> Export Results
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($voteResults)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Candidate</th>
                                        <th>Votes</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalVotesInPeriod = array_sum(array_column($voteResults, 'votes'));
                                    foreach ($voteResults as $result): 
                                    ?>
                                    <tr>
                                        <td><?= esc($result['name']) ?></td>
                                        <td><?= $result['votes'] ?></td>
                                        <td>
                                            <?= $totalVotesInPeriod > 0 
                                                ? number_format(($result['votes'] / $totalVotesInPeriod) * 100, 1) 
                                                : '0' ?>%
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            No votes have been recorded yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No active voting period found. Please create and activate a voting period to start collecting votes.
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Initialize vote distribution chart
    document.addEventListener('DOMContentLoaded', function() {
        const labels = <?= json_encode(array_column($voteResults, 'name')) ?>;
        const data = <?= json_encode(array_column($voteResults, 'votes')) ?>;
        initializeChart('voteChart', labels, data);
        
        // Initialize DataTable for class statistics
        initializeDataTable('#classTable');
    });
</script>
<?= $this->endSection() ?> 