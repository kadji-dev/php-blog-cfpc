# Page 8 : Architecture Visuelle et Système de Templating

Le templating est l'art de créer un site dont le design est uniforme tout en ayant des pages au contenu différent. Nous utilisons la technique du **Buffer d'affichage**.

## 1. Le problème de la répétition HTML

Sans templating, vous devriez copier-coller votre Header (menu) et votre Footer (bas de page) dans chaque fichier. Si vous changez un lien dans le menu, vous devez modifier 50 fichiers. **C'est inacceptable.**

## 2. La Solution : Output Buffering (`ob_start`)

Cette technique permet de capturer le HTML dans une variable. Voici comment nous l'utilisons dans `index.php` ou `login.php` :

```php
// 1. On définit les variables pour cette page précise
$pageTitle = 'Ma Page de Connexion';

// 2. On ouvre le tampon (le seau)
ob_start();

// 3. On inclut le fichier de vue (HTML pur)
// PHP va "écrire" le HTML mais rien ne sortira vers l'écran
require_once 'resources/views/users/login_html.php';

// 4. On vide le tampon dans une variable
// $pageContent contient maintenant tout le HTML du formulaire
$pageContent = ob_get_clean();

// 5. On appelle le Layout GLOBAL
// Ce fichier va afficher le header, le footer et injecter $pageContent au milieu
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php';
```

## 3. Anatomie du Layout (`blog-layout_html.php`)

C'est le "maître" du design. Voici sa structure simplifiée :

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title> <!-- Titre dynamique -->
</head>
<body>
    <?php include 'blog-header_html.php'; ?> <!-- Menu constant -->

    <main>
        <?= $pageContent ?> <!-- Ici s'injecte le formulaire ou l'article -->
    </main>

    <?php include 'blog-footer_html.php'; ?> <!-- Pied de page constant -->
</body>
</html>
```

## 3. Inclusions critiques du Layout

Dans `blog-layout_html.php`, nous utilisons `include` pour les fragments :

```php
<?php include 'blog-header_html.php'; ?>
```
**Pourquoi `include` et pas `require` ?** 
Si pour une raison X ou Y le fichier du menu (header) est corrompu ou manquant, nous préférons que l'utilisateur puisse quand même lire le contenu de l'article plutôt que de voir une page d'erreur blanche totale. Le Header est "important" mais pas "critique" pour le fonctionnement du code PHP pur.

## 4. Pourquoi est-ce proche de Laravel ?

Dans Laravel, on utilise le moteur **Blade**. On écrit `@extends('layout')` et `@section('content')`. Notre système PHP pur avec `ob_start()` fait exactement la même chose manuellement. C'est la meilleure façon de comprendre comment fonctionnent les moteurs de templates professionnels.

## 5. Résumé des avantages
-   **Titre dynamique** : Change selon la page via `$pageTitle`.
-   **Maintenance facile** : Le design global est dans un seul fichier.
-   **Code propre** : Vos fichiers à la racine ne contiennent presque plus de HTML.
