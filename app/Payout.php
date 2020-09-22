<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;

class Payout extends Model
{
    // Generales
    protected $table = 'payouts';
    public $timestamps = false;
    protected $fillable =  [
        'type_payout_id'
    ];

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

    // Payouts -->Type (El pago fue sobre un tipo de pago)
    public function TypePayout()
    {
      return $this->belongsTo(TypesPayout::class, 'types_payout_id', 'id');
    } 


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De un tipo de servicio 
    public function scopeTypePayout($query,$valor)
    {
        if(trim($valor) != ""){
            return $query->where('type_payout_id','=',$valor);      
        }
    }

    // Asociados con la transacción:
    // De una sucursal
    public function scopeSubsidiaryId($query,$subsidiary_id){
        $query->whereHas('transactions',function($query) use($subsidiary_id){
          $query->where('subsidiary_id',$subsidiary_id);
        });
    }

    // Cajero
    public function  scopeCashierId($query,$cashier_id){
        $query->whereHas('transactions',function($query) use($cashier_id){
          $query->where('cashier_id',$cashier_id);
        });
    }

    // De cajero conectado
    public function  scopeThisCashier($query){
        if (!Auth::check()) {
          return Null;
        }
        $query->whereHas('transactions',function($query){
          $query->where('cashier_id',Auth::user()->id);
        });
    }

    // De la subsidiaria activa en la sesión
    public function  scopeThisSubsidiary($query){
        if(Session::has('subsidiary') ){
           return Null;  
        }
        $query->wherehas('transactions',function($query){
          $query->where('subsidiary_id','=',Session::get('subsidiary')->id);
        });
    }
}
