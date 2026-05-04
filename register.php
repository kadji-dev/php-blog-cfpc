<?php
declare(strict_types=1);
session_start();
require_once 'database/database.php';
require_once 'flash.php';

// Rediriger si déjà connecté
if (isset($_SESSION['id'])) {
    header("Location: profil.php?id={$_SESSION['id']}");
    exit();
}

/**
 * Inscrit un nouvel utilisateur en base de données.
 * Retourne "success" ou un message d'erreur.
 */
function register(PDO $pdo, string $username, string $email, string $password, string $confirm_password): string
{
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        return "Tous les champs doivent être remplis.";
    }
    if (strlen($username) > 255) return "Votre pseudo ne doit pas dépasser 255 caractères.";

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->rowCount() > 0) return "Ce pseudo est déjà utilisé.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Adresse email invalide.";

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->rowCount() > 0) return "Adresse mail déjà utilisée !";

    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password)) {
        return "Mot de passe : 8 caractères min. avec une lettre et un chiffre.";
    }
    if ($password !== $confirm_password) return "Les mots de passe ne correspondent pas !";

    $stmt = $pdo->prepare("INSERT INTO users(username, email, password, role) VALUES(:username, :email, :password, 'user')");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    return "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = strip_tags($_POST['username'] ?? '');
    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $result = register($pdo, $username, $email, $password, $confirm_password);

    if ($result === "success") {
        flash_set('success', "Compte créé avec succès ! Vous pouvez maintenant vous connecter.");
        header("Location: login.php");
        exit();
    }

    flash_set('error', $result);
    header("Location: register.php");
    exit();
}


$pageTitle = 'S\'incrire';
ob_start();
require_once 'resources/views/users/register_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php';
