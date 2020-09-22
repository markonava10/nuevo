<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CopiesFax extends Model
{
    // Generales
    protected $table = 'copies_faxes';
    public $timestamps = false;
    protected $fillable =  ['international_fax',
                            'national_fax',
                            'copies',
                            ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+-----------------+
      | Polimórficas: 	|
      +-----------------+
    */

    // Asociado con lo de la transacción
 	public function transactions(){
        return $this->morphMany(Transaction::class, 'transactiontable');
    }

    /*+----------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

 	// ¿El cajero que creó la transacción es el que está conectado?
    public function scopeThisCashier($query,$cashier_id = Null){
    	// No se manda cajero, se asume el usuario conectado
    	if (!$cashier_id) {
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
        if (!$subsidiary_id && !Session::has('subsidiary') ){
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
}
