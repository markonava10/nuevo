<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    protected $table = 'denominations';
	protected $fillable =  [
        'spanish',
        'english',
        'type',
        'value',
        'qty_by_wad'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */


    // Denominations <--- Detail_denominations (Una denominación tiene muchos detalles)
    public function detail_denominations(){
        return $this->hasMany(Detail_Denomination::class,'denomination_id','id');
    }


    /*+----------------------------------------------------------+
      | Relación Mucho a Muchos                                  |
      +----------------------------------------------------------+
    */

    // Una denoninación (Denomination) en un contero final esar en muchas cajas (Register)
    public function registers()
    {
        return $this->belongsToMany(Register::class)->withPivot('quantity');
    }

    public function registers_by_register_id()
    {
        return $this->belongsToMany(Register::class)->withPivot('quantity')
                    ->orderby('register_id');
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Nombre de la denominación en español
    public function scopeSpanish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('spanish','LIKE',"%$valor%");   
        }
    }

    // Nombre de la denominación en inglés
    public function scopeEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('english','LIKE',"%$valor%");   
        }
    }

    // Tipo de Denominación: B=Bill C=Coin
    public function scopeType($query,$valor)
    {
        if ( trim($valor) != ""){
           $query->where('type','LIKE',"%$valor%");   
        }
    }

	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista de denominaciones
    public function  denomination_list(){
        return $this->orderBy('denomination','value')->pluck('denomination', 'id');
    } 

}
