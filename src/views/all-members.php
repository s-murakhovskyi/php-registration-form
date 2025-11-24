<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>All Members (<?php echo count($users); ?>)</h2>
        <!-- Link back to the registration form -->
        <a href="/" class="btn btn-primary">Add New Member</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>Photo</th>
                <th>Full Name</th>
                <th>Report Subject</th>
                <th>Email</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <!-- Photo Column -->
                    <td>
                        <?php
                        $photo = !empty($user['photo_path']) ? $user['photo_path'] : 'default.jpg';
                        $photoUrl = '/' . $photo;
                        ?>
                        <img src="<?php echo htmlspecialchars($photoUrl); ?>"
                             alt="Photo"
                             class="rounded-circle"
                             style="width: 50px; height: 50px; object-fit: cover;">
                    </td>

                    <!-- Full Name Column -->
                    <td>
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </td>

                    <!-- Report Subject -->
                    <td>
                        <?php echo htmlspecialchars($user['report_subject']); ?>
                    </td>

                    <!-- Email (Link) -->
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="4" class="text-center py-4">No members found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>