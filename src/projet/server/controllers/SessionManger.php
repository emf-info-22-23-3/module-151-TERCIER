<?php
class SessionManager {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function openSession($user): void {
        $_SESSION['user'] = $user;
    }

    public function isConnected(): bool {
        return isset($_SESSION['user']);
    }

    public function destroySession(): void {
        session_unset();
        session_destroy();
    }
}
?>
