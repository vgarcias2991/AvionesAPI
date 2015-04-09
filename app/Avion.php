<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Avion extends Model {

	// Definir la tabla MySQL que usara este modelo
	protected $table="aviones";

	// Clave primaria de la tabla aviones.
	// En este caso es el campo serie, por lo tanto hay que indicarlo.
	// Si no se indica, por defecto seria cun campo llamado "id"
	protected $primaryKey='serie';

	// Atributos de la tabla que se pueden rellenar de forma masiva.
	protected $fillable=array('modelo','longitud','capacidad','velocidad','alcance');

	// Campos que no queremos que se devuelvan en las consultas.
	protected $hidden=['create_at','updated_at'];

	// Definimos la relacion de Avion con Fabricante.
	public function fabricante(){

		// 1 avion pertenece a 1 fabricante.
		return $this->belongsTo('App\Fabricante');

	}

}
