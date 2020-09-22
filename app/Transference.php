<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\eMoneyTorController;
use Session;
use Auth;

class Transference extends Model
{
    // Generales
    protected $table = 'transferences';
    protected $fillable =  [
        'register_id',
        'cashier_id',
        'amount',
        'folio',
        'transference_type_id',
        'subsidiary_id_destination',
        'register_id_destination',
        'cashier_id_destination',
        'received_date',
        'open',
        'status_id'
    ];

    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */
  	// Transference -->Register (Una Transferencia se creó en una Caja Registradora)

  	public function register()
  	{
  		return $this->belongsTo(Register::class,'register_id','id');
  	}

    // Transference -->Register (La transferencia se hizo sobre una caja de destino)
    public function register_destination()
    {
      return $this->belongsTo(Register::class,'register_id_destination','id');
    } 
  	// Transference -->User (Una transferencia la hizo un cajero)
    public function cashier()
    {
       return $this->belongsTo(User::class, 'cashier_id','id');
    }

    // Transference -->User (Una transferencia es para un cajero destino)
    public function cashier_destination()
    {
       return $this->belongsTo(User::class, 'cashier_id_destination','id');
    }

    // Transference -->Types_Transferences (Una transferencia es de cierto Tipo)
    public function type_transference()
    {
       return $this->belongsTo(TypesTransferences::class, 'transference_type_id','id');
    }

    // Transference -->Subsidiaries (La Transacción se hizo en una sucursal)
    public function subsidiary_destination()
    {
      return $this->belongsTo(Subsidiary::class,'subsidiary_id_destination','id');
    } 


    // Transference -->Status (La Transacción stiene un estado)
    public function status()
    {
      return $this->belongsTo(Status::class,'status_id','id');
    } 

    // Polimórfica hacia Movimiento
    public function movements(){
        return $this->morphMany(Movement::class, 'transactiontable');
    }

    // Funciones que apoyan la operación

    // Transferencias abiertas hacia la sucursal abierta en la sesión
    public function openTranferencesThisSubsidiary(){
        // Si no tiene seleccionada sucursal regresa Null
        if(!Session::has('subsidiary')){
           return Null;  
        }
      	return $this->where('open','=','1')
                    ->where('subsidiary_id_destination','=',Session::get('subsidiary')->id);
    }

    // Transferencias abiertas por el usuario autenticado
    public function openTransferencesAuthenticatedUser(){
      	return $this->where('open','=','1')
                  	->where('cashier_id','=',Auth::user()->id);
    }

    // Transferencias Abiertas
    public function openedTransferences()
    {
      return $this->where('open','=','1');
    }

    // Transferencias Cerradas
    public function closedTransferences()
    {
      return $this->where('open','=','0');  
    }

    // Transferencias creadas por el usuario autenticado
    public function TransferencesAuthenticatedUser(){
      	return $this->where('cashier_id','=',Auth::user()->id);
    }

    // Transferencias hacia la sucursal abierta en la sesión
    public function TranferencesThisSubsidiary(){
        // Si no tiene seleccionada sucursal regresa Null
        if(!Session::has('subsidiary')){
           return Null;  
        }
      	return $this->where('subsidiary_id_destination','=',Session::get('subsidiary')->id);
    }

    // Transferencias del tipo: Sucursal-Sucursal
    public function Issubsidiary_subsidiary(){
      return $this->where('transference_type_id','1');
    }
    // Caja Fuerte
    public function Issafe_regiser(){
      return $this->where('transference_type_id','2');
    }

    // Cajas Registradoras
    public function Isregister_regiser(){
      return $this->where('transference_type_id','3');
    }


    /*+----------------------------------+
      | Funciones de Apoyo                |
      +-----------------------------------+
    */

    // ¿Está abierta?
    public function isOpen(){
      return $this->open;
    }

    // ¿Es de cierto tipo?
    public function isType_Transference($trnasference_type_id=1){
       return $this->transference_type_id == $trnasference_type_id;
    }

    // ¿Es para la sucursal de la sesión?
    public function isToSubsidiary_id_destination($subsidiary_id_destination=Null){
      if(!isset($subsidiary_id_destination) && Session::has('subsidiary')){
        $subsidiary_id_destination = Session::get('subsidiary')->id;
      }

      return $this->subsidiary_id_destination == $subsidiary_id_destination;
    }

    // ¿Fue enviada para la caja que indico?
    public function isToRegister_id_destination($register_id_destination=Null){
      if(!$register_id_destination){
        $register_id_destination = Session::get('active_register')->id; 
      }


      if(!$this->register_id_destination){
        return false;
      }

      if(!$register_id_destination){
        return false;
      }

      return $this->register_id_destination == $register_id_destination;

    }

    // ¿El cajero que creó el registro es el que está conectado?
    public function isCasher_id($cashier_id = Null){
      if(!isset($cashier_id)){
        $cashier_id = Auth::user()->id;
      }
      return $this->cashier_id == Auth::user()->id;
    }

    // ¿Necesito para poder cerrar cerrar una transferencia?
    // 1.  Que sea para la sucursal que tengo en la sésión
    // 2.- Si el tipo NO ES SUCURSAL-SUCURSAL la caja "Destino debe ser la abierta"
    // 3.- Si es para misma sucursal: Cajero que la creó no debe ser quien la recibe
    // 4.- Que esté abierta
    // 4.- 
    // Revisar porque no está regresando los registros de OPENINGS del usuario autenticado
/*
            &&  (   ($this->transference_type_id != 1 &&  $this->isToRegister_id_destination() && !$this->isCasher_id() )
             || ($this->transference_type_id == 1 && !$this->isToRegister_id_destination() && !$this->isCasher_id() ) 
             || ($this->transference_type_id == 4 && !$this->isToRegister_id_destination() && !$this->isCasher_id() )
             || ($this->transference_type_id == 5 && $this->isToRegister_id_destination()  &&  $this->isCasher_id() )
           )

      */

    /*+-----------------------------------------------------+
      | ¿Puede recibirse?, bajo las siguientes condiciones  |
      |   (a) Que sea para la sucursal ue está activa       |
      |   (b) Si tiene "caja destino" debe ser la activa    |
      |  En ambos casos debe estar abierta                  |
      +-----------------------------------------------------+   
    */
    public function canbereceived(){
      
      if($this->isOpen()){
        if($this->register_id_destination ){
          return $this->isToRegister_id_destination(Session::get('active_register')->id);
        }
        
        if($this->isToSubsidiary_id_destination()){
          return true;
        }
      }
      return false;
    }


    
    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // los que puedan ser cerrados
    public function scopeCanBeReceived($query){
       return $query->ToThisSubsidiary()->where($this->isOpen());
    }
    // De cierto Tipo
    public function scopeType_TransferenceId($query,$valor)
    {
        if(trim($valor) != ""){
           return $query->wheretransference_type_id($valor);
        } 
    }

    // Generadas en esta sucursal
    public function scopeFromThisSubsidiary($query)
    {
      // Si no tiene seleccionada sucursal regresa Null
        $query->where('subsidiary_id','=',Session::get('subsidiary')->id);   
    }

    // Enviadas a la sucursal conectada
    public function scopeToThisSubsidiary($query)
    {
    	// Si no tiene seleccionada sucursal regresa Null
        if(!Session::has('subsidiary')){
           return Null;  
        }
      	$query->where('subsidiary_id_destination','=',Session::get('subsidiary')->id);  
    }

    // Enviadas a cierta Sucursal
  	public function scopeToSubsidiaryId($query,$valor)
    {
        if(trim($valor) != ""){
          return $query->whereSubsidiary_id_destination($valor);
        } 
    }

    // Abiertas para cierta sucursal
  	public function scopeOpenToSubsidiaryId($query,$valor=Null)
    {
      if(!isset($valor)){
        if(!Session::has('subsidiary')){
           return Null;  
        }
        $valor = Session::get('subsidiary')->id;
      }

      return $query->where('subsidiary_id_destination',$valor);
    }

    // De una Caja Registradora 
    public function scopeRegisterId($query,$valor)
    {
      return $query->whereRegister_id($valor);
    }
    
    //  Creadas por Cajero
    public function scopeCreatedByCashierId($query,$valor)
    {
      return $query->whereCashier_id($valor);
    }

    //  Creadas por el cajero conectado
    public function scopeCreatedByThisCashier($query)
    {
      $query->where('cashier_id','=',Auth::user()->id);    
    }

    // Creados por otros cajeros
    public function scopeCreatedbyOtherCashier($query)
    {
      $query->where('cashier_id',"!=",Auth::user()->id);
    }


    // Para una caja diferente a la que está abierta
    public function scopeToOtherRegister_id($query){
      $query->where('register_id_destination',"!=", Auth::user()->register_open()->first()->register_id);
    }

    // Sin caja destino
    public function scopeWithOutRegister_id($query){
      $query->whereNull('register_id_destination');
    }

    // Para esta caja
    public function scopeToThisRegister_id($query,$register){
      $query->where('register_id_destination',"=", $register->id);
    }

    // Para esta caja
    public function scopeToThisCachier_id($query){
      $query->where('cashier_id_destination',"=", Auth::user()->register_open()->first()->register_id);  
    }
    // Recbidas por el cajero indicado
    public function scopeReceivedByCashierId($query,$valor)
    {
    	return $query->whereCashier_id_destination($valor);
    }

    // // Recbidas por el cajero indicado autenticado
    public function scopeReceivedByThisCashier($query)
    {
    	$query->where('cashier_id_destination','=',Auth::user()->id);	
    }


    // Transferencias abiertas
    public function scopeOpened($query)
    {
		  return $query->where('open','=','1');
    }

    // Transferencias cerradas
    public function scopeClosed($query)
    {
		  return $query->where('open','=','0');
    }
}
