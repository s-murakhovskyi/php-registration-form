<?php
include __DIR__."/../countries.php";
?>
<fieldset>
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name *</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>

    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name *</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>

    <div class="mb-3">
        <label for="birthdate" class="form-label">Birthdate *</label>
        <input type="date"
               class="form-control"
               id="birthdate"
               name="birthdate"
               max="<?= date('Y-m-d') ?>"
               required>
    </div>

    <div class="mb-3">
        <label for="report_subject" class="form-label">Report Subject *</label>
        <input type="text" class="form-control" id="report_subject" name="report_subject" required>
    </div>

    <div class="mb-3">
        <label for="country" class="form-label">Country *</label>
        <select class="form-select" id="country" name="country" required>
            <option value="">-- Please select --</option>

            <?php foreach ($countries as $country): ?>
                <option value="<?= htmlspecialchars($country) ?>">
                    <?= htmlspecialchars($country) ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone *</label>
        <input type="tel" class="form-control" id="phone" name="phone"
               placeholder="+1 (555) 555-5555"
               maxlength="19"
               required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" class="form-control" id="email" name="email"
               placeholder="example@mail.com"
               pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
               title="Must be a valid email address (e.g. user@domain.com)"
               required>
    </div>

    <div class="d-flex justify-content-end">
        <button type="button" id="next-step-1" class="btn btn-primary">Next</button>
    </div>
</fieldset>