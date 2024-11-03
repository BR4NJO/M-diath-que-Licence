<?php

class AuthController {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    private function validatePassword(string $password, string $username): bool {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        
        if (!preg_match($pattern, $password)) {
            throw new \Exception("Le mot de passe doit contenir au moins 8 caractères, avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.");
        }
        
        if (stripos($password, $username) !== false) {
            throw new \Exception("Le mot de passe ne doit pas contenir le nom d'utilisateur.");
        }

        return true;
    }

    public function register(string $username, string $password): bool {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->rowCount() > 0) {
            throw new \Exception("Ce nom d'utilisateur est déjà pris.");
        }

        $this->validatePassword($password, $username);

        $user = new User($username, password_hash($password, PASSWORD_BCRYPT));

        $stmt = $this->db->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $user->getUsername(),
            'password' => $user->getPassword()
        ]);

        return true;
    }

    public function login(string $username, string $password): bool {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(['username' => $username]);
        
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        if ($userData) {
            $user = new User($userData['username'], $userData['password']);
    
            $this->validatePassword($password, $username);
    
            if ($user->checkPassword($password)) {
                $_SESSION['username'] = $user->getUsername();
                return true;
            }
        }
    
        return false;
    }
}
