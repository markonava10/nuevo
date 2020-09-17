<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;

class ApiDocsBreadSeeder extends Seeder
{
    public function run()
    {
        $dataType = $this->dataType('slug', 'api-docs');
        if (!$dataType) {
            $dataType = DataType::create([
                'slug' => 'api-docs',
                'name' => 'api_docs',
                'display_name_singular' => 'Api Doc',
                'display_name_plural' => 'Api Docs',
                'icon' => 'voyager-double-down',
                'model_name' => 'App\ApiDoc',
                'policy_name' => '',
                'controller' => '',
                'generate_permissions' => true,
                'description' => '',
            ]);
        }

        

        /*            New Row           */
        $dataRow = $this->dataRows(['data_type_id' => $dataType->id, 'field' => 'id']);

        $arrayRow = [
            'data_type_id' => $dataType->id,
            'field' => 'id',
            'type' => 'text',
            'display_name' => 'Id',
            'required' => true,
            'browse' => false,
            'read' => false,
            'edit' => false,
            'add' => false,
            'delete' => false,
            'order' => '1'
        ];

        if (!$dataRow) {
            $dataRow = DataRow::create($arrayRow);
        } else {
            $dataRow->fill($arrayRow)->save();
        }


        /*            New Row           */
        $dataRow = $this->dataRows(['data_type_id' => $dataType->id, 'field' => 'file_path']);

        $arrayRow = [
            'data_type_id' => $dataType->id,
            'field' => 'file_path',
            'type' => 'file',
            'display_name' => 'File Path',
            'required' => true,
            'browse' => true,
            'read' => true,
            'edit' => true,
            'add' => true,
            'delete' => true,
            'order' => '3'
        ];

        if (!$dataRow) {
            $dataRow = DataRow::create($arrayRow);
        } else {
            $dataRow->fill($arrayRow)->save();
        }


        /*            New Row           */
        $dataRow = $this->dataRows(['data_type_id' => $dataType->id, 'field' => 'deleted_at']);

        $arrayRow = [
            'data_type_id' => $dataType->id,
            'field' => 'deleted_at',
            'type' => 'timestamp',
            'display_name' => 'Deleted At',
            'required' => false,
            'browse' => false,
            'read' => false,
            'edit' => false,
            'add' => false,
            'delete' => false,
            'order' => '4'
        ];

        if (!$dataRow) {
            $dataRow = DataRow::create($arrayRow);
        } else {
            $dataRow->fill($arrayRow)->save();
        }


        /*            New Row           */
        $dataRow = $this->dataRows(['data_type_id' => $dataType->id, 'field' => 'created_at']);

        $arrayRow = [
            'data_type_id' => $dataType->id,
            'field' => 'created_at',
            'type' => 'timestamp',
            'display_name' => 'Created At',
            'required' => true,
            'browse' => true,
            'read' => false,
            'edit' => false,
            'add' => false,
            'delete' => true,
            'order' => '5'
        ];

        if (!$dataRow) {
            $dataRow = DataRow::create($arrayRow);
        } else {
            $dataRow->fill($arrayRow)->save();
        }


        /*            New Row           */
        $dataRow = $this->dataRows(['data_type_id' => $dataType->id, 'field' => 'updated_at']);

        $arrayRow = [
            'data_type_id' => $dataType->id,
            'field' => 'updated_at',
            'type' => 'timestamp',
            'display_name' => 'Updated At',
            'required' => true,
            'browse' => false,
            'read' => false,
            'edit' => false,
            'add' => false,
            'delete' => false,
            'order' => '6'
        ];

        if (!$dataRow) {
            $dataRow = DataRow::create($arrayRow);
        } else {
            $dataRow->fill($arrayRow)->save();
        }


        $item = $this->getMenuItem(['menu_id' => 1, 'title' => 'Api Docs']);
        if (!$item) {
            $itemArray = [
                'menu_id' => '1',
                'title' => 'Api Docs',
                'url' => '',
                'route' => 'voyager.api-docs.index',
                'target' => '_self',
                'icon_class' => 'voyager-double-down',
                'color' => '',
                'parent_id' => null,
                'order' => '18'
            ];

            MenuItem::create($itemArray);
        }

        Permission::generateFor('api_docs');
    }

    protected function dataType($field, $for)
    {
        return DataType::where([$field => $for])->first();
    }

    protected function dataRows($data)
    {
        return DataRow::where($data)->first();
    }

    protected function getMenuItem($data)
    {
        return MenuItem::where($data)->first();
    }
}