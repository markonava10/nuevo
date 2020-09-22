<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call(BanksBreadRowAdded::class);
        $this->call(BanksBreadTypeAdded::class);
        $this->call(CancellationsBreadRowAdded::class);
        $this->call(CancellationsBreadTypeAdded::class);
        $this->call(CategoriesBreadRowAdded::class);
        $this->call(CategoriesBreadTypeAdded::class);
        $this->call(ChargesBreadRowAdded::class);
        $this->call(ChargesBreadTypeAdded::class);
        $this->call(ChargesVipsBreadRowAdded::class);
        $this->call(ChargesVipsBreadTYpeAdded::class);
        $this->call(CompaniesBreadRowAdded::class);
        $this->call(CompaniesBreadTypeAdded::class);
        $this->call(CopiesFaxesBreadRowAdded::class);
        $this->call(CopiesFaxesBreadTypeAdded::class);
        $this->call(CountriesBreadRowAdded::class);
        $this->call(CountriesBreadTypeAdded::class);
        $this->call(CtrsBreadRowAdded::class);
        $this->call(CtrsBreadTypeAdded::class);
        $this->call(CustomersBreadRowAdded::class);
        $this->call(CustomersBreadTypeAdded::class);
        $this->call(DenominationRegistersBreadRowAdded::class);
        $this->call(DenominationRegistersBreadTypeAdded::class);
        $this->call(DenominationsBreadRowAdded::class);
        $this->call(DenominationsBreadTypeAdded::class);
        $this->call(ApiDocsBreadSeeder::class);
        $this->call(DifferencesBreadRowAdded::class);
        $this->call(DifferencesBreadTypeAdded::class);
        $this->call(ExchangeChargesBreadRowAdded::class);
        $this->call(ExchangeChargesBreadTypeAdded::class);
        $this->call(ExchangesBreadRowAdded::class);
        $this->call(ExchangesBreadTypeAdded::class);
        $this->call(ExhcangeRatesBreadRowAdded::class);
        $this->call(ExhcangeRatesBreadTypeAdded::class);
        $this->call(ExhcangeRatesBreadRowAdded::class);
        $this->call(ExhcangeRatesBreadTypeAdded::class);
        $this->call(FeesBreadRowAdded::class);
        $this->call(FeesBreadTypeAdded::class);
        $this->call(IssuesBreadRowAdded::class);
        $this->call(IssuesBreadTypeAdded::class);
        $this->call(KeyabblesBreadRowAdded::class);
        $this->call(KeyabblesBreadTypeAdded::class);
        $this->call(KeyMovementsBreadRowAdded::class);
        $this->call(KeyMovementsBreadTypeAdded::class);
        $this->call(MarksBreadRowAdded::class);
        $this->call(MarksBreadTypeAdded::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->command->info("Seeder created successfully! :)");

    }
}
