# 🔐 Instructions de Connexion - ShadowWear

## ⚠️ IMPORTANT : Première utilisation

Votre système d'authentification utilise le **localStorage du navigateur**. Avant de pouvoir vous connecter, vous devez **initialiser les comptes utilisateurs**.

---

## 🚀 Étapes pour se connecter

### Étape 1️⃣ : Initialiser les comptes (À FAIRE UNE SEULE FOIS)

1. **Ouvrez votre navigateur** et accédez à :
   ```
   http://127.0.0.1:5500/init-users.html
   ```

2. **Cliquez sur le bouton** : 
   ```
   🚀 Créer les comptes par défaut
   ```

3. **Deux comptes seront créés automatiquement** :
   - 👑 **Admin** : `admin@shadowwear.com` / `admin123`
   - 👤 **Utilisateur** : `user@shadowwear.com` / `user123`

---

### Étape 2️⃣ : Se connecter

Une fois les comptes créés, vous pouvez vous connecter :

#### 🔹 Connexion Utilisateur Normal
1. Allez sur : `http://127.0.0.1:5500/login.html`
2. Utilisez les identifiants :
   - **Email** : `user@shadowwear.com`
   - **Mot de passe** : `user123`
3. Vous serez redirigé vers `account.html`

#### 🔹 Connexion Admin
1. Allez sur : `http://127.0.0.1:5500/login.html`
2. Utilisez les identifiants :
   - **Email** : `admin@shadowwear.com`
   - **Mot de passe** : `admin123`
3. Une fois connecté, allez sur : `http://127.0.0.1:5500/admin.html`

---

## 🔗 Liens rapides

- 🏠 **Site principal** : http://127.0.0.1:5500/index.html
- 🔧 **Initialisation** : http://127.0.0.1:5500/init-users.html
- 🔑 **Connexion** : http://127.0.0.1:5500/login.html
- 📝 **Inscription** : http://127.0.0.1:5500/register.html
- 👤 **Mon compte** : http://127.0.0.1:5500/account.html
- 👑 **Admin** : http://127.0.0.1:5500/admin.html

---

## 🛠️ Dépannage

### ❌ "Email ou mot de passe incorrect"
➡️ **Solution** : Les comptes n'ont pas été créés. Retournez à l'Étape 1.

### ❌ "Accès refusé" sur la page admin
➡️ **Solution** : Connectez-vous avec le compte admin (`admin@shadowwear.com`)

### ❌ Je veux réinitialiser tous les comptes
➡️ **Solution** : 
1. Allez sur `http://127.0.0.1:5500/init-users.html`
2. Cliquez sur "🗑️ Réinitialiser tout"
3. Recréez les comptes avec "🚀 Créer les comptes par défaut"

---

## 📌 Notes techniques

- ✅ Le système utilise **localStorage** (stockage local du navigateur)
- ✅ Les mots de passe sont stockés en clair (seulement pour le développement)
- ✅ Chaque navigateur a son propre localStorage
- ✅ Les données persistent même après fermeture du navigateur
- ✅ Utilisez F12 → Console pour voir les logs d'authentification

---

## 💡 Astuce développeur

Ouvrez la console (F12) et tapez :
```javascript
// Voir tous les utilisateurs
JSON.parse(localStorage.getItem('users'))

// Voir l'utilisateur connecté
JSON.parse(sessionStorage.getItem('currentUser'))
```
