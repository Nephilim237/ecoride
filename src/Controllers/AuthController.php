<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;
use Ecoride\Ecoride\Core\Session;
use Ecoride\Ecoride\Services\AuthService;

class AuthController extends Controller
{
    public function handle_register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $userData = [
            'pseudo' => htmlspecialchars($_POST['pseudo']) ?? '',
            'email' => htmlspecialchars($_POST['email']) ?? '',
            'password' => htmlspecialchars($_POST['password']) ?? '',
            'password_confirm' => htmlspecialchars($_POST['password_confirm']) ?? '',
            'credits' => 20
        ];

        // Validation du mot de passe
        if ($userData['password'] !== $userData['password_confirm']) {
            $this->session->set_flash('error', "Les deux mots de passes ne correspondent pas.");
            $this->redirect('/register');
        }

        if (mb_strlen($userData['password']) < 8) {
            $this->session->set_flash('error', "Le mot de passe doit contenir au moins 8 caracteres.");
            $this->redirect('/register');
        }

        // Supprimer la confirmation du mot de passe
        unset($userData['password_confirm']);

        if ($this->auth->register($userData)) {
            $this->session->set_flash('success', "Inscription reussie!");
            $this->redirect('/login');
        } else {
            $this->redirect('/register');
        }
    }

    public function register(): void
    {
        $this->renderView('auth/register', [
            'title' => "Inscription | " . APP_NAME
        ]);
    }

    public function login(): void
    {
        $this->renderView('auth/login', [
            'title' => "Inscription | " . APP_NAME
        ]);
    }

    public function handle_login(): void
    {
        // Si la page n'est pas accedee en POST, on redirige, il y a violation de protocol
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $identifier = sanitize($_POST['pseudo'] ?? '');
        $password = sanitize($_POST['password'] ?? '');

        if (empty($identifier) || empty($password)) {
            $this->session->set_flash('error', "Veuillez remplir tous les champs");
            $this->redirect('/login');
        }

        $remember = isset($_POST['remember_me']);
        // On veut une fonction qui fait toutes les validations possible dans le service
        if ($this->auth->attempt_to_connect($identifier, $password, $remember)) {
            // Si cette function renvoie un test positif (bool=true), on connecte l'utilisateur et on le redirige vers
            // Sa page de profil
            $this->session->set_flash('success', 'Connexion reussie.');
            $this->redirect('/profile', ['pseudo' => $identifier]);
        } else {
            // Sinon, on redirige vers le formulaires de connexion avec les erreurs
            $this->session->set_flash('error', 'Pseudo ou mot de passe incorrect.');
            $this->redirect('/login');

        }
    }

    public function logout(): void
    {
        $this->auth->logout();
    }
}