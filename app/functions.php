<?php

declare(strict_types=1);


function redirect(string $path)
{
    header("Location: ${path}");
    exit;
}

//adds a success message
if (!function_exists('successMessage')) {

    function successMessage(string $message): void
    {
        $_SESSION['success'][] = "${message}";
    }
}
//adds an error message
if (!function_exists('errorMessage')) {

    function errorMessage(string $error): void
    {
        $_SESSION['errors'][] = "${error}";
    }
}

function formatDate(string $date): string
{

    $saved_time = $date;
    $formated_saved_time = new DateTime($saved_time);
    $current_time = new DateTime();
    $interval = $current_time->diff($formated_saved_time);

    if (!empty($interval->format('%a'))) {
        $time_difference = $interval->format('%ad ago');
        return $time_difference;
    } elseif ($formated_saved_time->format('d') != $current_time->format('d')) {
        $time_difference = "yesterday";
        return $time_difference;
    } elseif (!empty($interval->format('%h'))) {
        $time_difference = $interval->format('%hh, %im ago');
        return $time_difference;
    } elseif (!empty($interval->format('%i'))) {
        $time_difference = $interval->format('%im ago');
        return $time_difference;
    } elseif (!empty($interval->format('%s'))) {
        $time_difference = $interval->format('%ss ago');
        return $time_difference;
    } else {
        $time_difference = "Now";
        return $time_difference;
    }
}

function isLiked(PDO $pdo, int $userId, int $postId): bool
{
    $query = 'SELECT * FROM likes WHERE userId = :userId and postId = :postId';
    $statement = $pdo->prepare($query);

    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
    $statement->bindParam(':postId', $postId, PDO::PARAM_INT);
    $statement->execute();

    $like = $statement->fetch(PDO::FETCH_ASSOC);

    if ($like) { //If vote exist return true else false
        return true;
    } else {
        return false;
    }
}
function isLikedComment(PDO $pdo, int $userId, int $commentId): bool
{
    $query = 'SELECT * FROM commentLikes WHERE userId = :userId and commentId = :commentId';
    $statement = $pdo->prepare($query);

    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
    $statement->bindParam(':commentId', $commentId, PDO::PARAM_INT);

    $statement->execute();

    $clike = $statement->fetch(PDO::FETCH_ASSOC);

    if ($clike) { //If vote exist return true else false
        return true;
    } else {
        return false;
    }
}

function numberOfLikes(PDO $pdo, int $postId): string
{
    $statement = $pdo->prepare('SELECT * FROM posts WHERE id = :postId');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->bindParam(':postId', $postId, PDO::PARAM_INT);

    $statement->execute();

    $post = $statement->fetch(PDO::FETCH_ASSOC);
    $likes = $post['likes'];

    if ($likes == 1) {
        return "$likes like";
    } else {
        return "$likes likes";
    }
}


function numberOfLikesComments(PDO $pdo, int $commentId): string
{
    $statement = $pdo->prepare('SELECT * FROM comments WHERE commentId = :commentId');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->bindParam(':commentId', $commentId, PDO::PARAM_INT);

    $statement->execute();

    $comment = $statement->fetch(PDO::FETCH_ASSOC);
    $likes = $comment['likes'];

    if ($likes == 1) {
        return "$likes like";
    } else {
        return "$likes likes";
    }
}
