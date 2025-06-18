<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1>Student Council Election</h1>
        <p>Make your voice heard! Vote for your next student council leader.</p>
        <?php if (!session()->get('isStudentLoggedIn')): ?>
            <a href="<?= base_url('login') ?>" class="btn btn-light btn-lg">Login to Vote</a>
        <?php else: ?>
            <a href="<?= base_url('vote') ?>" class="btn btn-light btn-lg">Cast Your Vote</a>
        <?php endif; ?>
    </div>
</div>

<!-- Candidates Section -->
<div class="container my-5">
    <h2 class="text-center mb-4">Meet the Candidates</h2>
    
    <div class="row g-4">
        <?php foreach ($candidates as $candidate): ?>
            <div class="col-md-4">
                <div class="candidate-card">
                    <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" alt="<?= $candidate['name'] ?>" class="card-img-top">
                    <div class="candidate-info">
                        <h3 class="candidate-name"><?= $candidate['name'] ?></h3>
                        <div class="candidate-vision">
                            <h4 class="h5">Vision</h4>
                            <p class="text-truncate-2"><?= $candidate['vision'] ?></p>
                        </div>
                        <div class="candidate-mission">
                            <h4 class="h5">Mission</h4>
                            <p class="text-truncate-2"><?= $candidate['mission'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Information Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h2>How to Vote</h2>
            <ol class="list-group list-group-numbered mb-4">
                <li class="list-group-item">Login using your Google account</li>
                <li class="list-group-item">Complete your student information (NIS and Class)</li>
                <li class="list-group-item">Select your preferred candidate</li>
                <li class="list-group-item">Submit your vote</li>
                <li class="list-group-item">Download your voting receipt</li>
            </ol>
        </div>
        <div class="col-md-6">
            <h2>Important Information</h2>
            <div class="card">
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i> You can only vote once</li>
                        <li class="mb-2"><i class="fas fa-clock text-primary me-2"></i> Voting period: <?= $period ? $period['year_start'] . '/' . $period['year_end'] : 'No active period' ?></li>
                        <li class="mb-2"><i class="fas fa-shield-alt text-primary me-2"></i> Your vote is confidential</li>
                        <li><i class="fas fa-file-pdf text-primary me-2"></i> You will receive a PDF receipt after voting</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 