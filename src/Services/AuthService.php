<?php

namespace Ecoride\Ecoride\Services;


use Ecoride\Ecoride\Core\Session;
use Ecoride\Ecoride\Lib\Helpers;
use Ecoride\Ecoride\Models\UserModel;

class AuthService
{
    private Session $session;
    private UserModel $userModel;

    public function __construct()
    {
        $this->session = new Session();
        $this->userModel = new UserModel();
    }

    public function register(array $userData): bool
    {
        // Hasher le mot de passe
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);

        // Validation des donnees
        if (!$this->validate_registration($userData)) {
            return false;
        }

        return $this->userModel->create($userData);
    }

    private function validate_registration(array $userData): bool {
        // Verifier que l'email n'est pas encore utilise
        if ($this->userModel->email_exists($userData['email'])) {
            $this->session->set_flash('error', 'Cet email est deja utilise.');
            return false;
        }


        // Verifier que le pseudo n'est pas encore utilise
        if ($this->userModel->pseudo_exists($userData['pseudo'])) {
            $this->session->set_flash('error', 'Ce pseudo est deja utilise');
            return false;
        }

        // Validation de l'email
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)){
            $this->session->set_flash('error', 'Email invalide');
            return false;
        }

        return true;
    }

    public function attempt_to_connect(string $identifier, string $password, bool $remember = false): bool
    {
        $user = $this->userModel->find_by_username_or_email($identifier);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            // Si le mot de passe fourni dans le formulaire match avec le mot de passe en BDD => Connexion reussie
            $this->login($user);
            return true;
        }

        // Gerer le "Remember me"
        if ($remember) {
            $this->set_remember_token($user->id);
        }

        return true;
    }

    private function set_remember_token(int $userId): void
    {
        $token = bin2hex(random_bytes(32));
        $this->userModel->update_remember_token($userId, $token);

        // Cookie valable 30 jours
        setcookie('remember_me', $token, time() + 60 * 60 * 24 * 30, '/', '', false, true);
    }

    public function login_with_remember_token():bool {
        if(isset($_COOKIE['remember_me'])) {
            $user = $this->userModel->find_by_remember_token($_COOKIE['remember_me']);

            if ($user) {
                $this->session->set_session('user', [
                    'id' => $user->user_id,
                    'email' => $user->email,
                    'pseudo' => $user->pseudo,
                    'nom' => $user->nom ?? '',
                    'prenom' => $user->prenom ?? '',
                    'roles' => $this->userModel->get_user_roles($user->user_id)
                ]);
                return true;
            }
        }
        return false;
    }

    /**
     * Connecter un utilisateur
     * Sauvegarde ses infos en Session
     * @param $user
     * @return void
     */
    public function login($user): void {

        $this->session->set_session('user', [
            'id' => $user->user_id,
            'email' => $user->email,
            'pseudo' => $user->pseudo,
            'nom' => $user->nom ?? '',
            'prenom' => $user->prenom ?? '',
            'roles' => $this->userModel->get_user_roles($user->user_id)
        ]);
    }


    public function logout(): void
    {
        $this->session->remove_session('user');
        setcookie('remember_me', '', time() - 3600, '/');
        $this->session->destroy_session();
        redirect('/');
    }

    public function get_connected_user() {
        return $this->session->get_session('user');
    }

    public function get_connected_user_id() {
        $user = $this->get_connected_user();
        return $user ? $user['id'] : null;
    }

    public function check(): bool
    {
        return $this->session->has_session('user');
    }

    public function is_driver(): bool
    {
        $user = $this->get_connected_user();
        return $user && in_array('chauffeur', $user['roles']);
    }

    public function is_passenger(): bool
    {
        $user = $this->get_connected_user();
        return $user && in_array('passager', $user['roles']);
    }

    /**
     * Middleware de protection des routes
     * @return void
     */
    public function require_auth(): void
    {
        if ($this->check()) {
            $this->session->set_flash('error', 'Acces refuse');
            redirect('/login');
        }
    }

    /**
     * Middleware pour les utilisateur deja connectes
     * @return void
     */
    public function require_guest(): void
    {
        if ($this->check()) {
            redirect('/profile');
        }
    }

    /**
     * Middleware pour les chauffeurs uniquement
     * @return void
     */
    public function require_driver(): void
    {
        $this->require_auth();
        if (!$this->is_driver()) {
            $this->session->set_flash('error', 'Acces refuse');
            redirect('/');
        }
    }

}