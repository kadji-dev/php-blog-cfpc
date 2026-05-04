# Page 3 : Maîtriser les Sessions et le Système de Messages Flash

Les sessions sont la mémoire vive de votre site web. Sans elles, impossible de savoir si un utilisateur est connecté ou de lui transmettre des messages d'une page à l'autre.

## 1. Le mécanisme des Sessions

Quand vous appelez `session_start()`, PHP fait trois choses :
1.  Il vérifie si l'utilisateur possède déjà un cookie nommé `PHPSESSID`.
2.  S'il n'en a pas, il en crée un.
3.  Il ouvre un fichier correspondant sur le serveur pour y stocker vos données.

### Règle d'or :
`session_start()` doit être appelé **AVANT tout affichage HTML** ou espace blanc. C'est pourquoi nous le mettons tout en haut de nos fichiers.

## 2. Le Système de Templating et Inclusions

Le fichier `index.php` illustre parfaitement l'ordre des inclusions :

1.  **`session_start()`** : Initialise la session.
2.  **`require_once 'database/database.php'`** : Connecte le site à la base.
3.  **`require_once 'flash.php'`** : Rend les fonctions d'alerte disponibles.
4.  **`require_once 'resources/views/blog/index_html.php'`** : Charge le contenu de la page d'accueil (dans le tampon `ob_start`).
5.  **`require_once 'resources/views/layouts/blog-layout/blog-layout_html.php'`** : Assemble le tout.

## 3. Le Système Flash (Explications détaillées)

Le problème classique en PHP : vous faites une action (ex: supprimer un article), puis vous redirigez l'utilisateur vers la liste des articles. Comment lui dire "L'article a été supprimé" ? 
La réponse : **Le Flash Message**.

### Analyse du code dans `flash.php` :

```php
<?php
declare(strict_types=1);

/**
 * Enregistre un message flash
 * @param string $type ('success' ou 'error') - Pour choisir la couleur de l'alerte
 * @param string $message - Le texte à afficher
 */
function flash_set(string $type, string $message): void
{
    // On stocke un petit tableau dans la session
    $_SESSION['flash'] = [
        'type' => $type, 
        'message' => $message
    ];
}

/**
 * Récupère le message et le détruit immédiatement
 * C'est le principe du "Flash" : il apparaît puis disparaît.
 */
function flash_get(): ?array
{
    // Si aucune alerte n'est en attente, on renvoie null
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    // On récupère la valeur pour pouvoir la retourner à la fin
    $flash = $_SESSION['flash'];

    // ON DÉTRUIT LA SESSION FLASH ICI
    // C'est ce qui garantit que le message ne s'affichera qu'UNE seule fois.
    unset($_SESSION['flash']);

    return $flash;
}
```

## 3. Comment l'afficher dans le Layout ?

Dans votre fichier de mise en page (`blog-layout_html.php`), nous avons ajouté ce bloc :

```php
<?php if ($flash = flash_get()): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>
```

### Pourquoi c'est génial ?
-   C'est automatique : n'importe quelle page du site affichera l'alerte si elle existe.
-   C'est propre : la logique de suppression est gérée par la fonction `flash_get()`, pas par la vue.

## 4. Astuce pour débutant
Utilisez toujours des noms de types simples comme `'success'` ou `'error'`. Cela vous permet de lier facilement ces noms à des classes CSS (ex: `.alert-success { background: green; }`).
