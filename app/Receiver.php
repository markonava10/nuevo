<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    // Generales
    protected $table = 'receivers';
    protected $fillable =  [
        'first_name',
        'middle_name',
        'last_name',
        'maternal_name',
        'company_id',
        'country_id',
        'active',
        'black_list'
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
        return ucwords($this->last_name) . ' ' . 
               ucwords($this->maternal_name) .  ' ' . 
               ucwords($this->first_name) .  ' ' . 
               ucwords($this->middle_name);   
    }

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

  	/*+----------------------------------------------------------+
      | Uno - Muchos: Tiene a muchos en otra Tabla (Es Pader de) |
      +----------------------------------------------------------+
    */

    // Receivers <-- Transfers: Un receptor tiene varios envíos de dinero (transfers)
    public function transfers(){
      return $this->hasMany(Transfer::class,'receiver_id','id');
    }

    // Receivers <--- Autorhizations (Un Receptor puede estar en muchas autorizaciones)
    public function authoriztions(){
        return $this->hasMany(Authorization::class,'receiver_id','id');
    }

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

	// Receiver -->Country (Un receptor pertenece o está en un pais)
	public function country()
	{
  		return $this->belongsTo(Country::class);
	}

	// Receivers -->Companies (Un receptor pertenece a una compañia)
	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	// Receivers -->Users (Un receptor fue creado por un usuario)
	public function user()
	{
		return $this->belongsTo(User::class);
	}

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // Id
    public function scopeId($query,$valor)
    {
        $query->where('id','=',$valor);      
    }
    // Primer Nombre del Receiver
    public function scopeFirstName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('first_name','LIKE',"%$valor%");   
        }
    }

    // Segundo Nombre del Receiver
    public function scopeMiddleName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('middle_name','LIKE',"%$valor%");   
        }
    }

    // Apellido paterno del Receiver
    public function scopeLastName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('last_name','LIKE',"%$valor%");   
        }
    }

    // Apellido Materno del Receiver
    public function scopeMaternalName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('maternal_name','LIKE',"%$valor%");   
        }
    }

    // Nombre  completo del receptor
    public function scopeFullName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('first_name','LIKE',"%$valor%")
                 ->orWhere('middle_name','LIKE',"%$valor%")
                 ->orWhere('last_name','LIKE',"%$valor%")
                 ->orWhere('maternal_name','LIKE',"%$valor%");
        }
    }

    // Nombre  del receptor
    public function scopeName($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('name','LIKE',"%$valor%");   
        }
    }


    // De una compañia
    public function scopeCompanyId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('company_id','=',$valor);      
        }
    }


    // De un pais
    public function scopeCountryId($query,$valor)
    {
       if ( trim($valor) != "") {
            $query->where('country_id','=',$valor);      
        }
    }



    // Creado por un usuario
    public function scopeUserId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('user_id','=',$valor);      
        }
    }


    /*+---------+
      | Listas  |
      +---------+
    */
    // Lista de Receptores
    public function  receivers_list(){
        return $this->orderBy($this->Name)->pluck($this->Name, 'id');
    } 

    /*+---------------------------------------+
      | Funciones de apoyo                    |
      +---------------------------------------+
    */
    public function transfersids($id){
        $record =$this->findOrFail($id);
        $transfersids = array();
        foreach ($record->transfers as $transfer) {
            array_push($transfersids,$transfer->id);
        }
        return $transfersids;
    }
}