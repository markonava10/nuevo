<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    // Generales
    protected $table = 'sells';

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Polimórficas:                            |
      +----------------------------------------------------------+
    */

    
    // El pago está asociado con los movimientos
    public function movements()
    {
        return $this->morphMany(Movement::class, 'transactiontable');
    }


    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // Cajero
    public function  scopeCashierId($query,$cashier_id){
        $query->whereHas('movements',function($query) use($cashier_id){
          $query->where('cashier_id',$cashier_id);
        });
    }

    // De cajero conectado
    public function  scopeThisCashier($query){
        if (!Auth::check()) {
          return Null;
        }
        $query->whereHas('movements',function($query){
          $query->where('cashier_id',Auth::user()->id);
        });
    }
}
