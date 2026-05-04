# Page 7 : Sécurisation des Accès (Espace Administration)

La protection des pages est ce qui transforme un simple site en une véritable application web sécurisée. Voici comment nous protégeons les zones sensibles.

## 1. Le rôle de la Session dans la Protection

Sur chaque page protégée (ex: `admin.php`), nous devons poser deux questions au serveur au tout début du fichier :
1.  **L'utilisateur est-il connecté ?** (`isset($_SESSION['auth'])`)
2.  **A-t-il le bon rôle ?** (`$_SESSION['role'] === 'admin'`)

## 2. Analyse du "Verrou" de sécurité (`admin.php`)

```php
<?php
declare(strict_types=1);
session_start();
require_once 'flash.php';

// LE VERROU :
if (!isset($_SESSION['auth']) || $_SESSION['role'] !== 'admin') {
    // 1. On prépare un message d'erreur
    flash_set('error', "Accès refusé. Vous n'avez pas les droits nécessaires.");
    
    // 2. On redirige vers le login
    header("Location: login.php");
    
    // 3. ON ARRÊTE LE SCRIPT
    exit();
}

// SI ON ARRIVE ICI, C'EST QUE L'UTILISATEUR EST ADMIN
// Le reste du code de la page s'exécute...
```

## 3. Pourquoi le `exit()` est la ligne la plus importante ?

Imaginez que vous oubliez le `exit()`. Le navigateur reçoit l'ordre de rediriger, mais le serveur PHP, lui, continue de lire les lignes suivantes. Si votre page admin contient des boutons pour "Supprimer tous les utilisateurs", un pirate pourrait envoyer une requête spéciale qui ignore la redirection et exécute quand même la suppression ! **Le `exit()` est votre garde-fou ultime.**

## 4. Centraliser la sécurité (Astuce d'Expert)

Si vous avez 50 pages admin, au lieu de copier-coller ce code partout, vous pouvez créer un fichier `admin_check.php` et l'inclure au début de chaque page :

`require_once 'admin_check.php';`

C'est ce qu'on appelle l'approche **DRY (Don't Repeat Yourself)**, très présente dans Laravel.

## 5. Distinction Utilisateur / Admin

Grâce à cette structure, vous pouvez avoir deux espaces distincts :
-   Un espace membre (`user-dashboard.php`) qui vérifie juste `isset($_SESSION['auth'])`.
-   Un espace admin (`admin.php`) qui vérifie en plus le rôle.

C'est la base de tout système de gestion de droits (ACL - Access Control List).
