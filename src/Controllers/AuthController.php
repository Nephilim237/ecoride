<?php

namespace Ecoride\Ecoride\Controllers;

use Ecoride\Ecoride\Core\Controller;
use Ecoride\Ecoride\Core\Session;
use Ecoride\Ecoride\Services\AuthService;

class AuthController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function register(): void
    {
        $this->auth->require_guest();

        $this->renderView('auth/register', [
            'title' => 'Inscription | '. APP_NAME
        ]);
    }

    /**
     * Logique metier de l'inscription
     * @return void
     */
    public function handle_register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            $this->redirect('/login');
        }

        // Recuperation et validation des donnees
        $userData = [
            'pseudo' => $_POST['pseudo'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
        ];

        // Validation du mot de passe
        if ($userData['password'] !== $userData['password_confirm']) {
            $this->session->set_flash('error', 'Les deux mots de passe ne correspondent pas');
            $this->redirect('/register');
        }

        if (mb_strlen($userData['password'] < 8)) {
            $this->session->set_flash('error', 'Le mot de passe doit contenir au moins 8 caracteres');
            $this->redirect('/register');
        }

        // Supprimer la confirmation du mot de passe
        unset($userData['password_confirm']);

        if ($this->auth->register($userData)) {
            $this->session->set_flash('success', "Inscription reussie! Vous pouvez maintenant vous connecter.");
            $this->redirect('/login');
        }else {
            $this->redirect('/register');
        }
    }

    /**
     * Afficher le formulaire de connexion
     * @return void
     */
    public function login(): void
    {
        $this->auth->require_guest();

        $this->renderView('auth/login', [
            'title' => 'Connexion | '. APP_NAME
        ]);
    }

    /**
     * Logique metier de connexion
     * @return void
     */
    public function handle_login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            $this->redirect('/login');
        }

        $pseudo = $_POST['pseudo'] ?? '';
        $password = $_POST['password'] ?? '';

        // var_dump($pseudo, $password);
        // exit();

        if (empty($pseudo) || empty($password)) {
            $this->session->set_flash('error', "Veuillez remplir tous les champs");
            $this->redirect('/login');
        }

        if ($this->auth->attempt_to_connect($pseudo, $password)) {
            $this->session->set_flash('success', 'Connexion reussie !');
            $this->redirect('/');
        } else {
            $this->session->set_flash('error', "Pseudo ou mot de passe incorrect.");
            $this->redirect('/login');
        }
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->session->set_flash('success', 'Vous etes maintenant deconnecte.');
        $this->redirect('/');
    }

}