<div id="map"></div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h2>To participate in the conference, please fill out the form</h2>
            <hr>

            <form id="registration-form">

                <div id="step-1">
                    <?php include 'partials/step-1.php'; ?>
                </div>

                <div id="step-2" style="display: none;">
                    <?php include 'partials/step-2.php'; ?>
                </div>

                <div id="step-3-social" style="display: none;">
                    <?php include 'partials/step-3-social.php'; ?>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Leaflet map
    const map = L.map('map').setView([34.1016, -118.3406], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([34.1016, -118.3406]).addTo(map)
        .bindPopup('7060 Hollywood Blvd, Los Angeles, CA')
        .openPopup();
</script>