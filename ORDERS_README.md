# 📦 Système de Gestion des Commandes et Statistiques

## 🎯 Fonctionnalités Principales

Ce système fournit une solution complète de gestion des commandes et statistiques pour les administrateurs de ShadowWear.

### 1. **Gestion des Commandes** (`js/orders.js`)
- Créer des commandes
- Gérer le panier utilisateur
- Mettre à jour les statuts des commandes
- Récupérer les commandes par utilisateur

### 2. **Dashboard Statistiques** (`stats.html`)
- Vue d'ensemble des KPIs (nombre de commandes, revenu total, etc.)
- Graphiques interactifs avec Chart.js
- Export des données en CSV
- Répartition par statut de commande

### 3. **Fonctionnalités Avancées**
- **Top Clients**: Classement des clients les plus dépensiers
- **Produits populaires**: Produits les plus vendus
- **Tendances**: Graphique sur 7 jours

---

## 📝 Mode d'emploi

### Pour l'Administrateur

#### Accès aux Statistiques
1. Se connecter en tant qu'admin
2. Aller sur la page Admin (`admin.html`)
3. Cliquer sur "📊 Voir les Stats" ou utiliser le lien direct `stats.html`

#### Générer des Données de Test
1. Aller à la page des Outils (`tools.html`)
2. Cliquer sur "Générer les Commandes"
3. Cela crée 30 commandes d'exemple pour tester

#### Comprendre les Données
- **Statuts**: pending (en attente), paid (payée), shipped (expédiée), delivered (livrée), canceled (annulée)
- **Revenu Total**: Somme de toutes les commandes payées/livrées
- **Panier Moyen**: Revenu total / Nombre de commandes

---

## 🛠️ Utilisation Technique

### Créer une Commande Manuellement

```javascript
// Créer une commande
const order = OrdersManager.createOrder(
    userId,           // ID utilisateur
    'client@mail.com', // Email
    [                 // Articles
        {
            productId: 1,
            productName: 'T-Shirt',
            price: 29.99,
            quantity: 2
        }
    ],
    59.98,           // Montant total
    'pending'        // Statut initial
);
```

### Récupérer les Statistiques

```javascript
// Statistiques globales
const stats = OrdersManager.getGlobalStats();

// Top clients
const topClients = OrdersManager.getTopClients(5);

// Produits populaires
const topProducts = OrdersManager.getTopProducts(5);

// Historique sur 7 jours
const weeklyData = OrdersManager.getOrdersByDate(7);
```

### Gérer le Panier

```javascript
// Ajouter un article au panier
OrdersManager.addToCart(userId, {
    productId: 1,
    productName: 'T-Shirt',
    price: 29.99,
    quantity: 1
});

// Obtenir le panier
const cart = OrdersManager.getCart(userId);

// Convertir en commande
const order = OrdersManager.cartToOrder(userId, email);

// Vider le panier
OrdersManager.clearCart(userId);
```

---

## 📊 Fichiers Créés

| Fichier | Description |
|---------|-------------|
| `js/orders.js` | Gestionnaire principal des commandes et statistiques |
| `js/test-data.js` | Générateur de données de test |
| `stats.html` | Dashboard statistiques pour l'admin |
| `tools.html` | Page des outils de développement |

---

## 🔑 Points Importants

### Stockage
- Les données sont stockées dans `localStorage` du navigateur
- Télécharger les données régulièrement pour éviter leur perte

### Sécurité
- Seuls les admins (role === 'admin') peuvent accéder aux statistiques
- Les pages sont protégées par vérification d'authentification

### Performance
- Les graphiques utilisent Chart.js pour une meilleure performance
- Les onglets permettent une meilleure organisation de l'interface

---

## 🚀 Prochaines Étapes

Pour améliorer le système:

1. **Intégration PHP/MySQL**
   - Remplacer localStorage par une vraie base de données
   - Meilleure sécurité et persistance

2. **Fonctionnalités Supplémentaires**
   - Email de confirmation
   - Export PDF
   - Notifications client

3. **Analytics Avancées**
   - Segmentation clients
   - Prédictions de chiffre d'affaires
   - Alertes automatiques

---

## 📞 Support

Pour des questions ou problèmes:
1. Vérifier la console navigateur (F12)
2. Consulter la page des Outils
3. Réinitialiser les données si nécessaire

---

**Système créé pour ShadowWear** | February 2026
