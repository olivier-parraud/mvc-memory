<?php

namespace App\Controllers;
namespace Core;

Use Core\BaseController;

class AuthController 
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && !empty($_POST['username'])) {
            $username = trim($_POST['username']);
            $user = $this->userModel->find_or_create($username);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            header('Location: index.php');
            exit;
        }

    }

    public function logout(): void
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function is_logged(): bool
    {
        return isset($_SESSION['user_id']);
    }
}

