<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model implements AuthenticatableContract
{
    use AuthAuthenticatable;

    use HasFactory;

    // Implémentation des méthodes requises pour Authenticatable
    public function getAuthIdentifierName()
    {
        return 'id'; // Remplacez 'id' par le nom de la colonne d'identification dans votre table admins
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return $this->mdp_admin; // Remplacez 'mdp_admin' par le nom de la colonne contenant le mot de passe dans votre table admins
    }

    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}

