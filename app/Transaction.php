<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use App\Receiver;

class Transaction extends Model
{
    // Generales
    protected $table = 'transactions';
    protected $fillable =  [
        'subsidiary_id',
        'customer_id',
        'service_id',
        'provider_id',
        'cashier_id',
        'amount',
        'fixed_fee',
        'comission_percentage',
        'comission_amount',
        'total_charges',
        'status_id',
    ];
    /*+-----------------------------+
      | Relaciones entre tablas     |
      +-----------------------------+
    */

    /*+-----------------------------+
      | Polimórficas:               |
      +-----------------------------+
    */

    public function transactiontable()
    {
        return $this->morphTo();
    }

    // Polimórfica hacia Movimiento
    public function movements(){
        return $this->morphMany(Movement::class, 'transactiontable');
    }
    
    /*+----------------------------------------------------------+
      | Uno - Muchos: Pertenece a una Tabla (Es hija de)         |
      +----------------------------------------------------------+
    */

    // Transaction -->Subsidiaries (La Transacción se hizo en una sucursal)
    public function subsidiary()
      {
        return $this->belongsTo(Subsidiary::class);
      } 

    // Transaction -->Customer (Una transacción pertenece a un cliente)
    public function customer()
    {
      return $this->belongsTo(Customer::class);
    }

    // Transaction -->Servide (Una transacción es de un Servicio)
    public function service()
    {
      return $this->belongsTo(Service::class);
    }

   // Transaction -->User (Cashier) (La transacción la hizo un cajero)
    public function cashier()
    {
       return $this->belongsTo(User::class, 'cashier_id','id');
    }

   // Transaction -->Provider (La transacción se hizo con un proveedor)
    public function provider()
    {
       return $this->belongsTo(Provider::class, 'provider_id','id');
    }

   // Transaction -->Register (La transacción se hizo en una Caja)
    public function register()
    {
       return $this->belongsTo(Register::class, 'register_id','id');
    }

   // Transaction -->Status (Estado) (La transacciónt tiene un estado)
    public function status()
    {
       return $this->belongsTo(Status::class);
    }

    /*+----------------------------------------------------------+
      | Uno - Uno: Tiene un registro asociado en otra tabla      |
      +----------------------------------------------------------+
    */

    // Transaction o---o Cancellation (La transacción tiene a una Cancelación)
    public function cancellation() {
        return $this->hasOne('App\Cancellation');
    }


    /*+---------------------------------+
      | Búsquedas x diferentes Criterios |
      +----------------------------------+
    */

    // De una compañia
    public function scopeSubsidiaryId($query,$valor)
    {
        if(trim($valor) != ""){
            $query->where('subsidiary_id','=',$valor);      
        }
    }

    // De un cliente
    public function scopeCustomerId($query,$valor){
        if(trim($valor) != ""){    
            $query->where('customer_id','=',$valor); 
        }    
    }

    // De un servicio
    public function scopeServiceId($query,$valor){
     if(trim($valor) != ""){
            $query->where('service_id','=',$valor);      
        }
    }

    // De un proveedor
    public function scopeProviderId($query,$valor){
        if(trim($valor) != ""){    
            $query->where('provider_id','=',$valor); 
        }       
    }
    // Por cajero
    public function scopeCashierId($query,$valor){
        if(trim($valor) != ""){    
            $query->where('cashier_id','=',$valor); 
        }       
    }

    // Por cajero es igual al usuario conectado
    public function scopeCashierAuthenticatedId($query){
            $query->where('cashier_id','=',Auth::user()->id);      
    }

    // Por Status
    public function scopeStatusId($query,$valor){
            $query->where('status_id','=',$valor);      
    }

    // De un rango de fechas 
    public function scopeFrom_to_Dates($query,$from_date,$to_date){
      $query->whereBetween('created_at',[$from_date,$to_date]); 
    }

    // De los últimos NN días
    public function scopeFrom_Last_Days($query,$days=1)
    {
      $from_date = Carbon::today()->subDays($days);
      $to_date   = Carbon::today()->endOfDay();
      return $query->whereBetween('created_at',[$from_date,$to_date]);
    }
    // Creados el día de hoy
    public function scopeToday($query){
      $from_date = Carbon::today();
      $to_date   = Carbon::today()->endOfDay();
      return $query->whereBetween('created_at',[$from_date,$to_date]);

    }

    // Por un modelo asociado
    public function scopeTransactionTable_Type($query,$model){
        return $query->where('transactiontable_type',$model);
    }

    public function canceled($query){
        return $query->where_not_null('cancellation_id');
    }

    /*+---------------------------------------------------------------+
      | ¿Está cancelado? (Si tiene valor canellation_id lo está)      |
      +---------------------------------------------------------------+
    */
     public function is_canceled(){
        return $this->cancellation;
       // return $this->cancellation_id;
     } 

    /*+---------------------------------------------------------------+
      | Funciones para validar las reglas establecidas en la empresa  |
      +---------------------------------------------------------------+
    */

    /* total_amount_customer_service
     +--------------------------------------------------------------------------------------------+
     | Total (sum amount) CLIENTE-SERVICIO en ciertos días (desde hoy hacia atras)                |
     +----------------------+---------------------------------------------------------------------+
     |  customer_id         | Cliente_id                                                          |
     |  service_id          | Id del servicio                                                     |
     |  days                | Dias que hay que revisar (desde hoy hacia atrás)                    |
     +----------------------+---------------------------------------------------------------------+
     | Reegresa:            | Suma del importe (sum amount)                                       |
     +--------------------------------------------------------------------------------------------+
    */ 
    public function total_amount_customer_service($customer_id,$service_id,$days){
      return $this->customerid($customer_id)
                  ->serviceid($service_id)
                  ->From_to_Dates(Carbon::now()->subDays($days)->startOfDay(),Carbon::today()->endOfDay())
                  ->sum('amount');
    }

    /*+---------------------------------------------------------+
      | Suma el importe de transacciones de                     |
      | Una ENTIDAD: Subsidiaria, Cajero, Cliente, proveedor    |
      | de  SERVICIO: Envíos, Cambio Cheques, Money Orders....  |
      | en  RANGO de fechas                                     |
      | Regresa: Importe total o Falso                          |
      | Parámetros                                              |
      | $type       = subsidiary,customer,cashier               |
      | $entity_id  = Id de la entidad segun el $type           |
      | $service_id = Id del servicio a sumar                   |
      | $from       = Fecha desde donde hay que sumar           |
      | $to         = Fecha hasta donde hay que sumar           |  
      | $days       = Cantidad de días hacia atrás              |
      +---------------------------------------------------------+
      | Si se pasan las fechas $from y $to no pasar $days       |
      | Si se pasa $days se calcularán $from y $to              |       
      +---------------------------------------------------------+ 
    */
    public function total_amount_entity_service($type='customer',$entity_id=Null,$service_id=Null,$from=Null,$to=Null,$days=Null){
        if(!isset($entity_id) || !isset($service_id) || !isset($type)){
            return false;
        }

        $type = strtolower($type);
        if(isset($days)){   
            $from = Carbon::now()->subDays($days)->startOfDay();
            $to   = Carbon::today()->endOfDay();   
        }else{
            if(is_null($from)){
                $from = Carbon::today()->startOfDay();  
            }

            if(is_null($to)){
                 $to  = Carbon::today()->endOfDay();    
            }
        }

        switch ($type) {
            case 'subsidiary':
                return $this->subsidiaryid($entity_id)
                  ->serviceid($service_id)
                  ->From_to_Dates($from,$to)
                  ->sum('amount');
                break;
      			case 'customer':
      				return $this->customerid($entity_id)
      			      ->serviceid($service_id)
      			      ->From_to_Dates($from,$to)
      			      ->sum('amount');
      				break;
      			case 'provider':
      				return $this->providerid($entity_id)
      			      ->serviceid($service_id)
      			      ->From_to_Dates($from,$to)
      			      ->sum('amount');
      			    break;

      			case 'cashier':
      				return $this->cashierid($entity_id)
      			      ->serviceid($service_id)
      			      ->From_to_Dates($from,$to)
      			      ->sum('amount');
      			    break;
        }
        return false;
    }

    // Total de registros
    public function total_records_entity_service($type='customer',$entity_id=Null,$service_id=Null,$from=Null,$to=Null,$days=Null){
        if(!isset($entity_id) || !isset($service_id) || !isset($type)){
            return false;
        }

        $type = strtolower($type);
        if(isset($days)){   
            $from = Carbon::now()->subDays($days)->startOfDay();
            $to   = Carbon::today()->endOfDay();   
        }else{
            if(is_null($from)){
                $from = Carbon::today()->startOfDay();  
            }

            if(is_null($to)){
                $to  = Carbon::today()->endOfDay();    
            }
        }


        switch ($type) {
            case 'subsidiary':
                return $this->subsidiaryid($entity_id)
                  ->serviceid($service_id)
                  ->From_to_Dates($from,$to)
                  ->count();
                break;
            case 'customer':
                return $this->customerid($entity_id)
                  ->serviceid($service_id)
                  ->From_to_Dates($from,$to)
                  ->count();
                break;
            case 'provider':
                return $this->providerid($entity_id)
                  ->serviceid($service_id)
                  ->From_to_Dates($from,$to)
                  ->count();
                break;

            case 'cashier':
                return $this->cashierid($entity_id)
                  ->serviceid($service_id)
                  ->From_to_Dates($from,$to)
                  ->count();
                break;
        }
        return false;
    }
    /* total_records_customer_service
     +--------------------------------------------------------------------------------------------+
     | Cuenta los registros de "value_to_count" de CLIENTE-SERVICIO en un rango de fechas         |
     +----------------------+---------------------------------------------------------------------+
     |  customer_id         | Cliente_id                                                          |
     |  service_id          | Id del servicio                                                     |
     |  days                | Dias que hay que revisar (desde hoy hacia atrás)                    |
     |  value_to_count      | Valor a contar (subsidiary_id,provider_id,receiver,payer,etc.)      |
     +----------------------+---------------------------------------------------------------------+
     | Reegresa:            | Total de registros                                                  |
     +--------------------------------------------------------------------------------------------+
    */ 
    public function total_records_customer_service($customer_id,$service_id,$days,$value_to_count){
        /* Se crea la subconsulta para poder contar */
        $subselect ='count(*) as total_records,' . $value_to_count;
        return $this->select(\DB::raw($subselect))
                    ->customerid($customer_id)
                    ->serviceid($service_id)
                    ->From_to_Dates(Carbon::now()->subDays($days)->startOfDay(),Carbon::today())
                    ->groupBy($value_to_count)
                    ->get()
                    ->count();
    }


    /* validate_limit_amount_customer 
     +--------------------------------------------------------------------------------------------+
     | Revisa si no se ha excedido el importe límite permitido para cliente-servicio              |
     +----------------------+---------------------------------------------------------------------+
     |  customer_id         | Cliente_id                                                          |
     |  service_id          | Id del servicio                                                     |
     |  days                | Dias que hay que revisar (desde hoy hacia atrás)                    |
     |  limit_allowed       | Límite definido en la política                                      |
     |  transaction         | Importe de la transacción                                           |
     |  include_transaction | ¿Incluir lo de esta transacción o no?                               |
     +----------------------+---------------------------------------------------------------------+
     | Reegresa:            | FALSO/VERDADERO ¿Está excedido o no?                                |
     +--------------------------------------------------------------------------------------------+
    */
    public function exced_limit_amount_customer($customer_id,$service_id,$days,$limit_allowed,$transction=0,$include_transaction = true){

      $total_amount_customer = $this->total_amount_customer_service($customer_id,$service_id,$days);
        if($include_transaction){
            return $total_amount_customer + $transction >= $limit_allowed;    
        }
        return $$total_amount_customer  >= $limit_allowed;     
    }


    /* validate_limit_records_customer
     +--------------------------------------------------------------------------------------------+
     | Revisa si no se ha excedido el importe límite permitido para cliente-servicio              |
     +----------------------+---------------------------------------------------------------------+
     |  customer_id         | Cliente_id                                                          |
     |  service_id          | Id del servicio                                                     |
     |  days                | Dias que hay que revisar (desde hoy hacia atrás)                    |
     |  value_to_count      | Valor a contar (subsidiary_id,provider_id,receiver,payer,etc.)      |
     |  limit_allowed       | Límite definido en la política                                      |
     |  include_transaction | ¿Incluir lo de esta transacción o no?                               |
     +----------------------+---------------------------------------------------------------------+
     | Reegresa:            | FALSO/VERDADERO ¿Está excedido o no?                                |
     +--------------------------------------------------------------------------------------------+
    */    
    public function exced_limit_records_customer($customer_id,$service_id,$days,$value_to_count,$limit_allowed,$include_transaction=true){
        
        $total_records = $this->total_records_customer_service($customer_id,$service_id,$days,$value_to_count);

        if($include_transaction){
            return $total_records + 1 >= $limit_allowed;    
        }
        return $total_records  >= $limit_allowed;  
    }

    /* transactions_receiver
     +------------------------------------------------------------------+
     | Regresa colección de transacciones de un RECEIVER                |
     +------------------------------------------------------------------+
    */ 
    public function transactions_receiver($service_id,$days,array $transfersids){
        return $this->serviceid($service_id)
                    ->From_to_Dates(Carbon::now()->subDays($days)->startOfDay(),Carbon::today())
                   ->whereIn('transactiontable_id', $transfersids);
    }

    /* total_amount_receiver_service
     +------------------------------------------------------------------+
     | Suma el importe de las transacciones de los últimos días         |
     +------------------------------------------------------------------+
    */ 
    public function total_amount_receiver_service($service_id,$days,array $transfersids){
        $transactions = $this->transactions_receiver($service_id,$days,$transfersids);
        return $transactions->sum('amount');
    }

    // Total de registros
    public function total_records_receiver_service($service_id,$days,array $transfersids){
        $transactions = $this->transactions_receiver($service_id,$days,$transfersids);
        return $transactions->count();
    }

    // Total de una fecha
    public function concentrated_between_dates($cashier_id,$date_from,$date_to){
      $year       = substr($date_from,6,4);
      $month      = substr($date_from,0,2);
      $day        = substr($date_from,3,2);
      $formated_date_from = $year . "-" . $month . "-" . $day;
      $year       = substr($date_to,6,4);
      $month      = substr($date_to,0,2);
      $day        = substr($date_to,3,2);
      $formated_date_to = $year . "-" . $month . "-" . $day;

      $sql= " SELECT s.id,s.service,count(*) as total,sum(t.amount) as importe,sum(t.total_charges + t.comission_amount) as comisiones";
      $sql.= " FROM transactions t,services s WHERE s.id = t.service_id";
      $sql.= " AND t.cashier_id = " . $cashier_id;
      //$sql.= " AND t.created_at BETWEEN CAST('" . $formated_date_from . " 00:00:01' AS DATETIME) AND CAST('" . $formated_date_to. " 23:59:59' AS DATETIME)";
      $sql.= " AND t.created_at BETWEEN '" . $date_from . "'  AND '" . $date_to . "'";
      $sql.= " GROUP BY s.id,s.service";
      return  \DB::select($sql); 
     
    }

    // Relación de Entidades en un período
    public function entities_by_service_from_days($entity_id='customer_id',$service_id=1,$days=0){
      $entity_id = strtolower($entity_id);
      $sql= " SELECT DISTINCT " . $entity_id . " as entity ";
      $sql.= "FROM transactions ";
      $sql.= "WHERE  service_id=" . $service_id;
      $sql.= "  AND created_at BETWEEN '" . Carbon::now()->subDays($days)->startOfDay() . "'";
      $sql.= "  AND '" . Carbon::today()->endOfDay() . "'";
      return \DB::select($sql); 
    }
}
