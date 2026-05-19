/* ============================================
   FONCTIONS UTILITAIRES POUR L'AUTHENTIFICATION
   ============================================
   
   Ce fichier contient les fonctions partagées
   pour gérer l'authentification avec localStorage.
   
   ============================================ */

/**
 * Vérifie si un email est valide (format)
 * @param {string} email - L'email à vérifier
 * @returns {boolean} - true si valide, false sinon
 */
function isValidEmail(email) {
    // Expression régulière simple pour valider un email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Vérifie si un utilisateur existe déjà dans localStorage
 * @param {string} email - L'email à vérifier
 * @returns {boolean} - true si existe, false sinon
 */
function userExists(email) {
    // Récupérer la liste des utilisateurs de localStorage
    const users = JSON.parse(localStorage.getItem('users')) || [];
    
    // Vérifier si un utilisateur avec cet email existe
    return users.some(user => user.email === email);
}

/**
 * Enregistre un nouvel utilisateur dans localStorage
 * @param {string} name - Nom de l'utilisateur
 * @param {string} email - Email de l'utilisateur
 * @param {string} password - Mot de passe de l'utilisateur
 * @param {string} role - Rôle de l'utilisateur (optionnel, 'user' par défaut)
 * @returns {boolean} - true si succès, false sinon
 */
function registerUser(name, email, password, role = 'user') {
    // Récupérer les utilisateurs existants (ou un tableau vide)
    const users = JSON.parse(localStorage.getItem('users')) || [];
    
    // Créer un nouvel objet utilisateur
    const newUser = {
        id: Date.now(), // ID unique basé sur la timestamp
        name: name,
        email: email,
        password: password, // En production, le mot de passe devrait être hashé !
        role: role, // Rôle : 'user' ou 'admin'
        createdAt: new Date().toISOString()
    };
    
    // Ajouter le nouvel utilisateur à la liste
    users.push(newUser);
    
    // Sauvegarder les utilisateurs dans localStorage
    localStorage.setItem('users', JSON.stringify(users));
    
    console.log('Utilisateur enregistré :', name);
    return true;
}

/**
 * Authentifie un utilisateur (vérifie email et mot de passe)
 * @param {string} email - Email de l'utilisateur
 * @param {string} password - Mot de passe de l'utilisateur
 * @returns {object|null} - L'objet utilisateur s'il est trouvé, null sinon
 */
function authenticateUser(email, password) {
    // Récupérer la liste des utilisateurs
    const users = JSON.parse(localStorage.getItem('users')) || [];
    
    // Chercher un utilisateur avec cet email et ce mot de passe
    const user = users.find(u => u.email === email && u.password === password);
    
    if (user) {
        console.log('Authentification réussie pour :', email);
        return user; // Retourner l'utilisateur trouvé
    } else {
        console.log('Authentification échouée pour :', email);
        return null; // Pas d'utilisateur trouvé
    }
}

/**
 * Récupère un utilisateur par son email
 * @param {string} email - Email de l'utilisateur
 * @returns {object|null} - L'objet utilisateur s'il existe, null sinon
 */
function getUserByEmail(email) {
    const users = JSON.parse(localStorage.getItem('users')) || [];
    return users.find(u => u.email === email) || null;
}

/**
 * Change le mot de passe d'un utilisateur
 * @param {string} email - Email de l'utilisateur
 * @param {string} newPassword - Nouveau mot de passe
 * @returns {boolean} - true si succès, false sinon
 */
function changePassword(email, newPassword) {
    const users = JSON.parse(localStorage.getItem('users')) || [];
    const userIndex = users.findIndex(u => u.email === email);
    
    if (userIndex === -1) {
        return false; // Utilisateur non trouvé
    }
    
    users[userIndex].password = newPassword;
    localStorage.setItem('users', JSON.stringify(users));
    console.log('Mot de passe changé pour :', email);
    return true;
}

/**
 * Supprime un utilisateur de localStorage
 * @param {string} email - Email de l'utilisateur
 * @returns {boolean} - true si succès, false sinon
 */
function deleteUser(email) {
    const users = JSON.parse(localStorage.getItem('users')) || [];
    const filteredUsers = users.filter(u => u.email !== email);
    
    if (filteredUsers.length === users.length) {
        return false; // Utilisateur non trouvé
    }
    
    localStorage.setItem('users', JSON.stringify(filteredUsers));
    console.log('Utilisateur supprimé :', email);
    return true;
}

/**
 * Affiche tous les utilisateurs enregistrés (pour le debug)
 * ATTENTION : À utiliser uniquement en développement !
 */
function debugShowAllUsers() {
    const users = JSON.parse(localStorage.getItem('users')) || [];
    console.table(users);
}

/**
 * Réinitialise complètement la base de données localStorage
 * ATTENTION : Cette action supprime TOUS les utilisateurs !
 */
function debugClearAllUsers() {
    if (confirm('Êtes-vous sûr ? Cette action supprimera TOUS les utilisateurs enregistrés.')) {
        localStorage.removeItem('users');
        console.log('Base de données réinitialisée');
    }
}

/**
 * Crée un utilisateur administrateur
 * ATTENTION : À utiliser uniquement une fois pour initialiser un admin
 * @param {string} name - Nom de l'admin
 * @param {string} email - Email de l'admin
 * @param {string} password - Mot de passe de l'admin
 * @returns {boolean} - true si succès, false sinon
 */
function createAdminAccount(name, email, password) {
    // Vérifier si l'utilisateur existe déjà
    if (userExists(email)) {
        console.warn('Cet email est déjà utilisé');
        return false;
    }

    // Enregistrer l'utilisateur avec le rôle 'admin'
    const users = JSON.parse(localStorage.getItem('users')) || [];
    const adminUser = {
        id: Date.now(),
        name: name,
        email: email,
        password: password,
        role: 'admin',
        createdAt: new Date().toISOString()
    };

    users.push(adminUser);
    localStorage.setItem('users', JSON.stringify(users));
    console.log('Compte administrateur créé :', email);
    return true;
}

/**
 * Promotionne un utilisateur en administrateur
 * @param {string} email - Email de l'utilisateur
 * @returns {boolean} - true si succès, false sinon
 */
function promoteToAdmin(email) {
    const users = JSON.parse(localStorage.getItem('users')) || [];
    const userIndex = users.findIndex(u => u.email === email);

    if (userIndex === -1) {
        return false;
    }

    users[userIndex].role = 'admin';
    localStorage.setItem('users', JSON.stringify(users));
    console.log('Utilisateur promu admin :', email);
    return true;
}

/**
 * Rétrograder un administrateur en utilisateur standard
 * @param {string} email - Email de l'administrateur
 * @returns {boolean} - true si succès, false sinon
 */
function demoteFromAdmin(email) {
    const users = JSON.parse(localStorage.getItem('users')) || [];
    const userIndex = users.findIndex(u => u.email === email);

    if (userIndex === -1) {
        return false;
    }

    users[userIndex].role = 'user';
    localStorage.setItem('users', JSON.stringify(users));
    console.log('Administrateur rétrogradé :', email);
    return true;
}

// ============================================
// EXEMPLE D'UTILISATION (à commenté après test)
// ============================================

// Exemple : enregistrer un utilisateur
// registerUser('Jean Dupont', 'jean@example.com', 'motdepasse123');

// Exemple : authentifier un utilisateur
// const user = authenticateUser('jean@example.com', 'motdepasse123');
// console.log(user);

// Exemple : afficher tous les utilisateurs (debug)
// debugShowAllUsers();
