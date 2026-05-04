# Page 4 : Anatomie Complète du Système d'Inscription

L'inscription est le point d'entrée de vos utilisateurs. C'est là que vous devez être le plus rigoureux sur la validation des données.

## 1. Le Fichier `register.php` : Le Cerveau

Ce fichier fait deux choses : il affiche le formulaire (via la vue) et il traite les données quand le formulaire est envoyé.

### Analyse détaillée de la fonction `register()` :

```php
function register(PDO $pdo, string $username, string $email, string $password, string $confirm_password): string
{
    // ÉTAPE 1 : Vérification de la présence des données
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        return "Tous les champs sont obligatoires.";
    }

    // ÉTAPE 2 : Validation du format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "L'adresse email n'est pas valide.";
    }

    // ÉTAPE 3 : Vérification de l'unicité (Sécurité DB)
    // On ne veut pas deux personnes avec le même email ou pseudo
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
    $stmt->execute([':email' => $email, ':username' => $username]);
    if ($stmt->rowCount() > 0) {
        return "Le pseudo ou l'email est déjà utilisé.";
    }

    // ÉTAPE 4 : Vérification de la complexité du mot de passe
    if (strlen($password) < 8) {
        return "Le mot de passe doit faire au moins 8 caractères.";
    }

    // ÉTAPE 5 : Concordance des mots de passe
    if ($password !== $confirm_password) {
        return "Les mots de passe ne sont pas identiques.";
    }

    // ÉTAPE 6 : HACHAGE ET INSERTION
    // PASSWORD_DEFAULT utilise l'algorithme le plus sûr actuellement (BCrypt ou Argon2)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:u, :e, :p, 'user')");
    $stmt->execute([
        ':u' => $username,
        ':e' => $email,
        ':p' => $hashedPassword
    ]);

    return "success";
}
```

## 2. Pourquoi `password_hash()` est-il obligatoire ?

Si vous stockez "123456" en base de données et qu'un pirate y accède, il a tous les comptes.
Avec `password_hash()`, "123456" devient quelque chose comme `$2y$10$vI8A...`. 
Il est impossible (théoriquement) de retrouver le "123456" à partir de cette chaîne. C'est ce qu'on appelle une fonction de hachage à sens unique.

## 3. Le traitement du formulaire POST

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Nettoyage des entrées pour éviter le code malveillant
    $username = strip_tags($_POST['username'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    
    // On appelle notre fonction
    $result = register($pdo, $username, $email, $_POST['password'], $_POST['confirm_password']);

    if ($result === "success") {
        flash_set('success', "Votre compte a été créé ! Connectez-vous.");
        header("Location: login.php");
        exit();
    } else {
        flash_set('error', $result);
        header("Location: register.php"); // On reste sur la page pour corriger
        exit();
    }
}
```

## 4. Conseils pour Débutants
-   **`strip_tags()`** : Utile pour enlever les balises HTML qu'un utilisateur pourrait essayer d'injecter dans son pseudo.
-   **`exit()`** : Ne l'oubliez jamais après une redirection `header()`. Sans lui, le script continue de s'exécuter derrière la redirection, ce qui est un trou de sécurité.
