<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    /* Pagadores Asociados a los Proveedores */
	protected $table = 'provider_service';
    public $timestamps = false;
    
    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // provider_service --> Providers (Un registro pertenece a un Proveedor)
	public function provider(){
		return $this->belongsTo(Provider::class, 'provider_id');
	} 

   // provider_service --> Services (Un registro pertenece a un servicio)
	public function service(){
		return $this->belongsTo(Payer::class, 'payer_id');
	} 

	/*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Por Id del Proveedor (provider_id)
    public function scopeProviderId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('provider_id','=',$valor);      
        }
    }

    // Por id de Servicio (service_id)
    public function scopeServiceId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('service_id','=',$valor);      
        }
    }

    public function getProviderService($provider,$payer){
        return $this->where('provider_id','=',$provider)
                    ->where('service_id','=',$service);
    }
}
