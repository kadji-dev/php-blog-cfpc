# Page 5 : Maîtriser le Processus d'Authentification (Login)

La connexion est l'étape où vous transformez un visiteur anonyme en un utilisateur identifié. Voici comment cela fonctionne en profondeur.

## 1. La Stratégie de Connexion Mixte

Nous avons configuré notre système pour qu'un utilisateur puisse entrer soit son **Email**, soit son **Pseudo**. C'est très moderne et pratique.

### Le Code de vérification (`login.php`) :

```php
function authenticateUser(PDO $pdo, string $identifier, string $password): string 
{
    // 1. On cherche l'utilisateur dans la base
    // On utilise OR pour vérifier les deux colonnes d'un coup
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :id OR username = :id LIMIT 1");
    $stmt->execute([':id' => $identifier]);
    $user = $stmt->fetch();

    // 2. Si l'utilisateur n'existe pas
    if (!$user) {
        return "Identifiants incorrects.";
    }

    // 3. Vérification du mot de passe haché
    // password_verify s'occupe de comparer le texte clair avec le hash
    if (!password_verify($password, $user['password'])) {
        return "Identifiants incorrects.";
    }

    // 4. Succès : On remplit la SESSION
    // C'est ici qu'on donne le "badge" à l'utilisateur
    $_SESSION['auth'] = true;
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    return "success";
}
```

## 2. Pourquoi ne pas dire "Email incorrect" ou "Mot de passe incorrect" ?

**Conseil d'expert Sécurité :** Utilisez toujours un message générique comme "Identifiants incorrects". 
Si vous dites "L'email est bon mais le mot de passe est faux", vous apprenez à un pirate qu'un compte existe avec cet email. Ne lui donnez aucune piste !

## 3. La Session : Le Badge de Sécurité

Une fois que `$_SESSION['auth'] = true` est défini, l'utilisateur est considéré comme connecté sur TOUTES les pages du site tant qu'il ne ferme pas son navigateur ou qu'il ne clique pas sur déconnexion.

### Accéder aux infos partout :
Dans n'importe quel fichier, vous pouvez maintenant faire :
`echo "Bienvenue " . $_SESSION['username'];`

## 4. Redirection selon le Rôle

Dans le traitement POST du login, nous avons ce bloc :

```php
if ($result === "success") {
    flash_set('success', "Heureux de vous revoir !");
    
    // REDIRECTION INTELLIGENTE
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php"); // L'admin va sur son dashboard
    } else {
        header("Location: index.php"); // L'utilisateur va sur l'accueil
    }
    exit();
}
```

C'est une structure proche de Laravel qui permet de diriger les utilisateurs vers le bon endroit immédiatement après leur authentification.
