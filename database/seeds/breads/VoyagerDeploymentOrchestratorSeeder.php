<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class VoyagerDeploymentOrchestratorSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = 'database/seeds/breads/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed(BanksBreadTypeAdded::class);
        $this->seed(BanksBreadRowAdded::class);
        $this->seed(CancellationsBreadTypeAdded::class);
        $this->seed(CancellationsBreadRowAdded::class);
        $this->seed(CategoriesBreadTypeAdded::class);
        $this->seed(CategoriesBreadRowAdded::class);
        $this->seed(ChargesBreadTypeAdded::class);
        $this->seed(ChargesBreadRowAdded::class);
        $this->seed(ChargesVipsBreadTypeAdded::class);
        $this->seed(ChargesVipsBreadRowAdded::class);
        $this->seed(CompaniesBreadTypeAdded::class);
        $this->seed(CompaniesBreadRowAdded::class);
        $this->seed(CopiesFaxesBreadTypeAdded::class);
        $this->seed(CopiesFaxesBreadRowAdded::class);
        $this->seed(CountriesBreadTypeAdded::class);
        $this->seed(CountriesBreadRowAdded::class);
        $this->seed(CtrsBreadTypeAdded::class);
        $this->seed(CtrsBreadRowAdded::class);
        $this->seed(CustomersBreadTypeAdded::class);
        $this->seed(CustomersBreadRowAdded::class);
        $this->seed(DenominationRegistersBreadTypeAdded::class);
        $this->seed(DenominationRegistersBreadRowAdded::class);
        $this->seed(DenominationsBreadTypeAdded::class);
        $this->seed(DenominationsBreadRowAdded::class);
        $this->seed(DifferencesBreadTypeAdded::class);
        $this->seed(DifferencesBreadRowAdded::class);
        $this->seed(ExchangeChargesBreadTypeAdded::class);
        $this->seed(ExchangeChargesBreadRowAdded::class);
        $this->seed(ExchangesBreadTypeAdded::class);
        $this->seed(ExchangesBreadRowAdded::class);
        $this->seed(ExhcangeRatesBreadTypeAdded::class);
        $this->seed(ExhcangeRatesBreadRowAdded::class);
        $this->seed(FeesBreadTypeAdded::class);
        $this->seed(FeesBreadRowAdded::class);
        $this->seed(IssuesBreadTypeAdded::class);
        $this->seed(IssuesBreadRowAdded::class);
        $this->seed(KeyMovementsBreadTypeAdded::class);
        $this->seed(KeyMovementsBreadRowAdded::class);
        $this->seed(KeyabblesBreadTypeAdded::class);
        $this->seed(KeyabblesBreadRowAdded::class);
        $this->seed(MarkingHistorysBreadDeleted::class);
        $this->seed(MarksBreadTypeAdded::class);
        $this->seed(MarksBreadRowAdded::class);
        $this->seed(MoneyordersBreadTypeAdded::class);
        $this->seed(MoneyordersBreadRowAdded::class);
        $this->seed(MovementsBreadTypeAdded::class);
        $this->seed(MovementsBreadRowAdded::class);
        $this->seed(OccupationsBreadTypeAdded::class);
        $this->seed(OccupationsBreadRowAdded::class);
        $this->seed(OpeningsBreadTypeAdded::class);
        $this->seed(OpeningsBreadRowAdded::class);
        $this->seed(PayerProvidersBreadTypeAdded::class);
        $this->seed(PayerProvidersBreadRowAdded::class);
        $this->seed(PayersBreadTypeAdded::class);
        $this->seed(PayersBreadRowAdded::class);
        $this->seed(PaymentsBreadTypeAdded::class);
        $this->seed(PaymentsBreadRowAdded::class);
        $this->seed(PayoutsBreadTypeAdded::class);
        $this->seed(PayoutsBreadRowAdded::class);
        $this->seed(PermissionCustomersBreadTypeAdded::class);
        $this->seed(PermissionCustomersBreadRowAdded::class);
        $this->seed(PermissionsBreadTypeAdded::class);
        $this->seed(PermissionsBreadRowAdded::class);
        $this->seed(PoliciesBreadTypeAdded::class);
        $this->seed(PoliciesBreadRowAdded::class);
        $this->seed(PoliciesExcededsBreadTypeAdded::class);
        $this->seed(PoliciesExcededsBreadRowAdded::class);
        $this->seed(ProcessesBreadTypeAdded::class);
        $this->seed(ProcessesBreadRowAdded::class);
        $this->seed(ProviderServicesBreadTypeAdded::class);
        $this->seed(ProviderServicesBreadRowAdded::class);
        $this->seed(ProvidersBreadTypeAdded::class);
        $this->seed(ProvidersBreadRowAdded::class);
        $this->seed(ReasonsBreadTypeAdded::class);
        $this->seed(ReasonsBreadRowAdded::class);
        $this->seed(ReceiversBreadTypeAdded::class);
        $this->seed(ReceiversBreadRowAdded::class);
        $this->seed(RechargesBreadTypeAdded::class);
        $this->seed(RechargesBreadRowAdded::class);
        $this->seed(RegistersBreadTypeAdded::class);
        $this->seed(RegistersBreadRowAdded::class);
        $this->seed(SellsBreadTypeAdded::class);
        $this->seed(SellsBreadRowAdded::class);
        $this->seed(ServicesBreadTypeAdded::class);
        $this->seed(ServicesBreadRowAdded::class);
        $this->seed(StatusesBreadTypeAdded::class);
        $this->seed(StatusesBreadRowAdded::class);
        $this->seed(SubsidiariesBreadTypeAdded::class);
        $this->seed(SubsidiariesBreadRowAdded::class);
        $this->seed(TotalsBreadTypeAdded::class);
        $this->seed(TotalsBreadRowAdded::class);
        $this->seed(TransactionsBreadTypeAdded::class);
        $this->seed(TransactionsBreadRowAdded::class);
        $this->seed(TransactionsByDaysBreadTypeAdded::class);
        $this->seed(TransactionsByDaysBreadRowAdded::class);
        $this->seed(TransferencesBreadTypeAdded::class);
        $this->seed(TransferencesBreadRowAdded::class);
        $this->seed(TransfersBreadTypeAdded::class);
        $this->seed(TransfersBreadRowAdded::class);
        $this->seed(TranslationsBreadTypeAdded::class);
        $this->seed(TranslationsBreadRowAdded::class);
        $this->seed(TypesPaymentsBreadTypeAdded::class);
        $this->seed(TypesPaymentsBreadRowAdded::class);
        $this->seed(TypesPayoutsBreadTypeAdded::class);
        $this->seed(TypesPayoutsBreadRowAdded::class);
        $this->seed(TypesRechargesBreadTypeAdded::class);
        $this->seed(TypesRechargesBreadRowAdded::class);
        $this->seed(TypesTransferencesBreadTypeAdded::class);
        $this->seed(TypesTransferencesBreadRowAdded::class);
        $this->seed(VipsBreadTypeAdded::class);
        $this->seed(VipsBreadRowAdded::class);
        
        $this->seed(ZipcodesBreadDeleted::class);
        $this->seed(ZipcodesBreadTypeAdded::class);
        $this->seed(ZipcodesBreadRowAdded::class);
        $this->seed(AuthorizationsBreadDeleted::class);
        $this->seed(AuthorizationsBreadTypeAdded::class);
        $this->seed(AuthorizationsBreadRowAdded::class);
    }
}
