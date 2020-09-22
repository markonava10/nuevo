<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailDenomination extends Model
{
    protected $table = 'detail_denominations';
	protected $fillable =  [
        'movement_id',
        'denomination_id',
        'quantity'
    ];

  	/*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
  	// detail_movement -->Movement (El detalle pertenece a un movimiento)

  	public function movement()
  	{
  		return $this->belongsTo(Movement::class);
  	}

  	// detail_movement -->Denomination (El detalle es de una denominación)

  	public function denomination()
  	{
  		return $this->belongsTo(Denomination::class);
  	}
}