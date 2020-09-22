<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    // Generales
	protected $table = 'occupations';
	protected $fillable = [
		'spanish',
		'short_spanish',
		'english',
		'short_english',
	];
	public $timestamps = false;
	/*+-----------------------------+
		      | Relaciones entre tablas     |
		      +-----------------------------+
	*/

	// Occupation <--- customers (Una ocupación está para muchos clientes)
	public function customers() {
		return $this->hasMany(Customer::class, 'occupation_id', 'id');
	}

	/*+---------------------------------+
		      | Búsquedas x diferentes Criterios |
		      +----------------------------------+
	*/

	// Nombre en Español
	public function scopeSpanish($query, $valor) {
		if (trim($valor) != "") {
			$query->where('spanish', 'LIKE', "%$valor%");
		}
	}

	// Nombre en Inglés
	public function scopeEnglish($query, $valor) {
		if (trim($valor) != "") {
			$query->where('english', 'LIKE', "%$valor%");
		}
	}

	// Nombre cortoen Español
	public function scopeShortSpanish($query, $valor) {
		if (trim($valor) != "") {
			$query->where('short_spanish', 'LIKE', "%$valor%");
		}
	}

	// Nombre cortoen Inglés
	public function scopeShortEnglish($query, $valor) {
		if (trim($valor) != "") {
			$query->where('short_english', 'LIKE', "%$valor%");
		}
	}

	/*+---------+
		      | Listas  |
		      +---------+
	*/
	// Lista de ocupaciones según el lenguaje establecido
	public function occupations_list() {
		if (app()->getLocale() == 'es') {
			return $this->orderBy('spanish')->pluck('spanish', 'id');
		} else {
			return $this->orderBy('english')->pluck('english', 'id');
		}
	}
}
