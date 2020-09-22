<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPayout extends Model
{
    /* Proveedores Asociados a la Compañías */
	// Tabla
	protected $table = 'company_types_payout';
	public $timestamps = false;

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // Company_Payout --> Company (Pertenece a una compañía)
	public function company(){
		return $this->belongsTo(Company::class, 'company_id');
	} 	

    // Company_Payout -->Types_Paymont (Pertenece a un tipo de servicio a pagar al cliente)
    public function TypePayout()
    {
      return $this->belongsTo(TypesPayout::class, 'type_payout_id', 'id');
    } 


	/*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una compañia
	public function scopeCompanyId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('company_id','=',$valor);      
        }
    }

    // De un proveedor
    public function scopeTypePayoutId($query,$valor){

        if ( trim($valor) != "") {
            $query->where('types_payout_id','=',$valor);      
        }
    }
}
