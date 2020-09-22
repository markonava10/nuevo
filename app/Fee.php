<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
     // Generales
     protected $table = 'fees';
     protected $fillable =  [
         'company_id',
         'provider_id',
         'country_id',
         'lower_limit',
         'upper_limit',
         'fixed_fee',
         'percentage'
     ];
 
 
     /*+-----------------------------+
       | Relaciones entre tablas     |
       +-----------------------------+
     */
 
     /*+----------------------------------------------------------+
       | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
       +----------------------------------------------------------+
     */
       // fees -->Companies (Un cobro pertenece a una compañia)
 
       public function company()
       {
           return $this->belongsTo(Company::class);
       }
 
     // fees -->Providers (Una cobro pertenece a un servicio)
     public function provider()
     {
       return $this->belongsTo(Provider::class);
     }
 
     // fees -->Countries (Una cobro pertenece a un servicio)
     public function country()
     {
       return $this->belongsTo(Country::class);
     }
 
 
     /*+---------------------------------+
       | Búsquedas x diferentes Criterios |
       +----------------------------------+
     */
 
     // De una compañia
     public function scopeCompanyId($query,$valor)
     {
       return $query->whereCompany_id($valor);
     }
     
     // De un proveedor
     public function scopeProviderId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('provider_id','=',$valor);      
         }
     }
 
     // De un país
     public function scopeCountryId($query,$valor)
     {
         if ( trim($valor) != "") {
             $query->where('country_id','=',$valor);      
         }
     }
 
     // Importe dentro de los límites
     public function scopeLimits($query,$amount){
       return $query->where('lower_limit','>=',$amount)
                    ->where('upper_limit','<=',$amount);
     }
 
    /*-----------------------------------------+
      | Funciones adicionales de ayuda           |
      +------------------------------------------+
     */
 
     // Regresa el registro de la compañía, proveedor, país y que el importe esté entre los límites
     public function feesbyamount($company_id,$provider_id,$country_id,$amount)
     {
       $sql = "SELECT * FROM fees ";
       $sql.= " WHERE company_id=" . $company_id;
       $sql.= "   AND provider_id=" . $provider_id;
       $sql.= "   AND country_id=" . $country_id;
       $sql.= "   AND " . $amount . " BETWEEN lower_limit AND upper_limit";
       return  \DB::select($sql); 
     }
}