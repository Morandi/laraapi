<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Parametri: classe, numero di record
        // create() crea gli utenti e li salva sul db
        // make() restituisce un'array con gli utenti creati, ma non li salva

        // E' possibile passare un'array di parametri al metodo create()
        // per impostare specifici campi degli oggetti creati
        factory(App\User::class, 50)->create();
    }
}