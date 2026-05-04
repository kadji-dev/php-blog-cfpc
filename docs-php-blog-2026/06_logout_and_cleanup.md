# Page 6 : Déconnexion et Sécurité des Sessions

La déconnexion semble simple, mais elle est vitale pour la sécurité de l'utilisateur, surtout sur les ordinateurs partagés.

## 1. Qu'est-ce que la Déconnexion ?

Se déconnecter, c'est dire au serveur : "Oublie tout ce que tu sais sur cet utilisateur et détruis son badge (ID de session)".

## 2. Analyse du code `logout.php`

```php
<?php
// On doit TOUJOURS appeler session_start() même pour déconnecter
session_start();
require_once 'flash.php';

// ÉTAPE 1 : On vide le tableau de session en mémoire vive
$_SESSION = [];

// ÉTAPE 2 : On détruit physiquement le fichier de session sur le serveur
// Cela rend l'ancien cookie PHPSESSID totalement inutile
session_destroy();

// ÉTAPE 3 : On prépare un message de confirmation
// Note : session_destroy() a tout supprimé, on doit donc parfois 
// refaire un petit session_start() juste pour le message flash final
session_start();
flash_set('success', "Vous avez été déconnecté. À bientôt !");

// ÉTAPE 4 : Redirection vers la page de login
header('Location: login.php');
exit();
```

## 3. Le danger de la session persistante

Si vous ne détruisez pas la session correctement, un attaquant pourrait utiliser une technique appelée "Session Hijacking" (vol de session) pour se faire passer pour l'utilisateur. En utilisant `$_SESSION = []` et `session_destroy()`, vous fermez toutes les portes.

## 4. Expérience Utilisateur (UX)

Il est important de rediriger l'utilisateur vers une page publique (comme le login ou l'accueil) après la déconnexion. S'il reste sur une page protégée, il verra une erreur "Accès refusé", ce qui est frustrant. En le redirigeant vers `login.php` avec un message de succès, vous lui confirmez que tout s'est bien passé.

### Résumé de la déconnexion :
1.  Vider les variables.
2.  Détruire le fichier serveur.
3.  Informer l'utilisateur.
4.  Rediriger.
