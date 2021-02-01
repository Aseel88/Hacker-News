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
