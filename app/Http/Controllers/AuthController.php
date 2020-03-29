<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
// use Illuminate\Suppot\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Protegge le rotte con jwt utilizzando il guard 'api' che e' stato definito in config/auth.php
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        // Si tenta di fare l'autenticazione con il metodo di default di Laravel attempt(),
        // se va a buon fine viene generato il token e viene restituito, altrimenti si ritorna l'errore
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Restituisce i dati dell'utente
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        // Ritorna un array con il token che andra' letto e salvato dal client
        // il tipo di token e la durata della validita'.
        // Il ttl puo' essere letto dal client e tenuto sotto controllo
        // per richiedere un refresh del token quando sta per espirare.
        // Un altro modo per fare il refresh del token e' quello di gestirlo
        // con un middleware che lo fara' in automatico
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            // Aggiungo username ed email alla payload per essere comodo ad utilizzarli nell'app Angular
            'user_name' => auth()->user()->name,
            'email' => auth()->user()->email
        ]);
    }

    public function signup()
    {
        $credentials = request(['name', 'email', 'password']);
        
        // $credentials['password'] = bycript($credentials['password']);
        // Hash::make e' una funzione nativa di php
        $credentials['password'] = \Hash::make($credentials['password']);

        $res = User::create($credentials);
        if(!$res){
            return response()->json(['error' => 'Error creating user'], 500);
        }

        // Si tenta di fare l'autenticazione con il metodo login del package di JWT,
        // se va a buon fine viene generato il token e viene restituito, altrimenti si ritorna l'errore
        if (! $token = auth()->login($res)) {
            return response()->json(['error' => 'Unauthorized 2'], 401);
        }

        return $this->respondWithToken($token);
    }
}