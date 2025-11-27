document.addEventListener('DOMContentLoaded', function() {

    // --- CONFIGURATION ---
    const ALLOWED_FILE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    // Get Elements
    const step1Div = document.getElementById('step-1');
    const step2Div = document.getElementById('step-2');
    const step3Div = document.getElementById('step-3-social');
    const phoneInput = document.getElementById('phone');

    // --- DYNAMIC PHONE MASK ---
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            // Strip non-numbers
            let input = e.target.value.replace(/\D/g, '');
            if (!input) {
                e.target.value = '';
                return;
            }
            if (input.length > 11) input = input.substring(0, 11);          // Prevent typing too many digits
            let formatted = '+';            // Rebuild string
            if (input.length > 0) {
                formatted += input.substring(0, 1);
            }
            if (input.length >= 2) {
                formatted += ' (' + input.substring(1, 4);
            }
            if (input.length >= 5) {
                formatted += ') ' + input.substring(4, 7);
            }
            if (input.length >= 8) {
                formatted += '-' + input.substring(7, 11);
            }
            e.target.value = formatted;
        });
    }

    // CHECK STORAGE ON LOAD
    const savedStep = localStorage.getItem('registration_step');
    const savedData = JSON.parse(localStorage.getItem('registration_data')) || {};

    // refill fields
    if (savedData.first_name) document.getElementById('first_name').value = savedData.first_name;
    if (savedData.last_name) document.getElementById('last_name').value = savedData.last_name;
    if (savedData.birthdate) document.getElementById('birthdate').value = savedData.birthdate;
    if (savedData.report_subject) document.getElementById('report_subject').value = savedData.report_subject;
    if (savedData.country) document.getElementById('country').value = savedData.country;
    if (savedData.phone) document.getElementById('phone').value = savedData.phone;
    if (savedData.email) document.getElementById('email').value = savedData.email;

    // Step 2 fields
    if (savedData.company) document.getElementById('company').value = savedData.company;
    if (savedData.position) document.getElementById('position').value = savedData.position;
    if (savedData.about_me) document.getElementById('about_me').value = savedData.about_me;

    // If user was on Step 2, go there
    if (savedStep === '2') {
        step1Div.style.display = 'none';
        step2Div.style.display = 'block';
    }

    // STEP 1 LOGIC
    const nextBtn1 = document.getElementById('next-step-1');
    if (nextBtn1) {
        nextBtn1.addEventListener('click', function(e) {
            e.preventDefault();

            // Validate ALL required fields
            const fname = document.getElementById('first_name').value.trim();
            const lname = document.getElementById('last_name').value.trim();
            const birth = document.getElementById('birthdate').value;
            const subject = document.getElementById('report_subject').value.trim();
            const country = document.getElementById('country').value;
            const phone = document.getElementById('phone').value.trim();
            const email = document.getElementById('email').value.trim();

            // check for empty strings
            if (!fname || !lname || !birth || !subject || !country || !phone || !email) {
                alert("Please fill in all required fields.");
                return;
            }

            // Name Validation
            const namePattern = /^[\p{L}\s\-']+$/u;

            if (!namePattern.test(fname)) {
                alert("First Name cannot contain numbers or special symbols.");
                return;
            }
            if (!namePattern.test(lname)) {
                alert("Last Name cannot contain numbers or special symbols.");
                return;
            }

            // Date Validation
            const selectedDate = new Date(birth);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate > today) {
                alert("Birthdate cannot be in the future.");
                return;
            }

            // STRICT Email Check (Latin Only)
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address (Latin characters only).");
                return;
            }

            // Phone Check
            // Pattern: +1 (555) 555-5555
            const phoneStrictPattern = /^\+\d{1} \(\d{3}\) \d{3}-\d{4}$/;
            if (!phoneStrictPattern.test(phone)) {
                alert("Please enter a complete phone number: +1 (555) 555-5555");
                return;
            }

            // check if email is already used
            fetch('/check-email', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ email: email })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert(data.message);
                    } else {
                        // SUCCESS: Email available, proceed

                        // Save data to LocalStorage
                        const currentData = {
                            first_name: fname,
                            last_name: lname,
                            birthdate: birth,
                            report_subject: subject,
                            country: country,
                            phone: phone,
                            email: email,
                            company: document.getElementById('company').value,
                            position: document.getElementById('position').value,
                            about_me: document.getElementById('about_me').value
                        };

                        localStorage.setItem('registration_data', JSON.stringify(currentData));
                        localStorage.setItem('registration_step', '2');

                        // Switch View
                        step1Div.style.display = 'none';
                        step2Div.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error("Error checking email:", error);
                    alert("System error checking email.");
                });
        });
    }

    // back button
    const backBtn2 = document.getElementById('back-step-2');
    if (backBtn2) {
        backBtn2.addEventListener('click', function(e) {
            e.preventDefault();
            localStorage.setItem('registration_step', '1');
            step2Div.style.display = 'none';
            step1Div.style.display = 'block';
        });
    }

    // step 2 submit
    const nextBtn2 = document.getElementById('next-step-2');
    if (nextBtn2) {
        nextBtn2.addEventListener('click', function(e) {
            e.preventDefault();

            // Create FormData to send everything
            const formData = new FormData();

            // Step 1
            formData.append('first_name', document.getElementById('first_name').value.trim());
            formData.append('last_name', document.getElementById('last_name').value.trim());
            formData.append('birthdate', document.getElementById('birthdate').value.trim());
            formData.append('report_subject', document.getElementById('report_subject').value.trim());
            formData.append('country', document.getElementById('country').value.trim());
            formData.append('phone', document.getElementById('phone').value.trim());
            formData.append('email', document.getElementById('email').value.trim());

            // Step 2
            formData.append('company', document.getElementById('company').value.trim());
            formData.append('position', document.getElementById('position').value.trim());
            formData.append('about_me', document.getElementById('about_me').value.trim());

            // file upload
            const photoInput = document.getElementById('photo');
            if (photoInput && photoInput.files[0]) {
                formData.append('photo', photoInput.files[0]);
            }

            // Send to Server
            fetch('/submit-full-form', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // SUCCESS! CLEAR MEMORY
                        localStorage.removeItem('registration_data');
                        localStorage.removeItem('registration_step');

                        // Show Step 3
                        step2Div.style.display = 'none';
                        step3Div.style.display = 'block';
                    } else {
                        alert(data.message || 'Error submitting form');
                    }
                })
                .catch(error => alert("System Error: " + error));
        });
    }
});