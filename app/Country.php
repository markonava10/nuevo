<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
	public $timestamps = false; 
	protected $table = 'countries';
	protected $fillable =  [
        'country',
        'code',
        'url',
        'isdefault',
        'include'
    ];

	/*+-----------------------------+
	  | Relaciones entre tablas     |
	  +-----------------------------+
	*/

    //  Country <---customers (De un pais -country - hay muchos clientes -customers)
    public function customers(){
        return $this->hasMany(Customer::class,'country_id','id');
    }

    //  Country <---receivers (Un país tiene muchos receptores)
    public function receivers(){
        return $this->hasMany(Receiver::class,'country_id','id');
    }

    //  Country <---payers (De un pais -country - hay muchos pagadores -payers)
    public function payers(){
        return $this->hasMany(Payer::class,'country_id','id');
    }

    //  Country <---Fees (De un pais -country - hay muchas cuotas -fees)
    public function fees(){
        return $this->hasMany(Fee::class,'country_id','id');
    }

    // Country <---> Typesids (Muchos a muchos)
    public function typesids(){
    	return $this->belongsToMany(TypeId::class, 'country_typeid', 'country_id', 'typeid_id');
    }
    
	/*+---------------------------------+
	  | Búsquedas x diferentes Criterios |
	  +----------------------------------+
	*/
      // Country
    public function scopeCountry($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('country','LIKE',"%$valor%");   
        }
    }

    public function scopeCode($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('code','LIKE',"%$valor%");   
        }
    }

    // Pais por defecto
    public function scopeDefault($query)
    {
        return $query->where('default', 1);
    }  

	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista de Países
    public function  countries_list(){
        return $this->orderBy('id')->pluck('country', 'id');
    } 

    // Lista Paises a incluir
    public function countries_include_list()
    {
        return $this->orderBy('country')->where('include','=',1)->pluck('country', 'id');
       // return $this->orderBy('country')->pluck('country', 'id');
    }  

    public function countries_payers_list(){
        return $this->orderBy('country')
            ->where('include','=',1)
            ->wherehas('payers')
            ->pluck('country', 'id');
    }
}
