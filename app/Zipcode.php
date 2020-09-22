<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
    public $timestamps = false; 
	protected $table = 'zipcodes';
	protected $fillable =  [
        'zipcode',
        'town',
        'type',
        'state',
        'county',
        'timezone',
        'areacode',
        'latitude',
        'longitude',
        'region',
        'country'
    ];

	   /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    //  Zipcode <---companies (Una zona postal tiene muchas compañias)
    public function companies(){
        return $this->hasMany(Company::class,'zipcode','zipcode');
    }

   //  Zipcode <---subsidiaries (Una zona postal tiene muchas sucursales)
    public function subsidiaries(){
        return $this->hasMany(Subsidiary::class,'zipcode','zipcode');
    }

   //  Zipcode <---customers (Una zona postal tiene muchas customers)
    public function customers(){
        return $this->hasMany(Customer::class,'zipcode','zipcode');
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // Town
    public function scopeZipcode($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('zipcode','LIKE',"%$valor%");   
        }
    }
    // Town
    public function scopeTown($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('town','LIKE',"%$valor%");   
        }
    }

    public function scopeState($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('state','LIKE',"%$valor%");   
        }
    }
  /*+---------+
    | Listas  |
    +---------+
  */
    // Lista de zonas postales por pueblo o ciudad
    public function  zipcodes_list(){
        return $this->orderBy('town')->pluck('town', 'zipcode');
    } 
}