# 📚 Guide d'Installation - Pour le Professeur

## 🎯 Objectif
Mettre le site en ligne localement avec un serveur pour les images 3D.

---

## 📋 Prérequis
- **Python 3** installé sur l'ordinateur
- Les fichiers du site (dossier complet)

---

## 🚀 Lancer le Serveur Local

### Étape 1 : Ouvrir le terminal PowerShell
1. Appuyez sur `Win + X` et sélectionnez **Terminal Windows (admin)** ou **PowerShell**
2. Naviguez jusqu'au dossier du site :
```powershell
cd "e:\BTS SIO\Rameseyer\Création d'un site Web vitrine"
```

### Étape 2 : Démarrer le serveur Python
Tapez cette commande dans le terminal :
```powershell
python -m http.server 8000
```

Vous devriez voir :
```
Serving HTTP on 0.0.0.0 port 8000 (http://0.0.0.0:8000/)
```

### Étape 3 : Accéder au site
Ouvrez votre navigateur (Chrome, Firefox, Edge) et allez à :
```
http://localhost:8000/index.html
```

---

## 📸 Images 3D
Les images 3D se chargent automatiquement depuis le dossier du serveur. Aucune configuration supplémentaire n'est nécessaire.

---

## ✅ Vérification
- Le site doit s'afficher correctement
- Les images 3D doivent apparaître sans erreur
- Les liens de navigation fonctionnent

---

## 🛑 Arrêter le serveur
Appuyez sur `Ctrl + C` dans le terminal PowerShell.

---

## 📞 Problèmes courants

### ❌ "Port 8000 déjà utilisé"
Utilisez un autre port :
```powershell
python -m http.server 9000
```
Puis accédez à : `http://localhost:9000/index.html`

### ❌ "Python n'est pas reconnu"
Installez Python : https://www.python.org/ (cocher "Add Python to PATH")

### ❌ Les images 3D ne s'affichent pas
Vérifiez que le serveur est bien lancé et que le dossier complet a été copié.

---

**C'est prêt ! 🎉**
