<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="receipt">
        <div class="receipt-header">
            <h2>Voting Receipt</h2>
            <p class="text-muted">Student Council Election <?= $period['name'] ?></p>
        </div>

        <div class="receipt-content">
            <table class="table">
                <tr>
                    <th>Student Name:</th>
                    <td><?= $student['name'] ?></td>
                </tr>
                <tr>
                    <th>NIS:</th>
                    <td><?= $student['nis'] ?></td>
                </tr>
                <tr>
                    <th>Class:</th>
                    <td><?= $class['name'] ?></td>
                </tr>
                <tr>
                    <th>Vote Date:</th>
                    <td><?= $vote['created_at'] ?></td>
                </tr>
                <tr>
                    <th>Receipt ID:</th>
                    <td><?= $vote['receipt_id'] ?></td>
                </tr>
            </table>
        </div>

        <div class="receipt-footer">
            <p>This receipt confirms your participation in the student council election.</p>
            <p>Keep this receipt for your records.</p>
        </div>

        <div class="text-center mt-4">
            <a href="<?= base_url('vote/download-receipt') ?>" class="btn btn-primary">
                <i class="fas fa-download me-2"></i>Download PDF
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 