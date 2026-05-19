<?php
/**
 * REDIRECTION - SYSTÈME D'AUTHENTIFICATION MIGRÉ
 * 
 * Ce fichier a été remplacé par un système cliente localStorage
 * Les nouvelles pages d'authentification sont en HTML/CSS/JavaScript
 */

// Redirection vers la page de connexion dans localStorage
header('Location: ../login.html');
exit;

redirect_to(BASE_PATH . 'auth/login.php');
