<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserEmail() {
    return $_SESSION['user_email'] ?? null;
}

function logout() {
    session_destroy();
    header('Location: /connexion');
    exit;
}
?>
