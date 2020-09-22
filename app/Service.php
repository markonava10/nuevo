<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Service extends Model
{
    // Generales
	protected $table = 'services';
	protected $fillable =  [
        'service',
        'short',
        'charges',
        'next_route',
        'route_transaction',
        'route_index',
        'require_customer',
        'require_provider',
        'require_receiver',
        'require_payer',
        'require_exchangerate',
        'require_issu',
        'require_bank',
        'avaiable_menu',
        'use_authorization',
        'image',
        'ctr_report',
        'limit_ctr'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Uno - Uno: Un Servicio  tiene asociado otro registro      |
      +----------------------------------------------------------+
    */

    public function service1()
    {
        return $this->hasOne(Total::class,'service_id_1');
    }


    public function service2()
    {
        return $this->hasOne(Total::class,'service_id_2');
    }

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */


    // Services <--- Transactions (Un Servicio tiene varias transacciones)
    public function transactions(){
        return $this->hasMany(Service::class,'service_id','id');
    }

    // Polimórfica muchos a muchos: 
    public function keysmovements(){
        return $this->morphToMany(Key_movement::class, 'keyabbles');
    }


    // Services <---Charges (Un servicio tiene varios registros en la tabla de cargos)
    public function charges(){
        return $this->hasMany(Charge::class,'service_id','id');
    }
    
    // Services <---Reasons (Un servicio puede tener muchas razones para cancelaciones)
    public function reasons(){
        return $this->hasMany(Reason::class,'service_id','id');
    }
    
    // Services <---Policies (Un servicio puede tener muchas políticas)
    public function policies(){
        return $this->hasMany(Policy::class,'service_id','id');
    }

    // Services <--- Lasts (Un Servicio tiene varios registros para CTR's)
    public function Ctrs(){
        return $this->hasMany(Service::class,'service_id','id');
    }

    // Services <--- transactions_by_day (Un Servicio tiene varios consultas de transaccionens)
    public function transactions_by_day(){
      return $this->hasMany(Transaction_By_Day::class,'user_id','id');
    }

    /*+-------------------------------------------------------------------+
      | Relación Mucho a Muchos con Role                                  |
      |  Un Proveedor (Provider) puede prestar muchos servicios (Serives) |
      +-------------------------------------------------------------------+
    */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }  


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Nombre del servicio
    public function scopeService($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('service','LIKE',"%$valor%");   
        }
    }

    // Nombre corto del servicio
    public function scopeShort($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('short','LIKE',"%$valor%");   
        }
    }

    // Servicios que requieren cliente
    public function scopeCustomer($query,$require_customer =1){
        $query->where('require_customer','=',1);
    }


    public function scopeServicesInCompany($query){
        return $this->where('avaiable_menu','=',1)
                    ->whereHas('providers', function ($query) {
                        $query->wherehas('companies',function($query) {
                                $query->where('company_id', '=', Auth::user()->company_id);   
                            });
                        })
                    ->orderby('service')
                    ->get();
    }


	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista de servicios (Nombre del servicio)
    public function  services_list(){
        return $this->orderBy('service')->pluck('service', 'id');
    } 

    // Lista de servicios x corto
    public function  services_short_list(){
        return $this->orderBy('short')->pluck('short', 'id');
    } 

    // lista de servicios autorizados en la compañía con proveedores asociados
    Public function services_in_company_list(){
        return $this->where('avaiable_menu','=',1)
        ->whereHas('providers', function ($query) {
            $query->wherehas('companies',function($query) {
                    $query->where('company_id', '=', Auth::user()->company_id);   
                });
            })
        ->orderby('service')->pluck('service', 'id');
    }

    Public function services_in_company_list_with_companies(){
        return $this->where('avaiable_menu','=',1)
        ->whereHas('providers', function ($query) {
            $query->wherehas('companies',function($query) {
                    $query->where('company_id', '=', Auth::user()->company_id);   
                });
            })
        ->wherehas('policies')
        ->orderby('service')->pluck('service', 'id');
    }

    // Lista de servicios que usan autorización
    public function services_user_authorization_list(){
        return $this->where('use_authorization','=',1)
        ->whereHas('providers', function ($query) {
            $query->wherehas('companies',function($query) {
                    $query->where('company_id', '=', Auth::user()->company_id);   
                });
            })
        ->orderby('service')->pluck('service', 'id');
    }

    public function services_services_in_company_list_ctr(){
        return $this->where('avaiable_menu','=',1)
        ->where('ctr_report','=',1)
        ->whereHas('providers', function ($query) {
            $query->wherehas('companies',function($query) {
                    $query->where('company_id', '=', Auth::user()->company_id);   
                });
            })
        ->orderby('service')->pluck('service', 'id');
    }
}