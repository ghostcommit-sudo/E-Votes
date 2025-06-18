// Vote Selection
function selectCandidate(element) {
    // Remove selected class from all cards
    document.querySelectorAll('.vote-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    element.classList.add('selected');

    // Update hidden input value
    document.getElementById('selectedCandidate').value = element.dataset.candidateId;

    // Enable submit button
    document.getElementById('submitVote').disabled = false;
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Loading Spinner
function showLoading() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner-overlay';
    spinner.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    document.body.appendChild(spinner);
}

function hideLoading() {
    const spinner = document.querySelector('.spinner-overlay');
    if (spinner) {
        spinner.remove();
    }
}

// Initialize components when document is ready
$(document).ready(function() {
    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Handle vote form submission
    $('#voteForm').submit(function(e) {
        if (!validateForm('voteForm')) {
            e.preventDefault();
            return false;
        }
        showLoading();
    });

    // Handle student info form submission
    $('#studentInfoForm').submit(function(e) {
        if (!validateForm('studentInfoForm')) {
            e.preventDefault();
            return false;
        }
        showLoading();
    });
}); 