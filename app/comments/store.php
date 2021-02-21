<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';
require __DIR__ . '/../../views/header.php';
// In this file we store/insert new posts in the database.


if (isset($_SESSION['user']['id'])) {

    $userId = $_SESSION['user']['id'];


    $query = ('SELECT users.id FROM users
                              INNER JOIN comments
                             ON users.id = comments.userId');
    $statement = $pdo->prepare($query);
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }

    $users = $statement->fetchAll(PDO::FETCH_ASSOC);


    if (isset($_POST['comment'], $_POST['postId'])) {
        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
        $postId = filter_var($_POST['postId'], FILTER_SANITIZE_NUMBER_INT);



        $query = 'INSERT INTO comments (userId, postId, comment ,createdAt, likes) VALUES (:userId, :postId, :comment, datetime(), 0)';

        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }


        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $statement->bindParam(':comment', $comment, PDO::PARAM_STR);



        $statement->execute();
    }
}

redirect('/');
