<fieldset>
    <div class="mb-3">
        <label for="company" class="form-label">Company</label>
        <input type="text" class="form-control" id="company" name="company">
    </div>

    <div class="mb-3">
        <label for="position" class="form-label">Position</label>
        <input type="text" class="form-control" id="position" name="position">
    </div>

    <div class="mb-3">
        <label for="about_me" class="form-label">About Me</label>
        <textarea class="form-control" id="about_me" name="about_me" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label for="photo" class="form-label">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
    </div>

    <div class="d-flex justify-content-between">
        <button type="button" id="back-step-2" class="btn btn-secondary">Back</button>

        <button type="button" id="next-step-2" class="btn btn-primary">Next (Finish)</button>
    </div>
</fieldset>