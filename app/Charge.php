<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    // Generales
    protected $table = 'charges';
    protected $fillable =  [
        'company_id',
        'service_id',
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

  	// Charges -->Companies (Un cobro pertenece a una compañia)
  	public function company(){
  		return $this->belongsTo(Company::class);
  	}

    // Charges -->Services (Una cobro pertenece a un servicio)
    public function service(){
      return $this->belongsTo(Service::class);
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

    public function scopeLimits($query,$amount){
      return $query->where('lower_limit','>=',$amount)
                   ->where('upper_limit','<=',$amount);
    }

   /*-----------------------------------------+
     | Funciones adicionales de ayuda           |
     +------------------------------------------+
    */

    public function chargesbyamount($service_id,$company_id,$amount){
      $sql = "SELECT * FROM charges ";
      $sql.= " WHERE company_id=" . $company_id;
      $sql.= "   AND service_id=" . $service_id;
      $sql.= "  AND " . $amount . " BETWEEN lower_limit AND upper_limit";
      return  \DB::select($sql); 
    }
}
