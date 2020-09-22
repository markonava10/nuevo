<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypesRecharge extends Model
{
    // Generales
	protected $table = 'types_recharges';
	protected $fillable =  [
        'spanish',
        'short_spanish',
        'english',
        'short_english'
    ];

 	/*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */
    // Types_Recharges --> Recharges  (Un tipo de Recarga tiene muchas Recargas)
    public function recharges(){
        return $this->hasMany(Recharge::class,'transference_type_id','id');
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Español
    public function scopeSpanish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('spanish','LIKE',"%$valor%");   
        }
    }

    // Corto en Español
    public function scopeShortSpanish($query,$valor)
    {
        if (trim($valor) != "") {
           $query->where('short_spanish','LIKE',"%$valor%");   
        }
    }

    // Inglés
    public function scopeEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('english','LIKE',"%$valor%");   
        }
    }

    // Corto en Español
    public function scopeShortEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('short_english','LIKE',"%$valor%");   
        }
    }

	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista por nombre completo
    public function recharges_types_list(){
    	if (\App::isLocale('es')) {
	 		return $this->orderBy('id')->pluck('spanish', 'id');	
    	} else {
    		 return $this->orderBy('id')->pluck('english', 'id');
    	}
    } 
   
   // Lista por nombre corto
    public function  recharges_types_short_list(){
    	if (App::isLocale('es')) {
	 		return $this->orderBy('id')->pluck('short_spanish', 'id');	
    	} else {
    		 return $this->orderBy('id')->pluck('short_english', 'id');
    	}
    }
}