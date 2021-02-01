<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';
$errors = [];
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
    <h1>Your Personal Page</h1>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success-message">
            <?php foreach ($_SESSION['success'] as $successMessage) : ?>
                <p><?= $successMessage; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <div class="avatar">
        <?php if (!$user['avatar']) : ?>
            <img class="profile" src="/assets/img/avatar.png" alt="default profile image">
        <?php else : ?>
            <?php if (file_exists(__DIR__ . '/app/users/uploads/' . $user['avatar'])) : ?>
                <img src=" <?php echo '/app/users/uploads/' . $user['avatar']; ?>" alt="user's profile image">
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="first profile">
        <h2> First Name: </h2>
        <p><?php echo $user['firstName']; ?></p>
    </div>
    <div class="last profile">
        <h3> Last Name:</h3>
        <p><?php echo $user['lastName']; ?></p>
    </div>

    <div class="bio profile">
        <h2> Biography:</h2>
        <p><?php echo $user['biography']; ?></p>
    </div>
    <div class="mail profile">
        <h2> Email:</h2>
        <p><?php echo $user['email']; ?></p>
    </div>



</article>
