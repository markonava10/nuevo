<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // Generales
    protected $table = 'companies';
    protected $fillable =  [
        'company',
        'owner',
        'email',
        'address',
        'zipcode',
        'phone',
        'name_notify',
        'amount_bd_transfers',
        'amount_bd_exchanges',
        'amount_to_require_id',
        'days_to_require_id',
        'days_check_to_authorization',
        'amount_require_adicional_id',
        'adicional_ids',
        'amount_to_notify',
        'days_to_notify',
        'limit_by_day_transfers',
        'limit_by_day_exchanges',
        'times_subsidiares',
        'amount_subsidiaries',
        'days_subsidiaries',
        'amount_first_time_transfers',
        'amount_first_time_exchanges',
        'amount_requiere_income',
        'providers_transfers',
        'providers_exchanges',
        'days_providers_exchanges',
        'days_providers_transfers',
        'max_subidiaries',
        'logo',
        'active',
        'process_open',
        'process_close',
        'tx_subsidiary',
        'tx_safe',
        'tx_register',
        'tx_petty_cash',
        'rx_subsidiary',
        'rx_safe',
        'rx_register',
        'rx_petty_cash',
        'ch_request',
        'ch_accept',
        'ch_change',
        'ch_receive',
        'service_id_payments',
        'service_id_diference',
        'copies_rate',
        'fax_nat_first_page_rate',
        'fax_nat_extra_page_rate',
        'fax_int_first_page_rate',
        'fax_int_extra_page_rate',
        'amount_max_by_mo',
        'amount_to_req_serial_mo',
        'expire_at'
    ];


    /*+-----------------------------------------+
      | Setters y Getters de varios Campos      |
      +-----------------------------------------+  
    */
    // Setters
    public function setCompanyAttribute($value)
    {
        $this->attributes['company'] = ucfirst($value);
    }

    public function setOwnerAttribute($value)
    {
        $this->attributes['owner'] = ucfirst($value);
    }

    public function setaddressAttribute($value)
    {
        $this->attributes['address'] = ucfirst($value);
    }
    
    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */


    // Companies <--- Subsidiaries (Una compañia tiene varias sucursales)
    public function subsidiaries(){
        return $this->hasMany(Subsidiary::class,'compnay_id','id');
    }

    // Companies <--- Users (Una compañia tiene varios usuarios)
    public function users(){
        return $this->hasMany(User::class,'compnay_id','id');
    }


    // Companies <--- Customers (Una compañia tiene varios clientes)
    public function customers(){
        return $this->hasMany(Customer::class,'compnay_id','id');
    }

    // Companies <--- Receivers (Una compañia tiene varios receptores)
    public function receivers(){
        return $this->hasMany(Receiver::class,'compnay_id','id');
    }


    // Companies <--- Policies (Una compañia tiene varias políticas)
    public function policies(){
        return $this->hasMany(Police::class,'compnay_id','id');
    }


    // Companies <--- Charges (Una compañia tiene varios cargos -charges - para cobros)
    public function charges(){
        return $this->hasMany(Charge::class,'compnay_id','id');
    }

    // Companies <--- reasons (Una compañia tiene varios razones de cancelar transacciones)
    public function reasons(){
        return $this->hasMany(Reason::class,'compnay_id','id');
    }

    // Companies <--- marks (Una compañia tiene varios razones o causas para marcar clientes)
    public function marks(){
        return $this->hasMany(Mark::class,'compnay_id','id');
    }
    
    // Companies <--- key_movements (Una compañia tiene muchas claves de movimiento)
    public function key_movements(){
        return $this->hasMany(Key_movement::class,'compnay_id','id');
    }

    // Companies <--- processes (Una compañia tiene muchos procesos de caja)
    public function processes(){
        return $this->hasMany(Process::class,'compnay_id','id');
    }
    
    // Companies <--- Vips (Una compañia tiene varios tipos de Vips)
    public function vips(){
        return $this->hasMany(Vip::class,'compnay_id','id');
    }

    /*+-------------------------------------------------------------+
      | Relación UNO a Muchos con Zipcodes                          |
      | Una Compañía está o pertenece a una zona postal (ZipCode)   |
      +-------------------------------------------------------------+
    */

    // Companies -->Zipcodes (Una compañia pertenece a un zipcode)
    public function zipcode()
    {
      return $this->belongsTo(Zipcode::class,'zipcode','zipcode');
    }

    /*+-------------------------------------------------------------+
      | Relación Mucho a Muchos con Provider                        |
      | Una Compañía usa mucos proveedores                          |
      +-------------------------------------------------------------+
    */

    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }  

    // Una compañía presta muchos servicios
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    // Companies <--- types_payments (En la compañía se pueden pagar muchos tipos de pago)
    public function types_payments(){
        return $this->belongsToMany(TypesPayment::class);
    }

    // Companies <--- types_payouts (La empresa paga varias cosas a los clientes)
    public function types_payouts(){
        return $this->belongsToMany(TypesPayout::class);
    }


    /*+-------------------------------------------------------------+
      | Relación Muchos a través de  hasManyThrough                 |
      +-------------------------------------------------------------+
    */


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
      // Country
    public function scopeCompany($query,$valor)
    {
        if (trim($valor) != "") {
           $query->where('company','LIKE',"%$valor%");   
        }
    }

    // Activas
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }  

    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de Compañias
    public function  companies_list(){
        return $this->orderBy('id')->pluck('company', 'id');
    } 

    // Lista Compañias Activas
    public function companies_actives_list($query)
    {
        return $this->orderBy('id')->where('active','=',1)->pluck('company', 'id');
    }  
}