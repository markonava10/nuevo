<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
class Register extends Model
{
    // Generales
	protected $table = 'registers';
	protected $fillable =  [
        'subsidiary_id',
        'register',
        'type',
        'amount_open',
        'open_close_extra',
        'amount_close',
        'balance_min_mon',
        'balance_min_tue',
        'balance_min_wed',
        'balance_min_thu',
        'balance_min_fri',
        'balance_min_sat',
        'balance_min_sun',
        'balance_max_mon',
        'balance_max_tue',
        'balance_max_wed',
        'balance_max_thu',
        'balance_max_fri',
        'balance_max_sat',
        'balance_max_sun',
        'priority'
    ];


    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Mucho - Uno: Tiene a muchos en otra Tabla (Es Pader de)  |
      +----------------------------------------------------------+
    */
      // Register --> Subsidiaries (La caja pertenece a una Sucursal)
    public function subsidiary()
    {
        return $this->belongsTo(Subsidiary::class);
    } 

    // Registers <--- Openings (Una caja puede tener muchas aperturas de caja);
    public function openings(){
      return $this->hasMany(Opening::class,'register_id','id');  
    }

    // Registers <--- Movements (Una caja puede tener muchos movimeintos de caja);
    public function movements(){
      return $this->hasMany(Movement::class,'register_id','id');  
    }

    // Registers <--- Transactions (Una caja puede tener muchaas transacciones);
    public function transactions(){
      return $this->hasMany(Transaction::class,'register_id','id');  
    }


    // Registers <--- transferences (Una caja puede tener muchas transferencias);
    public function transferences(){
      return $this->hasMany(Transference::class,'register_id','id');  
    }

    // Registers <--- transferences (Una caja puede tener muchas transferencias);
    public function transferences_destination(){
      return $this->hasMany(Transference::class,'register_id','id');  
    }


    // Una Caja  puede haber muchas consultas de transacciones x dia
    public function transactions_by_day(){
      return $this->hasMany(Transaction_By_Day::class,'user_id','id');
    }

    /*+----------------------------------------------------------+
      | Relación Mucho a Muchos                                  |
      +----------------------------------------------------------+
    */

    // Una caja (Register) puede cerrar con muchas denominaciones (Denomination)
    public function denominations()
    {
        return $this->belongsToMany(Denomination::class)->withPivot('quantity');
    }


    /*+---------+
      | Apoyo   |
      +---------+
    */
    public function openClose($action=true){
        if ($action) {
            $this->open = 1;
        } else {
            $this->open = 0;
        }
        session(['balance'  => $this->balance]);
        $this->save();
    }

    public function isType($type='N')
    {
        return $this->type === $type;
    }

    // Actualiza el saldo
    public function update_balance($action='input',$amount=0)
    {
         switch ($action)     // Switch según la acción (Entrada o Salida)
         {
            case 'input':   // Cierre de la Caja
                $this->balance = $this->balance + $amount;
                break;
            case 'output';
                $this->balance = $this->balance - $amount;
                break;
         } // Termina switch
         session(['balance'  => $this->balance]);  
         $this->save();
    }

    // Incrementa el folio de la caja
    public function add_folio()
    {
         $this->folio = $this->folio + 1;
         $this->save();
    }

    // Pone saldos en sesión: Mínimo-Máximo (según el día) - El actual de la caja  y como caja activa 
    public function put_session_balance_by_day()
    {
        switch (date('w')){
            case 0: 
                session(['balance_min'  => $this->balance_min_sun]); 
                session(['balance_max'  => $this->balance_max_sun]); 
                break;
            case 1: 
                session(['balance_min'  => $this->balance_min_mon]);
                session(['balance_max'  => $this->balance_max_mon]); 
                break;
            case 2:
                session(['balance_min'  => $this->balance_min_tue]); 
                session(['balance_max'  => $this->balance_max_tue]);
                break;
            case 3: 
                session(['balance_min'  => $this->balance_min_wed]); 
                session(['balance_max'  => $this->balance_max_wed]);
                break;
            case 4: 
                session(['balance_min'  => $this->balance_min_thu]); 
                session(['balance_max'  => $this->balance_max_thu]);
                break;
            case 5: 
                session(['balance_min'  => $this->balance_min_fri]); 
                session(['balance_max'  => $this->balance_max_fri]);
                break;
            case 6: 
                session(['balance_min'  => $this->balance_min_sat]); 
                session(['balance_max'  => $this->balance_max_sat]);
                break;
        }
        session(['balance'          => $this->balance]);  
        session(['active_register'  => $this]); 
    }

    // Lee la primera apertura abierta para esta caja
    public function get_opening_open()
    {
        return $this->openings()->where('open','=',1)->first();
    }

    // Lee saldos mínimos por día
    public function get_balance_min_by_day($type='min')
    {
        if ($type == 'min') {
            switch (date('w')){
                case 0: 
                    return $this->balance_min_sun; 
                    break;
                case 1: 
                   return $this->balance_min_mon;
                    break;
                case 2:
                   return $this->balance_min_tue; 
                    break;
                case 3: 
                   return $this->balance_min_wed; 
                    break;
                case 4: 
                   return $this->balance_min_thu; 
                    break;
                case 5: 
                   return $this->balance_min_fri; 
                    break;
                case 6: 
                   return $this->balance_min_sat; 
                    break;
            }            
        } else {
            switch (date('w')){
                case 0: 
                    return $this->balance_max_sun; 
                    break;
                case 1: 
                   return $this->balance_max_mon;
                    break;
                case 2:
                   return $this->balance_max_tue; 
                    break;
                case 3: 
                   return $this->balance_max_wed; 
                    break;
                case 4: 
                   return $this->balance_max_thu; 
                    break;
                case 5: 
                   return $this->balance_max_fri; 
                    break;
                case 6: 
                   return $this->balance_max_sat; 
                    break;
            }              
        }
    }

    // Pone null todos los lugares donde esté  la caja en la sesión
    public function forget_session_register(){
        Session::forget('normal_register');
        Session::forget('safe_register');
        Session::forget('active_register');
        Session::forget('active_opening');
        Session::forget('register');
    }

    // Pone el saldo directamente
    public function put_balance($balance){
         $this->balance = $balance;
         $this->save();
    }

    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */
    // De una sucursal
    public function scopeSubsidiaryId($query,$valor)
    {
        if ( trim($valor) != "") {
            $query->where('subsidiary_id','=',$valor);      
        }
    }

    // Nombre de la caja
    public function scopeRegister($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('register','LIKE',"%$valor%");   
        }
    }

    // Tipo de caja
    public function scopeType($query,$valor)
    {
        if ( trim($valor) != "") {
           $query->where('type','LIKE',"%$valor%");   
        }
    }

    public function scopeOpen($query,$open=1)
    {
        $query->where('open','=',$open);
    }

	/*+---------+
	  | Listas  |
	  +---------+
	*/

    // Todas las cajas registradoras
    public function  registers_list(){
        return $this->orderBy('register')
                    ->pluck('register', 'id');
    } 

    // Lista de Cajas de una Subsidiaria por defect
    public function  register_subsidiary_list($subsidiary_id=Null){
        if ($subsidiary_id == Null) {
            $subsidiary_id = session()->get('subsidiary')->id;
        }
        return $this->where('subsidiary_id',$subsidiary_id)
        			->orderBy('register')
        			->pluck('register', 'id');
    } 

    // Lista de cajas de una subsidiaria por defecto cerradas
    public function  register_subsidiary_status_list($subsidiary_id,$open = false,$type='A'){
        if ($type == 'A'){
            return $this->where('subsidiary_id',$subsidiary_id)
                        ->where('open',$open)
                        ->orderBy('register')
                        ->pluck('register', 'id');            
        }

        return $this->where('subsidiary_id',$subsidiary_id)
                    ->where('open',$open)
                    ->where('type',$type)
        			->orderBy('register')
        			->pluck('register', 'id');
    }
}