<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';
require __DIR__ . '/../../views/header.php';

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];


    if (isset($_POST['title'], $_POST['link'], $_POST['description'], $_POST['id'])) {
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $link = filter_var($_POST['link'], FILTER_SANITIZE_URL);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        $query = 'UPDATE posts
              SET title = :title,
                  link = :link,
                  userId = :userId,
                  description = :description,
                   createdAt = datetime()
              WHERE id = :id';

        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }


        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':link', $link, PDO::PARAM_STR);
        $statement->bindParam(':description', $description, PDO::PARAM_STR);
        $statement->execute();
    }
}


redirect('/');
