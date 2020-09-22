<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // Generales
	protected $table = 'statuses';
    public $timestamps = false; 
      protected $fillable =  [
                            'status',
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
  
      // Statuses <---authorizations (Una autorizaci´po ntiene un estado)
      public function authorizations(){
          return $this->hasMany(Authorization::class,'status_id','id');
      }
      
      // Statuses <---Transactions (Una transacció tiene un estado)
      public function transactions(){
          return $this->hasMany(Transaction::class,'status_id','id');
      }
      
      // Statuses <---Transferencias (Una transferencia tiene un estado)
      public function transferences(){
          return $this->hasMany(Transference::class,'status_id','id');
      }
  
      /*+-------------------------------------------------------------------+
        | Relación Mucho a Muchos con Role                                  |
        |  Un Proveedor (Provider) puede prestar muchos servicios (Serives) |
        +-------------------------------------------------------------------+
      */
  
      /*+---------------------------------+
        | Búsquedas x diferentes Criterios |
        +----------------------------------+
      */
      // Nombre del Estado
      public function scopeStatus($query,$valor)
      {
          if ( trim($valor) != "") {
             $query->where('status','LIKE',"%$valor%");   
          }
      }
  
      // Nombre corto del Estado
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
      // Lista de status
      public function  statuses_list(){
          return $this->orderBy('status')->pluck('status', 'id');
      } 
     
     // Lista de status nombre corto
      public function  statuses_short_list(){
          return $this->orderBy('short')->pluck('short', 'id');
      }  
}