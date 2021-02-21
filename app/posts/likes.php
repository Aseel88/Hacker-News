<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

header('Content-Type: application/json');


if (isset($_POST['like'])) {
    $userId = $_SESSION['user']['id'];
    $postId = filter_var($_POST['like'], FILTER_SANITIZE_NUMBER_INT);

    if (isLiked($pdo, (int) $_SESSION['user']['id'], (int) $postId)) { //Checks if user has voted, if vote exist - remove the vote
        $query = 'UPDATE posts SET likes = likes - 1 WHERE id = :postId';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }
        $statement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $statement->execute();

        $query = 'DELETE FROM likes WHERE userId = :userId AND postId = :postId';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $statement->execute();

        $numberOfLikes = numberOfLikes($pdo, $postId);
        $status = true;

        $response = [
            'numberOfLikes' => $numberOfLikes,
            'status' => $status
        ];
        echo json_encode($response);
    } else { // If vote do not exist from user - add vote
        $query = 'UPDATE posts SET likes = likes + 1 WHERE id = :postId';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $statement->execute();

        $query = 'INSERT INTO likes (id, userId, postId) VALUES (:id, :userId, :postId)';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $statement->execute();
        $numberOfLikes = numberOfLikes($pdo, $postId);
        $status = false;

        $response = [
            'numberOfLikes' => $numberOfLikes,
            'status' =>  $status
        ];
        echo json_encode($response);
    }
} else {
    $_SESSION['message'] = "You have to be logged in to upvote.";
    redirect('../../login.php');
}
