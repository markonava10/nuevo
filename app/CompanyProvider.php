<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyProvider extends Model
{
    /* Proveedores Asociados a la Compañías */
	// Tabla
	protected $table = 'company_provider';

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // COMPANYPROVIDER --> Providers (Un registro pertenece a un PROVIDER)
	public function provider(){
		return $this->belongsTo(Provider::class, 'provider_id');
	} 

    // PAYERPROVIDER --> Payers (Un registro pertenece a un PAYER)
	public function company(){
		return $this->belongsTo(Company::class, 'company_id');
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
    public function scopeProviderId($query,$valor){

        if ( trim($valor) != "") {
            $query->where('provider_id','=',$valor);      
        }
    }
}
