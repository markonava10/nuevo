<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;

class Recharge extends Model
{
    // Generales
    protected $table = 'recharges';
    protected $fillable =  [
        'recharge_type_id',
        'phone',
        'card_',
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
    // Recharge -->Types_Recharges (Una transferencia es de cierto Tipo)
    public function type_recharge()
    {
       return $this->belongsTo(TypesRecharges::class, 'recharge_type_id','id');
    }

    /*+---------------------------------------------------------+
      | Polimórficas:                            				|
      +---------------------------------------------------------+
    */

    // El encabezado de la transacción
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactiontable');
    }

    /*--------------------------------------+
     | Funciones de apoyo					|
     +--------------------------------------+
	*/


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

 	// ¿El cajero que creó la transacción es el que está conectado?
    public function scopeThisCashier($query,$cashier_id = Null){
    	// No se manda cajero, se asume el usuario conectado
    	if (!$cashier_id){
    		$cashier_id = Auth::user()->id;
    	}
    	// Regresa registros con transacción que sean del cajero indicado
        $query->wherehas('transactions',function($query) use ($cashier_id) {
            $query->where('cashier_id','=',$cashier_id);
        });
    }

    // Public de un cajor
    public function scopeCashier_Id($query,$cashier_id)
    {
        if ( trim($valor) != "") {
	        $query->wherehas('transactions',function($query) use ($cashier_id) {
            $query->where('cashier_id','=',$cashier_id);
            });
        } 
    }

 	// Recargas de la subsidiaria en sesión
    public function scopeThisSubsidiary($query,$subsidiary_id=null){
    	// No se mandó subsidiaria y tampoco está en sesión regresa NULL
        if (!$subsidiary_id && !Session::has('subsidiary') ) {
           return Null;  
        }

        // No se mandó subsidiaria se toma la que está en la sesión
        if (!$subsidiary_id) {
           $subsidiary_id = Session::get('subsidiary')->id;  
        }

        // Regresa los registros que tengan relación con TRANSACCIONES y que sean de la subsidiaria indicada
        $query->wherehas('transactions',function($query) use ($subsidiary_id){
        $query->where('subsidiary_id','=',$subsidiary_id);
        });
    }

    // De cierto Tipo de recarga
    public function scopeRechargeTypeId($query,$valor)
    {
        if ( trim($valor) != "") {
           return $query->whererecharge_type_id($valor);
        } 
    }
    // De un teléfono 
    public function scopePhone($query,$valor)
    {
        if ( trim($valor) != "") {
             $query->where('phone','LIKE',"%$valor%");      
        }
    }

    // De una tarjeta 
    public function scopeCard($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('card','LIKE',"%$valor%");      
        }
    }

    
    public function scopeTransactionSubsidiaryId($query,$subsidiary_id)
    {
        $query->whereHas('transactions',function($query) use($subsidiary_id){
            $query->where('subsidiary_id',$subsidiary_id);
        });
    }

    // Cajero
    public function  scopeTransactionCashierId($query,$cashier_id)
    {
        $query->whereHas('transactions',function($query) use($cashier_id){
        $query->where('cashier_id',$cashier_id);
        });
    }

    // Servicio
    public function  scopeTransactionServiceId($query,$service_id)
    {
        $query->whereHas('transactions',function($query) use($service_id){
        $query->where('service_id',$service_id);
        });
    }

    // Proveedor
    public function  scopeTransactionProviderId($query,$provider_id)
    {
        $query->whereHas('transactions',function($query) use($provider_id){
            $query->where('provider_id',$provider_id);
        });
    }

    // Cliente
    public function  scopeTransactionCustomerId($query,$customer_id)
    {
        $query->whereHas('transactions',function($query) use($customer_id){
            $query->where('customer_id',$customer_id);
        });
    }
}
