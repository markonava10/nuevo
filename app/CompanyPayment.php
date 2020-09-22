<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    /* Proveedores Asociados a la Compañías */
	// Tabla
	protected $table = 'company_payment';
	public $timestamps = false;

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // Company_Payment --> Company (Pertenece a una compañía)
	public function company(){
		return $this->belongsTo(Company::class, 'company_id');
	} 	

    // Company_Payment -->Types_Paymennt (Pertenece a un tipo de servicio a pagar)
    public function TypePayment()
    {
      return $this->belongsTo(TypesPayment::class, 'type_payment_id', 'id');
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
    public function scopeTypePaymentId($query,$valor){

        if ( trim($valor) != "") {
            $query->where('type_payment_id','=',$valor);      
        }
    }
}
