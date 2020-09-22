<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayerProvider extends Model
{
    /* Pagadores Asociados a los Proveedores */
	// Tabla
	protected $table = 'payer_provider';
    public $timestamps = false;
    
    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // PAYERPROVIDER --> Providers (Un registro pertenece a un PROVIDER)
	public function provider(){
		return $this->belongsTo(Provider::class, 'provider_id');
	} 

    // PAYERPROVIDER --> Payers (Un registro pertenece a un PAYER)
	public function payer(){
		return $this->belongsTo(Payer::class, 'payer_id');
	} 

    // A países a paises a través de PAYERS

    public function countries(){
        return $this->hasManyThrough('App\Country', 'App\Payer');
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

    // Por id de Pagador (payer_id)
    public function scopePayerId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('payer_id','=',$valor);      
        }
    }

    // Por país del pagador
    public function scopeCountryPayer($query,$valor)
    {
       if ( trim($valor) != "") {
    		$query->whereHas('payer', function ($query) use ($valor){
    			$query->where('country_id', '=', $valor);
			});
          }
    }

    public function getPayerProvider($provider,$payer){
        return $this->where('provider_id','=',$provider)
                    ->where('payer_id','=',$payer);
    }
}
