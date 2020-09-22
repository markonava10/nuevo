<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypesTransference extends Model
{
    // Generales
	protected $table = 'types_transferences';
	protected $fillable =   [
                            'transference',
                            'short'
                        ];

 	/*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */

    // Types_transferences --> Transferences  (Un tipo de transferencias tiene muchas transferencieas)
    public function transferences(){
        return $this->hasMany(Transference::class,'recharge_type_id','id');
    }
    

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Nombre del Tipo de Transferencia
    public function scopeTransference($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('transference','LIKE',"%$valor%");   
        }
    }

    // Nombre corto del tipo de Transferencia
    public function scopeShort($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('short','LIKE',"%$valor%");   
        }
    }

	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista de Tipos de Transferencia
    public function  type_transferences_list(){
        return $this->orderBy('id')->pluck('transference', 'id');
    } 
   
   // Lista de nombres cortos de tipos de transferencias
    public function  type_transferences_short_list(){
        return $this->orderBy('id')->pluck('short', 'id');
    }
}