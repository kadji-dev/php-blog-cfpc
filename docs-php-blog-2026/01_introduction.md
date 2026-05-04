# Page 1 : Introduction Exhaustive à l'Architecture du Projet

Bienvenue dans cette documentation ultra-détaillée. Ce guide a pour but de vous transformer en expert de l'architecture PHP moderne en vous expliquant chaque rouage de notre application de blog.

## 1. La Philosophie de l'Architecture

Pourquoi ne pas simplement écrire tout le code dans un seul fichier ? Parce que ce serait un cauchemar à maintenir. Notre architecture suit le principe de **Séparation des Préoccupations (Separation of Concerns)**.

### Les 3 Couches principales :
1.  **La Couche Logique (Contrôleurs)** : Ce sont les fichiers à la racine (`login.php`, `register.php`). Ils reçoivent les données du formulaire, parlent à la base de données et décident quoi faire.
2.  **La Couche de Données (Modèle)** : C'est le dossier `database/` et `app/`. C'est là qu'on définit comment on stocke et on valide les informations.
3.  **La Couche de Présentation (Vues)** : C'est le dossier `resources/views/`. Ici, on ne fait pas de calculs compliqués, on s'occupe juste d'afficher le HTML.

## 2. L'importance des Inclusions (`require_once` vs `include`)

Dans ce projet, vous verrez souvent ces deux fonctions. Voici la différence cruciale à comprendre :

- **`require_once`** : Utilisé pour les fichiers INDISPENSABLES (comme la base de données ou les fonctions de flash). Si le fichier est manquant, PHP arrête tout immédiatement avec une erreur fatale. Le "once" garantit que le fichier n'est pas inclus deux fois, ce qui éviterait des erreurs de "redéfinition de fonction".
- **`include`** : Utilisé pour les morceaux de design (comme le header ou le footer). Si le fichier manque, PHP affiche un simple avertissement mais continue d'afficher le reste de la page.

## 3. Structure Détaillée du Système de Fichiers

Voici une analyse de chaque dossier et de son rôle précis :

-   **`/docs-php-blog-2026/`** : C'est le dossier où vous vous trouvez actuellement. Il contient votre formation complète.
-   **`/app/Emuns/`** : Contient les énumérations. En PHP 8.3, cela permet de définir des listes de valeurs fixes (comme les rôles admin/user) pour éviter les erreurs de frappe.
-   **`/database/`** :
    -   `database.php` : Le seul et unique endroit où l'on configure la connexion à MySQL. Si vous changez de mot de passe de base de données, vous ne le changez qu'ici.
-   **`/resources/views/`** :
    -   `layouts/` : Contient le "cadre" global du site (header, footer, structure HTML).
    -   `users/` : Contient les formulaires de connexion et d'inscription sans aucune logique PHP complexe.
    -   `blog/` : Contient l'affichage des articles du blog.
-   **`flash.php`** : Un utilitaire crucial pour la communication entre les pages (messages de succès ou d'erreur).
-   **`index.php`** : La porte d'entrée principale du site.

## 3. Le Parcours d'une Donnée (Le Workflow)

Imaginons que vous vous inscrivez :
1.  Vous remplissez le formulaire dans `register_html.php`.
2.  Vous cliquez sur "S'inscrire". Les données partent vers `register.php`.
3.  `register.php` inclut `database.php` pour pouvoir écrire dans la base.
4.  `register.php` vérifie vos données (email valide ? mot de passe identique ?).
5.  Si c'est bon, il insère en base et utilise `flash_set()` pour préparer un message de succès.
6.  Il vous redirige vers `login.php`.
7.  `login.php` s'affiche, voit qu'il y a un message flash, et vous l'affiche en vert.

Ce cycle est la base de tout le web moderne. En maîtrisant ce flux, vous pouvez construire n'importe quelle application complexe.
