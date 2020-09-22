<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class TypesPayment extends Model
{
    // Generales
	protected $table = 'types_payments';
    public $timestamps = false;
	protected $fillable =  [
        'spanish',
        'short_spanish',
        'english',
        'short_english'
    ];
 	/*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */
    // Types_Payments --> Paymnts  (Un tipo de servicio tiene muchos pagos)
    public function payments(){
        return $this->hasMany(Payment::class,'type_payment_id','id');
    }

    /*+-------------------------------------------------------------------+
      | Relación Muchos a muchos                                          |
      +-------------------------------------------------------------------+
    */

    // Un Tipo de pago (Types_Payments) puede ser usado por muchas empresas 
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }  
    

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // De la compañía del usuario conectado

    public function scopeCompanyAuth($query)
    {
        $query->wherehas('companies',function($query) {
            $query->where('company_id','=',Auth::user()->company_id);
        });
    }
    // Español
    public function scopeSpanish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('spanish','LIKE',"%$valor%");   
        }
    }

    // Corto en Español
    public function scopeShortSpanish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('short_spanish','LIKE',"%$valor%");   
        }
    }

    // Inglés
    public function scopeEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('english','LIKE',"%$valor%");   
        }
    }

    // Corto en Español
    public function scopeShortEnglish($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('short_english','LIKE',"%$valor%");   
        }
    }

	/*+---------+
	  | Listas  |
	  +---------+
	*/
    // Lista por nombre completo
    public function types_payments_list(){
    	if (\App::isLocale('es')) {
            return $this->wherehas('companies',function($query){
                    $query->where('company_id','=',Auth::user()->company_id);
            })->orderBy('id')->pluck('spanish', 'id');	
    	} else {
            return $this->wherehas('companies',function($query){
                    $query->where('company_id','=',Auth::user()->company_id);
            })->orderBy('id')->pluck('english', 'id'); 
    	}
    } 
   
   // Lista por nombre corto
    public function  types_payments_short_list(){
    	if (App::isLocale('es')) {
	 		return $this->wherehas('companies',function($query){
                    $query->where('company_id','=',Auth::user()->company_id);
            })->orderBy('id')->pluck('short_spanish', 'id');	
    	} else {
            return $this->wherehas('companies',function($query){
                    $query->where('company_id','=',Auth::user()->company_id);
            })->orderBy('id')->pluck('short_english', 'id');
    	}
    }
}