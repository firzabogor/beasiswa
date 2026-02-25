document.addEventListener('DOMContentLoaded', function() {
    console.log('SDMO Dashboard loaded');
    
    // Example: Confirmation before submitting form
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Optional: validation or loading state
            // const submitBtn = this.querySelector('button[type="submit"]');
            // if(submitBtn) {
            //     submitBtn.textContent = 'Mengirim...';
            //     submitBtn.disabled = true;
            // }
        });
    });
});
