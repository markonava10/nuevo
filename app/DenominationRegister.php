<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DenominationRegister extends Model
{
    protected $table = 'denomination_register';
    public $timestamps = false;


    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // DENOMINATION_REGISTER --> Denominación (Un registro pertenece a una Denoninación)
  	public function denomination(){
  		return $this->belongsTo(Denomination::class);
  	}

    public function register(){
      return $this->belongsTo(Register::class);
    }

    
	/*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Por Id de la denominación
    public function scopeDenominationId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('denomination_id','=',$valor);      
        }
    }

    // Por id de la caja registradora
    public function scopeRegisterId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('register_id','=',$valor);      
        }
    }
}