# 🔐 Guide d'Intégration - Système d'Authentification ShadowWear

## 📋 Vue d'ensemble

Votre site ShadowWear utilise maintenant un système d'authentification **100% côté client** avec localStorage. Aucun serveur, aucune base de données requise.

## 📁 Structure des fichiers

```
/
├── index.html                                  # Page accueil (modifiée)
├── login.html                                  # Connexion
├── register.html                               # Inscription
├── account.html                                # Mon compte
├── admin.html                                  # Panneau admin (nouveau!)
├── setup.html                                  # Configuration admin (nouveau!)
├── js/
│   └── auth.js                                 # Fonctions d'authentification (partagées)
├── styles/
│   └── auth.css                                # Styles d'authentification
└── auth/                                       # Fichiers PHP (tous redirigent maintenant)
    ├── login.php                               # Redirection → login.html
    ├── register.php                            # Redirection → register.html
    ├── account.php                             # Redirection → account.html
    └── logout.php                              # Redirection → login.html
```

## 🚀 Premiers pas

### 1️⃣ Créer un compte administrateur

1. Ouvrez `setup.html` dans votre navigateur
2. Remplissez les informations :
   - **Nom** : Votre nom complet
   - **Email** : admin@shadowwear.com (ou autre)
   - **Mot de passe** : Votre mot de passe sécurisé
3. Cliquez sur "Créer le compte admin"

### 2️⃣ Tester la connexion

1. Allez sur `login.html`
2. Entrez vos identifiants administrateur
3. Vous serez redirigé vers `account.html`

### 3️⃣ Accéder au panneau d'administration

1. Depuis la barre de navigation du site, cliquez sur "Admin"
2. Vous accédez au panneau de gestion des utilisateurs

## 🔗 Intégrations sur le site principal

Les liens suivants ont été modifiés dans `index.html` :

```html
<!-- Avant -->
<a href="auth/login.php">Compte</a>
<a href="admin/index.php">Admin</a>

<!-- Après -->
<a href="login.html">Compte</a>
<a href="admin.html">Admin</a>
```

## 🔐 Fonctionnalités d'authentification

### Page d'inscription (`register.html`)
- ✅ Validation du nom (min 3 caractères)
- ✅ Validation de l'email unique
- ✅ Mot de passe minimum 6 caractères
- ✅ Confirmation du mot de passe
- ✅ Messages d'erreur clairs

### Page de connexion (`login.html`)
- ✅ Vérification des identifiants
- ✅ Sauvegarde de la session
- ✅ Redirection après connexion

### Page du compte (`account.html`)
- ✅ Affichage du profil
- ✅ Modification du profil
- ✅ Changement du mot de passe
- ✅ Bouton déconnexion

### Panneau admin (`admin.html`)
- ✅ Accès réservé aux administrateurs
- ✅ Statistiques des utilisateurs
- ✅ Gestion complète des utilisateurs
- ✅ Export/Import de données
- ✅ Outils d'administration

## 📊 Stockage des données

### localStorage
- **Clé** : `users`
- **Contenu** : Tableau JSON de tous les utilisateurs
- **Structure d'un utilisateur** :
```json
{
  "id": 1708617600000,
  "name": "Admin ShadowWear",
  "email": "admin@shadowwear.com",
  "password": "motdepasse123",
  "role": "admin",
  "createdAt": "2025-02-22T10:00:00.000Z"
}
```

### sessionStorage
- **Clé** : `currentUser`
- **Contenu** : Profil de l'utilisateur actuellement connecté
- **Lifespan** : Jusqu'à la fermeture de l'onglet

## 🛠️ Utilisation des fonctions `js/auth.js`

### Enregistrement d'un utilisateur
```javascript
registerUser('Jean Dupont', 'jean@example.com', 'password123', 'user');
```

### Authentification
```javascript
const user = authenticateUser('jean@example.com', 'password123');
if (user) {
    console.log('Connecté :', user.name);
}
```

### Créer un administrateur
```javascript
createAdminAccount('Admin', 'admin@shadowwear.com', 'admin123');
```

### Promouvoir un utilisateur
```javascript
promoteToAdmin('user@example.com');
```

### Autres fonctions
```javascript
// Obtenir un utilisateur par email
getUserByEmail('test@example.com');

// Changer le mot de passe
changePassword('test@example.com', 'nouveau_mot_de_passe');

// Supprimer un utilisateur
deleteUser('test@example.com');

// Afficher tous les utilisateurs (debug)
debugShowAllUsers();

// Nettoyer toutes les données (attention!)
debugClearAllUsers();
```

## ⚙️ Configuration avancée

### Modifier les messages d'erreur

Ouvrez `register.html`, `login.html`, etc. et cherchez les sections `// Message d'erreur` pour personnaliser les messages.

### Modifier les styles

Editez `styles/auth.css` pour adapter les couleurs, polices, etc.

### Ajouter des champs d'inscription

Modifiez le formulaire dans `register.html` et ajoutez la validation dans le JavaScript.

## ⚠️ Limitations et considérations

1. **localStorage n'est pas sécurisé** :
   - Les mots de passe sont stockés en clair
   - Un utilisateur malveillant peut accéder aux données via la console
   - **Solution en production** : Hasher les mots de passe (bcryptjs côté client)

2. **Données non persistantes sur serveur** :
   - Les données sont perdues si l'utilisateur vide le cache du navigateur
   - **Solution en production** : Synchroniser avec une base de données

3. **Pas d'authentification backend** :
   - Qualqu'un peut modifier les données depuis la console
   - **Solution en production** : Implémenter une API sécurisée

## 🔄 Importer/Exporter des utilisateurs

### Exporter les données (depuis admin.html)
Cliquez sur "▼ Exporter les données" et un fichier JSON sera téléchargé.

### Importer des données (manuel)
```javascript
// Dans la console du navigateur
const users = [
  { id: 1, name: "User1", email: "user1@example.com", password: "pass1", role: "user", createdAt: "2025-02-22T..." },
  // ...
];
localStorage.setItem('users', JSON.stringify(users));
```

## 📱 Responsive Design

Tous les formulaires sont optimisés pour :
- ✅ Desktop (1920x1080+)
- ✅ Tablette (768px - 1024px)
- ✅ Mobile (320px - 767px)

## 🧪 Tester le système

### Compte de test fourni
- **Email** : test@example.com
- **Mot de passe** : password123

Pour créer ce compte, exécutez dans la console du navigateur :
```javascript
registerUser('Utilisateur Test', 'test@example.com', 'password123', 'user');
```

## 📞 Support et dépannage

### Problème : "Accès refusé" au panneau admin
- ✅ Vérifiez que vous êtes connecté avec un compte admin
- ✅ Allez sur `setup.html` pour vérifier les admins existants

### Problème : Les données disparaissent après fermeture
- ✅ C'est normal, localStorage persiste mais sessionStorage non
- ✅ Vérifiez que vous ne videz pas le cache

### Problème : Impossible de modifier le profil
- ✅ Vérifiez que vous êtes connecté (sessionStorage)
- ✅ Rechargez la page si nécessaire

## 🚀 Prochaines étapes

Pour améliorer le système :

1. **Sécurité** :
   - [ ] Hasher les mots de passe (bcryptjs)
   - [ ] Ajouter une validation 2FA
   - [ ] Implémenter un système de token JWT

2. **Fonctionnalités** :
   - [ ] Récupération de mot de passe par email
   - [ ] Photo de profil utilisateur
   - [ ] Historique de connexion

3. **Backend** (futur) :
   - [ ] Créer une API Node.js/PHP
   - [ ] Synchroniser avec une base de données
   - [ ] Ajouter le hachage des mots de passe côté serveur

---

**Créé pour le projet ShadowWear - Site vitrine BTS SIO**
**Système d'authentification 100% client-side avec localStorage**
