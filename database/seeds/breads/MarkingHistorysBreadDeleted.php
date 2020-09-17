<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\MenuItem;

class MarkingHistorysBreadDeleted extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
       try {
        \DB::beginTransaction();

        $dataType = DataType::where('name', 'marking_historys')->first();

            if (is_bread_translatable($dataType)) {
                $dataType->deleteAttributeTranslations($dataType->getTranslatableAttributes());
            }

            if ($dataType) {
                DataType::where('name', 'marking_historys')->delete();
            }

        Voyager::model('Permission')->removeFrom('marking_historys');

        $menuItem = MenuItem::where('route', 'voyager.marking-historys.index');

        if ($menuItem->exists()) {
            $menuItem->delete();
        }

        // Since, voyager cache the menu, after seed deletion we clear admin menu cache as well.
        Artisan::call('cache:forget', ['key' => 'voyager_menu_admin']);
       } catch(Exception $e) {
         throw new Exception('Exception occur ' . $e);

         \DB::rollBack();
       }

       \DB::commit();
    }
}
