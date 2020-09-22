<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payer extends Model
{
    // Generales
	protected $table = 'payers';
	protected $fillable =  [
        'payer',
        'short',
        'logotipo',
        'exchange_rate',
        'country_id'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */
    // Payers <-- Transfers: Un PAGADOR paga varios envíos de dinero (transfers)
    public function transfers(){
      return $this->hasMany(Transfer::class,'payer_id','id');
    }

    // Payers -->Country (Un PAGADOR pertenece a un pais)
    public function country()
    {
      return $this->belongsTo(Country::class);
    }

    // A países a paises a través de PAYERS

    public function fees(){
        return $this->hasManyThrough('App\Fee', 'App\Country');
    }


    /*+----------------------------------------------------------+
      | Relación Mucho a Muchos                                  |
      +----------------------------------------------------------+
    */

    // Un Pagador (Payer) puede ser usado por muchos Proveedores (Provider)
    public function providers()
    {
        return $this->belongsToMany(Provider::class)->withPivot('exchange_rate');
        
    }

    /*+----------------------------------------------------------+
      | Relación a través de otro modelo                         |
      +----------------------------------------------------------+
    */

    public function providersInCompany()
    {
        return $this->hasManyThrough(CompanyProvider::class, PayerProvider::class,'payer_id','provider_id','id','id');
    }



    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Nombre del pagador
    public function scopePayer($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('payer','LIKE',"%$valor%");   
        }
    }

    // Nombre corto del pagador
    public function scopeShort($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('short','LIKE',"%$valor%");   
        }
    }

    // Por Id de un país
    public function scopeCountryId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('country_id','=',$valor);      
        }
    }

    // Asociado a un  a un proveedor (PROVIDER_ID)
    public function scopeProviderId($query,$valor){
        return $this->providers()->where('provider_id','=',$valor);
    }


    // Solo providers asociados a una compañía


	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista de Pagadores por nombre
    public function payers_list(){
        return $this->orderBy('payer')->pluck('payer', 'id');
    } 

    // Lista de Empresas que expiden cheques x nombre corto
    public function payers_short_list(){
        return $this->orderBy('short')->pluck('short', 'id');
    } 

    // Lista de pagadores que tienen pagadores asociados
    public function PayersByProvider_list(){
        return $this->wherehas('providers')->pluck('payer', 'id');
    }

    // Lista de pagadores que Tengan proveedore asocidos y estos asociados a la compañía y a servicios
    public function payers_by_provider_list($country_id)
    {
      return $this->wherehas('providers',function($query) use($country_id){  
            $query->where('exchange_rate','>',0)
                ->where('country_id',$country_id);
            })->pluck('payer','id');
    }
}