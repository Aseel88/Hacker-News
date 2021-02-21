<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

header('Content-Type: application/json');


if (isset($_POST['like'])) {
    $userId = $_SESSION['user']['id'];
    $commentId = filter_var($_POST['like'], FILTER_SANITIZE_NUMBER_INT);


    if (isLikedComment($pdo, (int) $_SESSION['user']['id'],  (int) $commentId)) { //Checks if user has voted, if vote exist - remove the vote
        $query = 'UPDATE comments SET likes = likes - 1 WHERE commentId = :commentId';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }
        $statement->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $statement->execute();

        $query = 'DELETE FROM CommentLikes WHERE userId = :userId AND commentId = :commentId';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':commentId', $commentId, PDO::PARAM_INT);

        $statement->execute();

        $numberOfLikesComments = numberOfLikesComments($pdo, $commentId);
        $status = true;

        $response = [
            'numberOfLikesComments' => $numberOfLikesComments,
            'status' => $status
        ];
        echo json_encode($response);
    } else { // If vote do not exist from user - add vote
        $query = 'UPDATE comments SET likes = likes + 1 WHERE commentId = :commentId';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $statement->execute();

        $query = 'INSERT INTO commentLikes (commentId, userId) VALUES (:commentId, :userId)';
        $statement = $pdo->prepare($query);

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':commentId', $commentId, PDO::PARAM_INT);

        $statement->execute();
        $numberOfLikesComments = numberOfLikesComments($pdo, $commentId);
        $status = false;

        $response = [
            'numberOfLikesComments' => $numberOfLikesComments,
            'status' =>  $status
        ];
        echo json_encode($response);
    }
}
 //else {
//     $_SESSION['message'] = "You have to be logged in to upvote.";
//     redirect('../../login.php');
// }
