<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

// use Illuminate\Suppot\Facades\Hash;

class UsersController extends Controller
{
    // Creo il costruttore per poter impostare il middleware auth:api
    // che consente di proteggere tutto il controller in modo che solo
    // gli utenti loggati potranno accedervi.
    // A questo punto il token dovra' essere passato ad ogni richiesta
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Esempio per far vedere che viene mostrato sotto la rotta 'users' definita in web.php il json che parsifica l'Array definito
        // return ['name' => 'Mattia'];
        return response()->json(
            [
                'data'=> User::get(),
                'success'=> true
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Creazione dell'utente
        $data = [];
        $message = '';
        
        try {
            // 1. Cerchiamo l'utente selezionato
            $User = new User();

            // Anche se siamo in creazione lasciamo comunque l'id in except
            // per evitare che qualcuno tenti di farne l'override
            $postData = $request->except('id', '_method');
            $postData['password'] = bcrypt('test');

            // Possibilita' 1
            // Popoliamo i dati e poi salviamo
            $User->fill($postData);
            $success = $User->save();

            // Possibilita' 2
            // Popoliamo i dati e salviamo in un'unica istruzione
            // $success = $User->save($postData);

            $data = $User;
        } catch (\Exception $e) {
            $success = true;
            $message = $e->getMessage();
        }

        return compact('data', 'message', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            // Response e' l'oggetto che viene restituito dalla request.
            // Lo parsifico come json e lo passo  alla pagina
            return response()->json(['data'=> User::findOrFail($id)]);
        }catch(\Exception $e){
            return response()->json(
                [
                    'data'=>[],
                    'message' => $e->getMessage()
                ]
            );
        }

        return compact('data', 'message', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [];
        $message = '';
        
        try {
            // 1. Cerchiamo l'utente selezionato
            $User = User::findOrFail($id);

            // 2. Leggiamo i dati della request
            // Se vogliamo leggerli tutti possiamo usare $request->all();
            // All accetta un'array di chiavi di dati che vogliamo leggere
            // Except accetta un'array di chiavi di dati che NON vogliamo leggere
            $postData = $request->except('id', '_method');

            // In questo esempio la password la inseriamo noi.
            // Laravel cripta le password con la funzione bcrypt
            // $data['password'] = bycript('test');
            // Dalla versione 5.5 di Laravel le password devono essere lunghe almeno 8 caratteri
            // $postData['password'] = Hash::make('test');
            if(empty($postData['password'])){
                $postData['password'] = bcrypt('test');
            }

            $success = $User->update($postData);
            $user = User::findOrFail($id);
            $data = $User;
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return compact('data', 'message', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Cancellazione utente
        $data = [];
        $message = 'User deleted';
        
        try {
            // Possibilita' 1
            // 1. Cerchiamo l'utente selezionato, cosi' se da eccezione possiamo notificarlo
            // 2. Cancelliamo l'utente
            $User = User::findOrFail($id);

            // Solo per debug per assicurarci che l'utente sia passato correttamente
            $data = $User;

            $success = $User->delete();

            // Possibilita' 2
            // $User = User::destroy($id);

            return compact('data', 'message', 'success');
        } catch (\Exception $e) {
            $success = false;
            $message = 'User not found';
        }
    }
}
