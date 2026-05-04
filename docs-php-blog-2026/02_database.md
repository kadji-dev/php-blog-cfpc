# Page 2 : La Gestion de la Base de Données (L'Expertise PDO)

Dans cette page, nous allons décortiquer chaque ligne du fichier `database/database.php` pour comprendre pourquoi elle est indispensable.

## 1. Pourquoi PDO au lieu de mysqli ?
PDO est plus moderne, plus sécurisé et surtout, il est orienté objet. Il permet de changer de type de base de données (passer de MySQL à PostgreSQL par exemple) presque sans changer de code.

- **`require_once 'database/database.php'`** : Cette ligne est présente au début de chaque fichier nécessitant un accès SQL. On utilise `require_once` car sans ce fichier, aucune requête ne peut fonctionner.

## 2. Analyse ligne par ligne du code

```php
<?php
// On force le typage strict pour éviter les erreurs bizarres
declare(strict_types=1);

// Utilisation de constantes : elles sont globales et immuables
define('DB_SERVERNAME', '127.0.0.1'); // L'adresse du serveur (localhost)
define('DB_USERNAME', 'valet');      // Votre nom d'utilisateur SQL
define('DB_PASSWORD', 'valet');      // Votre mot de passe SQL
define('DB_DATABASE', 'blog-cfpc-2026'); // Le nom de votre base

try {
    // Création de l'objet PDO
    // L'argument 'charset=utf8' est CRUCIAL pour ne pas avoir de problèmes avec les accents
    $pdo = new PDO(
        'mysql:host='.DB_SERVERNAME.';dbname='.DB_DATABASE.';charset=utf8', 
        DB_USERNAME, 
        DB_PASSWORD
    );

    // Configuration des attributs PDO
    
    // 1. Gestion des erreurs : on veut que PHP lève une Exception (erreur fatale)
    // si une requête SQL est mal écrite. C'est beaucoup plus facile pour débugger.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Mode de récupération par défaut : on veut récupérer nos données 
    // sous forme de tableau associatif (ex: $user['email'])
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si la connexion échoue, on arrête tout et on affiche l'erreur
    // En production, on cacherait le message détaillé pour la sécurité
    die("<div style='color:red;'>Échec de la connexion à la base :</div> " . $e->getMessage());
}
```

## 3. Les Requêtes Préparées : Le Bouclier Anti-Piratage

C'est le concept le plus important. **Ne concaténez jamais de variables dans vos requêtes SQL.**

**Mauvaise pratique (DANGEREUX) :**
`"SELECT * FROM users WHERE email = '$email'"` -> Un pirate peut injecter du code SQL ici.

**Bonne pratique (SÉCURISÉ) :**
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
```
Ici, PDO nettoie automatiquement la variable `$email`, rendant toute attaque impossible.

## 4. Conclusion sur la couche de données
Le fichier `database.php` ne doit être inclus qu'une seule fois par page via `require_once`. L'objet `$pdo` devient alors disponible partout dans votre script pour effectuer vos opérations de lecture et d'écriture.
