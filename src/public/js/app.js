document.addEventListener('DOMContentLoaded', function() {

    // Grab the main containers
    const step1Div = document.getElementById('step-1');
    const step2Div = document.getElementById('step-2');
    const step3Div = document.getElementById('step-3-social');

    // --- STEP 1 LOGIC ---
    const nextBtn1 = document.getElementById('next-step-1');
    if (nextBtn1) {
        nextBtn1.addEventListener('click', function(e) {
            e.preventDefault();

            const formData = {
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                birthdate: document.getElementById('birthdate').value,
                report_subject: document.getElementById('report_subject').value,
                country: document.getElementById('country').value,
                phone: document.getElementById('phone').value,
                email: document.getElementById('email').value
            };

            // Validation omitted for brevity...

            fetch('/submit-step-1', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Switch the page
                        step1Div.style.display = 'none';
                        step2Div.style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                });
        });
    }

    // --- STEP 2 LOGIC ---
    const nextBtn2 = document.getElementById('next-step-2');
    if (nextBtn2) {
        nextBtn2.addEventListener('click', function(e) {
            e.preventDefault();

            const formElement = document.getElementById('registration-form');
            const formData = new FormData(formElement);

            fetch('/submit-step-2', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Switch to Step 3
                        step2Div.style.display = 'none';
                        step3Div.style.display = 'block';
                    } else {
                        alert(data.message || 'Error saving Step 2');
                    }
                });
        });
    }
});