<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ctr extends Model
{
    protected $table = 'ctrs';
	protected $fillable = [
		'customer_id',
		'service_id',
		'from_date',
		'to_date',
		'quantity',
		'total',
	];

	/*+----------------------------------------------------------+
		      | Uno - Uno: Un Total tiene asociado otro registro       |
		      +----------------------------------------------------------+
	*/

	public function customer() {
		return $this->belongsTo(Customer::class);
	}

	// Un total pertenece a un cservicio
	public function service() {
		return $this->belongsTo(Service::class);
	}

	/*+---------------------------------+
		      | Búsquedas x diferentes Criterios |
		      +----------------------------------+
	*/

	// De un cliente
	public function scopeCustomerId($query, $valor) {
		if ( trim($valor) != "") {
			$query->where('customer_id', '=', $valor);
		}
	}

	// De un servicio
	public function scopeServiceId($query, $valor) {
		if ( trim($valor) != "") {
			$query->where('service_id', '=', $valor);
		}
	}

	/*+---------------------------------------------+
		      | Funciones de apoyo                          |
		      +---------------------------------------------+
	*/

	// ¿Está excedido?
	public function is_exceeded() {
		return $this->total >= 10000;
	}

	public function DeleteServiceRecords($service_id) {
		//DB::table('users')->where('votes', '>', 100)->delete();
		return $this->where('service_id', '=', $service_id)->delete();
	}
}