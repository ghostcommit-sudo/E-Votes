<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?= isset($candidate) ? 'Edit' : 'Add' ?> Candidate</h5>
    </div>
    <div class="card-body">
        <form action="<?= isset($candidate) ? base_url('admin-system/candidates/update/' . $candidate['id']) : base_url('admin-system/candidates/store') ?>" 
              method="post" 
              enctype="multipart/form-data" 
              class="needs-validation" 
              novalidate>
            
            <div class="row">
                <!-- Photo Upload -->
                <div class="col-md-4 mb-3">
                    <div class="text-center">
                        <img id="imagePreview" 
                             src="<?= isset($candidate) ? base_url('uploads/candidates/' . $candidate['photo']) : base_url('assets/images/placeholder.jpg') ?>" 
                             alt="Candidate Photo" 
                             class="img-preview mb-3">
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label">Candidate Photo</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*"
                                   <?= isset($candidate) ? '' : 'required' ?>>
                            <div class="invalid-feedback">
                                Please select a photo.
                            </div>
                            <div class="form-text">
                                Maximum file size: 2MB. Supported formats: JPG, PNG
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Candidate Details -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               value="<?= isset($candidate) ? $candidate['name'] : '' ?>" 
                               required>
                        <div class="invalid-feedback">
                            Please enter the candidate's name.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="vision" class="form-label">Vision</label>
                        <textarea class="form-control" 
                                  id="vision" 
                                  name="vision" 
                                  rows="3" 
                                  required><?= isset($candidate) ? $candidate['vision'] : '' ?></textarea>
                        <div class="invalid-feedback">
                            Please enter the candidate's vision.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mission" class="form-label">Mission</label>
                        <textarea class="form-control" 
                                  id="mission" 
                                  name="mission" 
                                  rows="5" 
                                  required><?= isset($candidate) ? $candidate['mission'] : '' ?></textarea>
                        <div class="invalid-feedback">
                            Please enter the candidate's mission.
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="<?= base_url('admin-system/candidates') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= isset($candidate) ? 'Update' : 'Create' ?> Candidate
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?> 