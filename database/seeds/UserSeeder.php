<?php

use Illuminate\Database\Seeder;

// Hace uso del modelo de Avion.
use App\User;

class UserSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::create([
			'email'=>'test@test.es',
			'password'=>Hash::make('abc123.')
			]);

	}

}