<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typesid extends Model
{
    // Generales
	protected $table = 'typesids';
	protected $fillable = [
		'description',
	];

	/*+-----------------------------+
		      | Relaciones entre tablas     |
		      +-----------------------------+
	*/

	/*+----------------------------------------------------------+
		      | Muchos - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
		      +----------------------------------------------------------+
	*/

	//  Country <---customers (Un Tipo está en muchos clientes)
	public function customers() {
		return $this->hasMany(Customer::class, 'typeid_id', 'id');
	}

	// Typesids <---> Country   (Muchos a muchos)
	public function typesids() {
		return $this->belongsToMany(Country::class, 'country_typeid', 'typeid_id', 'country_id');
	}

	/*+---------------------------------+
		  | Búsquedas x diferentes Criterios |
		  +----------------------------------+
	*/
	// Description
	public function scopeDescription($query, $valor) {
		if ( trim($valor) != "") {
			$query->where('description', 'LIKE', "%$valor%");
		}
	}

	/*+---------+
		  | Listas  |
		  +---------+
	*/
	// Lista de Países
	public function typesids_list() {
		return $this->orderBy('id')->pluck('description', 'id');
	}
}
