<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

?>
<article>
    <h2>Change your password!</h2>

    <?php if (isset($_SESSION['errors'])) : ?>
        <div class="errors">
            <?php foreach ($_SESSION['errors'] as $error) : ?>
                <p><?= $error; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <form action="app/users/changePassword.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="currentPassword">Current Password</label>
            <input class="form-control" type="password" name="currentPassword" id="currentPassword">

        </div>

        <div class="form-group">
            <label for="newPassword">New Password</label>
            <input class="form-control" type="password" name="newPassword" id="newPassword">

        </div>

        <div class="form-group">
            <label for="confirmPassword"> Confirm Password</label>
            <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" required>
            <small class="form-text text-muted">Please re-write your password (passphrase)*</small>
        </div>
        <button type="submit" name="submit">Change Password </button>
    </form>
</article>
