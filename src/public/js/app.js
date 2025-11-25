document.addEventListener('DOMContentLoaded', function() {

    // Get Elements
    const step1Div = document.getElementById('step-1');
    const step2Div = document.getElementById('step-2');
    const step3Div = document.getElementById('step-3-social');

    // CHECK STORAGE ON LOAD
    const savedStep = localStorage.getItem('registration_step');
    const savedData = JSON.parse(localStorage.getItem('registration_data')) || {};

    // to refill fields if they exist in memory
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
            const fname = document.getElementById('first_name').value;
            const lname = document.getElementById('last_name').value;
            const birth = document.getElementById('birthdate').value;
            const subject = document.getElementById('report_subject').value;
            const country = document.getElementById('country').value;
            const phone = document.getElementById('phone').value;
            const email = document.getElementById('email').value;

            // check for empty strings
            if (!fname || !lname || !birth || !subject || !country || !phone || !email) {
                alert("Please fill in all required fields.");
                return;
            }

            // Email Check
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return;
            }

            // Phone Check
            const phonePattern = /^[\+]?[\d\s\-\(\)]{10,25}$/;
            if (!phonePattern.test(phone)) {
                alert("Phone number is invalid. It must be 10-25 digits/characters long.");
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
            formData.append('first_name', document.getElementById('first_name').value);
            formData.append('last_name', document.getElementById('last_name').value);
            formData.append('birthdate', document.getElementById('birthdate').value);
            formData.append('report_subject', document.getElementById('report_subject').value);
            formData.append('country', document.getElementById('country').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('email', document.getElementById('email').value);

            // Step 2
            formData.append('company', document.getElementById('company').value);
            formData.append('position', document.getElementById('position').value);
            formData.append('about_me', document.getElementById('about_me').value);

            // File
            const photoInput = document.getElementById('photo');
            if (photoInput.files[0]) {
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