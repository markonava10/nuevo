<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable =  [
        'first_name',
        'middle_name',
        'last_name',
        'maternal_name',
        'birthday',
        'address',
        'phone',
        'zipcode',
        'company_id',
        'country_id',
        'typeid_id',
        'id_number',
        'expire_at',
        'typeid_second_id',
        'second_id_number',
        'second_expire_at',
        'occupation_id',
        'vip_id',
        'photo',
        'active'
    ];

    /*+-----------------------------+
      | Atributos Get y Set         |
      +-----------------------------+
    */
    // Name
    public function getNameAttribute()
    {
        return ucwords($this->first_name) . ' ' . ucwords($this->last_name);   
    }

    // Full Name
    public function getFullNameAttribute()
    {
        return ucwords($this->first_name)    . ' ' . 
               ucwords($this->middle_name)   . ' ' .
               ucwords($this->last_name)     . ' ' .
               ucwords($this->maternal_name);   
    }
    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Muchos - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */

    // Customers <--- Ctr (Un cliente tiene varios registros ultima transaccion)
    public function ctrs(){
        return $this->hasMany(Ctr::class,'customer_id','id');
    }

    // Customers <--- Transactions (Un cliente tiene varias transacciones)
    public function transactions(){
        return $this->hasMany(Transaction::class,'customer_id','id');
    }

    // Customers <--- Transactions (Un cliente tiene varias transacciones)
    public function transactions_last($limit=10){
        return $this->hasMany(Transaction::class,'customer_id','id')->orderBy('created_at','desc')->limit($limit);
    }

    // Customers <--- MoneyOrders (Un cliente tiene varios Money ORDERS)
    public function moneyorders(){
        return $this->hasMany(Moneyorder::class,'customer_id','id')->orderBy('created_at','desc');
    }

    // Customers <--- Autorhizations (Un cliente puede estar en muchas autorizaciones)
    public function authoriztions(){
        return $this->hasMany(Authorization::class,'customer_id','id');
    }


    // Customers <--- MarkHistory (Un cliente tiene varias marcas)
    public function mark_histories(){
        return $this->hasMany(MarkHistory::class,'customer_id','id');
    }


    // Customers <--- MarkHistory (Un cliente tiene varias marcas)
    public function mark_histories_last($limit=10){
        return $this->hasMany(MarkHistory::class,'customer_id','id')->orderBy('created_at','desc')->limit($limit);
    }

    // Customers <--- MarkHistory (Un cliente tiene varias marcas activas)
    public function mark_histories_active(){
        return $this->hasMany(MarkHistory::class,'customer_id','id')
                    ->where('active','=',1);
    }



    /*+----------------------------------------------------------+
      | Uno - Uno: Un Cliente tiene asociado otro registro       |
      +----------------------------------------------------------+
    */

    public function totals()
    {
        return $this->hasMany(Total::class);
    }


    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // Customer -->Zipcodes (Una cliente pertenece a un zipcode)
    public function zipcode()
      {
        return $this->belongsTo(Zipcode::class,'zipcode','zipcode');
      } 

    // Customers -->Country (Un cliente pertenece a un pais)
    public function country()
    {
      return $this->belongsTo(Country::class);
    }

    // Customers -->Companies (Un cliente pertenece a una compañia)
    public function company()
    {
      return $this->belongsTo(Company::class);
    }


    // Customers -->Vip (Un cliente puede perteecer a un Vip)
    public function vips()
    {
      return $this->belongsTo(Vips::class,'vip_id','id');
    }

    // Customers -->Users (Un cliente fue creado por un usuario)
    public function user()
    {
      return $this->belongsTo(User::class);
    }

    // Customers -->Occupation (Un cliente pertenece o tiene una ocupación)
    public function occupation()
    {
      return $this->belongsTo(Occupation::class,'occupation_id','id');
    }

    // Customers -->TypeId (Un cliente tiene una identificación de cierto tipo)
    public function typeId()
    {
      return $this->belongsTo(TypeId::class,'typeid_id','id');
    }


    // Customers -->TypeId (Un cliente tiene una identificación de cierto tipo)
    public function typeidsecondid()
    {
      return $this->belongsTo(TypeId::class,'typeid_second_id','id');
    }

    // ¿Tiene Marcas Abiertas?
    Public function has_active_marks()
    {
      return $this->mark_histories()
                  ->where('active','=','1')
                  ->count();
    }

    // ¿De las marcas activas cuáles son para bloqueo?
    public function has_marks_to_lock()
    {
        return $this->whereHas('mark_histories', function ($query){
            $query->where('active', '=', 1)
                  ->wherehas('mark',function($query){
                    $query->where('lock_customer','=',1);
                  });
        })->count();
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Nombre del cliente
    public function scopeName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('first_name','LIKE',"%$valor%")
                 ->orWhere('middle_name','LIKE',"%$valor%")
                 ->orWhere('last_name','LIKE',"%$valor%")
                 ->orWhere('maternal_name','LIKE',"%$valor%");
        }
    }

    // Nombre  completo del cliente
    public function scopeFullName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('first_name','LIKE',"%$valor%")
                 ->orWhere('middle_name','LIKE',"%$valor%")
                 ->orWhere('last_name','LIKE',"%$valor%")
                 ->orWhere('maternal_name','LIKE',"%$valor%");
        }
    }

    // Cuenta de Correo
    public function scopeEmail($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('email','LIKE',"%$valor%");   
        }
    }


    // Por el teléfono
    public function scopePhone($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('phone','LIKE',"%$valor%");   
        }
    }

    // De una compañia
    public function scopeCompanyId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('company_id','=',$valor);      
        }
    }


    // De un Vip
    public function scopeVipId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('vip_id','=',$valor);      
        }
    }


    // Por Zona Postal
    public function scopeZipcode($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('zipcode','=',$valor);      
        }
    }

    // De un pais
    public function scopeCountryId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('country_id','=',$valor);      
        }
    }

    // De la compañía del usuario conectado
    public function scopeCompanyAuthUser($query)
    {
        $query->where('company_id','=',Auth::user()->company_id);   
    }

    // Creado por un usuario
    public function scopeUserId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('user_id','=',$valor);      
        }
    }

    // Marcados
    public function scopeMarked($query)
    {
        $query->whereNotNull('marked')
              ->where('marked','>',0);      
    }

    // De una ocupación
    public function scopeOccupationd($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('occupation_id','=',$valor);      
        }
    }

 /*+----------------------+
      | Funciones de apoyo   |
      +----------------------+
    */

    // ¿Es Vip?

    public function is_vip(){
        if ($this->vip_id && $this->vip_id > 0) {
            return true;
        }
        return false; 
    }    

    public function isVip(){
        if ($this->vip_id && $this->vip_id > 0) {
            return true;
        }
        return false; 
    }
    // Lista de clientes de la empresa
    public function customers_company_auth_user($query)
    {
        $query->where('company_id','=',Auth::user()->company_id);
    }

    // Lista de clientes marcados de esta empresa
    public function marked_customers_this_company($query){
        dd(Auth::user()->company_id);
        $query->where('company_id','=',Auth::user()->company_id)
              ->where('marked','>','0');
    }

    public function marked_customers(){
        return $this->where('marked');
    }
    // ¿está marcado?
    Public function is_marked(){
        return $this->marked;
    }

    public function transactons_orderby($column){
        return $this->transactions->orderBy($column)->get();
    }

    // ¿Tiene identificación?
    public function has_first_id(){
        if (!is_null($this->typeid_id)  && !is_null($this->id_number) && !is_null($this->expire_at)) {
            return true;
        }
        return false;
    }

    // ¿Tiene segunda identificación?
    public function has_second_id(){
        if (!is_null($this->typeid_second_id)  && !is_null($this->second_id_number) && !is_null($this->second_expire_at)) {
            return true;
        }
        return false;
    }

    // ¿Tiene ocupación?
    public function has_occupation(){
        return $this->occupation_id;
    }

    // Actualiza cliente con el Request
    public function update_customer($request){
        if ($request->typeid_id && $request->id_number && $request->expire_at) {
            $request['expire_at'] = date_create_from_format('m/d/Y', $request->expire_at);
        }
        if ($request->typeid_second_id && $request->second_id_number && $request->second_expire_at) {
            $request['second_expire_at'] = date_create_from_format('m/d/Y', $request->second_expire_at);
        }

        $this->fill($request->all());
        $this->save();
        return $this;
    }


    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de sucursales (Subsidiarias)
    public function  customers_list(){
        return $this->orderBy('last_name')->pluck('last_name', 'id');
    } 

    // Lista de clientes que tienen marcas
    public function customers_with_marks_list(){
        return $this->wherehas('mark_histories')
                    ->pluck('fullname', 'id');
    }
}
