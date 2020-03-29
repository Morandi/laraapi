<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Qui si indicano i campi che possono essere modificati dall'utente
        'name', 'email', 'password', 'phone', 'province', 'fiscalcode', 'age', 'lastname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Implementazione dei metodi dell'interfaccia JWTSubject
    public function getJWTIdentifier(){
        // Ritorna l'identificativo del JWT
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        // Qui si posso includere nella payload del token altri valori che possono servirci.
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}
