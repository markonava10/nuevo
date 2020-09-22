<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    // Generales
	protected $table = 'banks';
	protected $fillable =  [
        'bank',
        'short',
    ];
   


    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

      
    // Banks <-- Exchanges: Un banco emite muchos cheques cambiados
    public function exchanges(){
      return $this->hasMany(Bank::class,'bank_id','id');
    }
    /*+----------------------------------------------------------+
      | Polimórficas:                                            |
      +----------------------------------------------------------+
    */

    // El encabezado de la transacción
   public function transactions(){
        return $this->morphMany(Transaction::class, 'transactiontable');
    }


    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */
    

    /*+-------------------------------------------------------------------+
      | Relación Mucho a Muchos con Role                                  |
      |  Un Proveedor (Provider) puede prestar muchos servicios (Serives) |
      +-------------------------------------------------------------------+
    */

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Nombre del servicio
    public function scopeBank($query,$valor)    {
        if(trim($valor) != ""){
           $query->where('bank','LIKE',"%$valor%");   
        }
    }

    // Nombre corto del servicio
    public function scopeShort($query,$valor){
        if(trim($valor) != ""){
           $query->where('short','LIKE',"%$valor%");   
        }
    }
	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista de bancos por nombre
    public function banks_list(){
        return $this->orderBy('bank')->pluck('bank', 'id');
    } 

    // Lista de bancos por nombre corto
    public function banks_short_list(){
        return $this->orderBy('short')->pluck('short', 'id');
    } 
}
