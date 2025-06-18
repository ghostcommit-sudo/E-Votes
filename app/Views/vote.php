<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h1 class="text-center mb-4">Cast Your Vote</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if ($hasVoted): ?>
        <div class="alert alert-info text-center">
            <h4 class="alert-heading">You have already voted!</h4>
            <p>Thank you for participating in the student council election.</p>
            <hr>
            <p class="mb-0">
                <a href="<?= base_url('vote/receipt') ?>" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Download Receipt
                </a>
            </p>
        </div>
    <?php else: ?>
        <form action="<?= base_url('vote/submit') ?>" method="post" id="voteForm" class="needs-validation" novalidate>
            <input type="hidden" id="selectedCandidate" name="candidate_id" required>
            
            <div class="row g-4">
                <?php foreach ($candidates as $candidate): ?>
                    <div class="col-md-4">
                        <div class="vote-card card h-100" onclick="selectCandidate(this)" data-candidate-id="<?= $candidate['id'] ?>">
                            <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" class="card-img-top" alt="<?= $candidate['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $candidate['name'] ?></h5>
                                <div class="mb-3">
                                    <h6>Vision:</h6>
                                    <p class="card-text"><?= $candidate['vision'] ?></p>
                                </div>
                                <div>
                                    <h6>Mission:</h6>
                                    <p class="card-text"><?= $candidate['mission'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <button type="submit" id="submitVote" class="btn btn-primary btn-lg" disabled>
                    Submit Vote
                </button>
            </div>
        </form>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmVoteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Your Vote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to vote for this candidate? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('voteForm').submit()">
                            Confirm Vote
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->section('scripts') ?>
<script>
    // Show confirmation modal before submitting vote
    document.getElementById('voteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (this.checkValidity()) {
            new bootstrap.Modal(document.getElementById('confirmVoteModal')).show();
        }
        this.classList.add('was-validated');
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 