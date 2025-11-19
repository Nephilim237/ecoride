<?php

namespace Ecoride\Ecoride\Core;

use Ecoride\Ecoride\Models\UserModel;

class Service
{
    protected Session $session;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->session = new Session();
        $this->userModel = new UserModel();
    }

    public function update_user_session($user) {
        $this->session->set_session('user', [
            'id' => $user->user_id,
            'nom' => $user->nom ?? null,
            'prenom' => $user->prenom ?? null,
            'email' => $user->email,
            'pseudo' => $user->pseudo,
            'telephone' => $user->telephone ?? null,
            'adresse' => $user->adresse ?? null,
            'photo' => $user->photo ?? null,
            'date_creation' => $user->date_creation ?? null,
            'roles' => [
                $this->userModel->is_driver($user->user_id) ? 'Chauffeur' : null,
                $this->userModel->is_passenger($user->user_id) ? 'Passager' : null
            ],
            'adminInfo' => $this->userModel->get_role_info($user->user_id) ?? null
        ]);
    }

    public function get_connected_user()
    {
        return $this->session->get_session('user'); // $_SESSION['user']
    }

    public function get_connected_user_id()
    {
        return $this->session->get_session('user')['id'] ?? null; // $_SESSION['user']['id']
    }

    public function is_passenger(): bool
    {
        $user  = $this->get_connected_user();
        return $user && in_array('passager', $user['roles']);
    }

    public function is_driver(): bool
    {
        $user  = $this->get_connected_user();
        return $user && in_array('chauffer', $user['roles']);
    }

    public function is_logged_in(): bool
    {
        return $this->session->has_session('user');
    }

    public function require_guest(): void
    {
        if ($this->is_logged_in()) {
            redirect('profile', ['pseudo' => $this->get_connected_user()['pseudo'] ?? '']);
        }
    }

    public function require_auth(): void
    {
        if (!$this->is_logged_in()) {
            // Message Flash facultatif
            $this->session->set_flash('error', "Acces Refuse");
            redirect('login');
        }
    }

    public function logout(): void
    {
        $this->session->remove_session('user');
        setcookie('remember_me', '', time() - 3600, '/');
        $this->session->destroy_session();
        redirect('/login');
    }

}