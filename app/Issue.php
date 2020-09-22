<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    // Generales
	protected $table = 'issues';
	protected $fillable =  [
        'issue',
        'short',
        'phone',
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */
      
    // Issues <-- Exchanges: Una empresa emite muchos cheques cambiados
    public function exchanges(){
      return $this->hasMany(Exchange::class,'issue_id','id');
    }


    /*+----------------------------------------------------------+
      | Polimórficas:                                            |
      +----------------------------------------------------------+
    */

    // El encabezado de la transacción
   public function transactions(){
        return $this->morphMany(Transaction::class, 'transactiontable');
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
    // Nombre del servicio
    public function scopeIssue($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('issue','LIKE',"%$valor%");   
        }
    }

    // Nombre corto del servicio
    public function scopeShort($query,$valor)
    {
        if (trim($valor) != "") {
           $query->where('short','LIKE',"%$valor%");   
        }
    }

    // Teléfono
    public function scopePhone($query,$valor)
    {
        if (trim($valor) != "") {
           $query->where('phone','LIKE',"%$valor%");   
        }
    }

	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista de Empresas que expiden cheques
    public function issues_list(){
        return $this->orderBy('issue')->pluck('issue', 'id');
    } 

    // Lista de Empresas que expiden cheques x nombre corto
    public function issues_short_list(){
        return $this->orderBy('short')->pluck('short', 'id');
    } 

    /*+-----------+
      | De Apoyo  |
      +-----------+
    */
    // Usadas por un cliente
    public function issues_used_by_customer($customer_id){
        return $this->whereHas('exchanges', function ($query) use($customer_id){
            $query->wherehas('transactions',function($query) use($customer_id){
            $query->where('customer_id', '=',$customer_id);   
            });
        })
        ->orderby('issue');
    }
}
