<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

$errors = [];

if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

if (isset($_SESSION['user']['id'])) {
    $user = $_SESSION['user'];
    $id = $_SESSION['user']['id'];


    $statement = $pdo->prepare('SELECT * FROM users WHERE id =:id');


    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
}

?>
<article>
    <h2>Edit your profile!</h2>
    <!-- shows a message  -->
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="message">
            <?php foreach ($_SESSION['success'] as $successMessage) : ?>
                <p><?= $successMessage; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php foreach ($errors as $error) : ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endforeach; ?>

    <!-- /form-group -->
    <form class="avatarForm" action="app/users/editAvatar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <div class="avatar">
            <?php if (count($errors) > 0) : ?>
                <img src="/assets/img/avatar.png" alt="default profile image">
            <?php elseif (!$user['avatar']) : ?>
                <img src="/assets/img/avatar.png" alt="default profile image">
            <?php else : ?>
                <?php if (file_exists(__DIR__ . '/app/users/uploads/' . $user['avatar'])) : ?>
                    <img src=" <?php echo '/app/users/uploads/' . $user['avatar']; ?>" alt="user's profile image">
                <?php endif; ?>
            <?php endif; ?>
            <input type="file" accept=".jpg, .jpeg, .png" name="avatar" class="avatar" id="avatar">
        </div>

        <button type="submit">Upload</button>
    </form>
    <!-- /form-group -->
    <form action="app/users/profile.php" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label for="firstName">First name</label>
            <input class="form-control" type="text" name="firstName" id="firstName" value="<?php echo $user['firstName']; ?>">
            <small class="form-text text-muted">Edit your first name*</small>

        </div>

        <div class="form-group">
            <label for="lastName">Last name</label>
            <input class="form-control" type="text" name="lastName" id="lastName" value="<?php echo $user['lastName']; ?>">
            <small class="form-text text-muted">Edit your last name*</small>

        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" id="email" value="<?php echo $user['email']; ?>">
            <small class="form-text text-muted">Edit your email*</small>

        </div>

        <div class="form-group">
            <label for="biography">Biography</label>
            <textarea class="form-control" type="text" name="biography" id="biography"><?php echo $user['biography']; ?></textarea>
            <small class="form-text text-muted">Edit your biography*</small>

        </div>

        <!-- <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" type="password" name="password" id="password">
            <small class="form-text text-muted">Edit your password (passphrase)*</small>
        </div>

        <div class="form-group">
            <label for="confirmPassword"> Confirm Password</label>
            <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" required>
            <small class="form-text text-muted">Please re-write your password (passphrase)*</small>
        </div> -->

        <button type="submit" name="submit">Edit profile </button>
    </form>
</article>

<a href="/changePassword.php">Change Your Password</a>

<article class="delete-form">
    <!-- form to delete everything -->
    <form action="app/users/delete.php" method="post">
        <p class="delete-text">Please type in "delete" to permanently delete your profile with every post, comment, reply and upvote made by you.</p>
        <label for="delete">Type here: </label>
        <input type="text" name="delete" placeholder="delete" required />
        <button type="submit" name="submit">Delete</button>
    </form>
</article>
