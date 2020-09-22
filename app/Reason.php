<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Reason extends Model
{
    // Generales
    protected $table = 'reasons';
    protected $fillable =  [
        'reason',
        'company_id',
        'service_id',
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Muchos - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */

    // Un Usuario puede haber creado muchos cancelaciones (Cancellations)
    public function cancellations(){
      return $this->hasMany(Cancellation::class,'user_id','id');
    }

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
      
    // Company -->Reason (Una razon pertenece a una compañia)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    // Service -->Reason (Una razon pertenece o es para un Servicio)
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Nombre de la razon (Motivo)
    public function scopeReason($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('reason','LIKE',"%$valor%");   
        }
    }

    // De una compañia determinada
    public function scopeCompanyId($query,$valor)
    {
      $query->where('company_id','=',$valor);  
    }

    // De un servicio
    public function scopeServiceId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('service_id','=',$valor);   
        }   
    }

    // De una compañia del usuario conectado
    public function scopeCompanyAuth($query)
    {
      $query->where('company_id','=',Auth::user()->company_id);  
    }
 
    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de razones (Motivos) de la compañía del usuario conectado
	public function reasons_of_auth_user_company_list()
    {
        return $this->where('company_id','=',Auth::user()->company_id)
                    ->orderBy('reason')
        	        ->pluck('reason', 'id');
    } 

    public function reasons_list_by_service($service_id)
    {
        return $this->CompanyId(Auth::user()->company_id)
                    ->ServiceId($service_id)
                    ->orderBy('reason')
                    ->pluck('reason', 'id');
    }
}