<?php
/**
 * Classe SessionManager
 *
 * Cette classe gère les sessions utilisateur en les ouvrant, vérifiant et détruisant.
 */
class SessionManager {
    /**
     * Constructeur de la classe SessionManager
     *
     * Démarre une session si aucune n'est déjà active.
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Ouvre une session pour un utilisateur donné.
     *
     * @param string $user Nom de l'utilisateur à stocker en session.
     */
    public function openSession($user): void {
        $_SESSION['user'] = $user;
    }

    /**
     * Vérifie si un utilisateur est connecté.
     *
     * @return bool Retourne true si un utilisateur est connecté, sinon false.
     */
    public function isConnected(): bool {
        return isset($_SESSION['user']);
    }

    /**
     * Détruit la session en cours.
     *
     * Supprime toutes les variables de session et détruit la session.
     */
    public function destroySession(): void {
        session_unset();
        session_destroy();
    }
}
?>
