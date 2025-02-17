<?php 
class SessionManager {
    private static SessionManager $instance = null;

    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getInstance(): SessionManager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
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

    private function __clone() {}
    private function __wakeup() {}
}
?>