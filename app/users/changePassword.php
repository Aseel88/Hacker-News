<?php

declare(strict_types=1);
require __DIR__ . '/../autoload.php';

if (isset($_SESSION['user']['id'])) {
    $errors = [];
    $id = $_SESSION['user']['id'];

    if (isset($_POST['currentPassword'], $_POST['newPassword'])) {

        $currentPassword = $_POST['currentPassword'];
        $hash = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

        $statement = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (password_verify($currentPassword, $user['password'])) {
            $user['password'] = $currentPassword;
            $updatePassword = 'UPDATE users SET password = :hash WHERE id =:id';
            $statement = $pdo->prepare($updatePassword);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':hash', $hash, PDO::PARAM_STR);
            $statement->execute();

            unset($user['password']);

            //show a success message
            successMessage("Your password is changed successfully!");
            redirect('/personalPage.php');
        } else {

            //show an error message
            errorMessage("Try again,The typed password did not match your current password");
            redirect('/changePassword.php');
            unset($user['password']);
        }
    }
}
