<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idsbycountry extends Model
{
    protected $table = 'idsbycountry';

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+-------------------------------------------------------------+
      | Es hija de xxxxxxxxxxxxx 									|
      +-------------------------------------------------------------+
    */

    // Pais
    public function country()
    {
      return $this->belongsTo(Country::class);
    }

    // Tipo de identificación
    public function typeid()
    {
      return $this->belongsTo(TypeId::class,'typeid','id');
    }
}
