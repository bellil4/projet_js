document.addEventListener('DOMContentLoaded', () => {
    // Simple form validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            let valid = true;
            const requiredInputs = form.querySelectorAll('input[required], textarea[required]');
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '#ccc';
                }
            });

            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');

            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    valid = false;
                    alert("Les mots de passe ne correspondent pas.");
                    password.style.borderColor = 'red';
                    confirmPassword.style.borderColor = 'red';
                }
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    });

    // Confirmation for delete actions
    const deleteLinks = document.querySelectorAll('a[onclick*="confirm"]');
    // They already have onclick in HTML, but we could handle it here too.
});
