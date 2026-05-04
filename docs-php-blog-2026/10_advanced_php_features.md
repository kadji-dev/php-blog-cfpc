# Page 10 : Utiliser la Puissance de PHP 8.3 et 8.4

PHP a énormément évolué. Ce projet utilise les fonctionnalités les plus récentes pour garantir un code performant et moderne.

## 1. Le Typage Strict (`strict_types=1`)

C'est la première ligne de presque tous nos fichiers.
`declare(strict_types=1);`

**Pourquoi ?**
Sans cela, si vous donnez le chiffre `10` à une fonction qui attend du texte, PHP va le transformer en `"10"` sans rien dire. Avec le typage strict, il génère une erreur. Cela vous force à écrire un code beaucoup plus rigoureux et facile à tester.

## 2. Les Enums (Nouveauté PHP 8.1+)

Fini les chaînes de caractères "admin" ou "user" qui traînent partout. Nous utilisons une Énumération dans `app/Emuns/Role.php` :

```php
enum Role: string {
    case ADMIN = 'admin';
    case USER = 'user';
}
```

### Avantage :
Si vous faites une faute de frappe (`'admn'`), PHP vous le signalera immédiatement car cette valeur n'existe pas dans l'Enum. C'est une sécurité monumentale pour la gestion des droits.

## 3. L'Opérateur Null Coalescing (`??`)

Très présent dans nos formulaires :
`$email = $_POST['email'] ?? '';`

C'est un raccourci pour :
`$email = isset($_POST['email']) ? $_POST['email'] : '';`
C'est plus court, plus lisible et très efficace en PHP 8.3.

## 4. Les Propriétés de Promotion (Constructor Promotion)

Bien que nous soyons en procédural, si vous créez des classes, PHP 8+ permet d'écrire beaucoup moins de code :

```php
// Avant PHP 8
class User {
    public string $name;
    public function __construct(string $name) {
        $this->name = $name;
    }
}

// PHP 8+ (Promotion)
class User {
    public function __construct(public string $name) {}
}
```

## 5. Conclusion Finale de votre Documentation

Vous avez maintenant entre les mains un projet qui respecte les standards de 2026. 
-   **Sécurité** : PDO, password_hash, htmlspecialchars.
-   **Architecture** : Templating, séparation Logique/Vue.
-   **Modernité** : PHP 8.3+, Enums, Typage strict.

En maîtrisant ces 10 pages, vous avez acquis les bases nécessaires pour travailler sur des projets professionnels de grande envergure ou pour migrer facilement vers des frameworks comme Laravel ou Symfony.

**Bon codage !**
