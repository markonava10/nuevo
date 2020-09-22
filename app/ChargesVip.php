<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargesVip extends Model
{
    // Generales
    protected $table = 'charges_vips';
    protected $fillable =  [
        'company_id',
        'service_id',
        'vip_id',
        'lower_limit',
        'upper_limit',
        'fixed_fee',
        'percentage',
        'discount_percentage',
        'low_percentage'
    ];


    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

  	// Charges_vips -->Companies (Un cobro pertenece a una compañia)
  	public function company(){
  		return $this->belongsTo(Company::class);
  	}

    // Charges_vips -->Services (Una cobro pertenece a un servicio)
    public function service(){
      return $this->belongsTo(Service::class);
    }

    // Charges_Vips -->Vip (Un cliente puede perteecer a un Vip)
    public function vip(){
      return $this->belongsTo(Vips::class);
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una compañia
    public function scopeCompanyId($query,$valor){
      return $query->whereCompany_id($valor);
    }
    
    // De un servicio
    public function scopeServiceId($query,$valor){
        if (trim($valor) != "") {
            $query->where('service_id','=',$valor);      
        }
    }

    // De un tipo de Vip
    public function scopeVipId($query,$valor){
        if(trim($valor) != ""){
            $query->where('vip_id','=',$valor);      
        }
    }

    public function scopeLimits($query,$amount){
      return $query->where('lower_limit','>=',$amount)
                   ->where('upper_limit','<=',$amount);
    }

   /*-------------------------------------------+
     | Funciones adicionales de ayuda           |
     +------------------------------------------+
    */

    public function chargesbyamount($service_id,$company_id,$vip_id,$amount){
      $sql = "SELECT * FROM charges_vips ";
      $sql.= " WHERE company_id=" . $company_id;
      $sql.= "   AND service_id=" . $service_id;
      $sql.= "   AND vip_id=" . $vip_id;
      $sql.= "   AND " . $amount . " BETWEEN lower_limit AND upper_limit";
      return  \DB::select($sql); 
    }

}
