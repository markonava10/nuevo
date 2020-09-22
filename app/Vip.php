<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class Vip extends Model
{
    // Generales
    protected $table = 'vips';
    protected $fillable =  [
        'company_id',
        'spanish',
        'english'
    ];


    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

  	// vip -->Companies (Un cobro pertenece a una compañia)
  	public function company()
  	{
  		return $this->belongsTo(Company::class);
  	}

    // Vips <--- customers (Un vip está o tiene muchos clientes)
    public function customers(){
        return $this->hasMany(Customer::class,'vip_id','id');
    }

    // Vips <--- charges_vips (Un vip está o tiene muchos cargos/cuotas para los vips)
    public function chargesvip(){
        return $this->hasMany(ChargeVip::class,'vip_id','id');
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
    

    // Procesos de compañía del usuario conectado
    public function scopeCompanyAuthUser($query)
    {
        $query->where('company_id','=',Auth::user()->company_id);   
    }

    // Nombre del vip en español
    public function scopeSpanish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('spanish','LIKE',"%$valor%");   
        }
    }

    // Nombre del vip en Inglés
    public function scopeEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('english','LIKE',"%$valor%");   
        }
    }


    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de VIPS de la compañía según el lenguaje establecido en la sesión
    public function vips_list(){
      
      if (App::isLocale('en')) {
        return $this->where('company_id','=',Auth::user()->company_id)
                    ->orderBy('id')->pluck('english', 'id');
      }

      return $this->where('company_id','=',Auth::user()->company_id)
                    ->orderBy('id')->pluck('spanish', 'id');
    }
}