<?php
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../../views/header.php';


if (isset($_SESSION['user']['id'])) {
    $id = $_SESSION['user']['id'];


    if (isset($_FILES['avatar'])) {
        $errors = [];
        $avatar = $_FILES['avatar'];
        $avatarName =  $_FILES['avatar']['name'];


        if (!in_array($avatar['type'], ['image/jpeg', 'image/png'])) {
            $errors[] = 'The uploaded file type is not allowed.';
        }

        if ($avatar['size'] > 2097152) {
            $errors[] = 'The uploaded file exceeded the filesize limit.';
        }

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;

            redirect('/editProfile.php');

            exit;
        }

        if (count($errors) === 0) {
            $destination = __DIR__ . '/uploads/' . $avatarName;

            move_uploaded_file($avatar['tmp_name'], $destination);
            successMessage("The file was successfully uploaded!");
        }

        $query = 'UPDATE users
        SET avatar = :avatarName
        WHERE id =  :id ';

        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->bindValue(':avatarName', $avatarName, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $statement->execute();
    }
}
redirect('/editProfile.php');
