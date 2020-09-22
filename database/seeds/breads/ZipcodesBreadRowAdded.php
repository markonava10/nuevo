<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;

class ZipcodesBreadRowAdded extends Seeder
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

            $dataType = DataType::where('name', 'zipcodes')->first();

            \DB::table('data_rows')->insert(array (
                0 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'zipcode',
                    'type' => 'text',
                    'display_name' => 'Zipcode',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 1,
                ),
                1 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'town',
                    'type' => 'text',
                    'display_name' => 'Town',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 2,
                ),
                2 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'type',
                    'type' => 'text',
                    'display_name' => 'Type',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 3,
                ),
                3 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'state',
                    'type' => 'text',
                    'display_name' => 'State',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 4,
                ),
                4 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'county',
                    'type' => 'text',
                    'display_name' => 'County',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 5,
                ),
                5 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'timezone',
                    'type' => 'text',
                    'display_name' => 'Timezone',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 6,
                ),
                6 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'areacode',
                    'type' => 'text',
                    'display_name' => 'Areacode',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 7,
                ),
                7 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'latitude',
                    'type' => 'text',
                    'display_name' => 'Latitude',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 8,
                ),
                8 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'longitude',
                    'type' => 'text',
                    'display_name' => 'Longitude',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 9,
                ),
                9 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'region',
                    'type' => 'text',
                    'display_name' => 'Region',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 10,
                ),
                10 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'country',
                    'type' => 'text',
                    'display_name' => 'Country',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 11,
                ),
                11 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'deleted_at',
                    'type' => 'timestamp',
                    'display_name' => 'Deleted At',
                    'required' => 0,
                    'browse' => 0,
                    'read' => 0,
                    'edit' => 0,
                    'add' => 0,
                    'delete' => 0,
                    'details' => '{}',
                    'order' => 12,
                ),
                12 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'created_at',
                    'type' => 'timestamp',
                    'display_name' => 'Created At',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 0,
                    'delete' => 0,
                    'details' => '{}',
                    'order' => 13,
                ),
                13 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'updated_at',
                    'type' => 'timestamp',
                    'display_name' => 'Updated At',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 0,
                    'delete' => 0,
                    'details' => '{}',
                    'order' => 14,
                ),
                14 => 
                array (
                    'data_type_id' => $dataType->id,
                    'field' => 'id',
                    'type' => 'text',
                    'display_name' => 'Id',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{}',
                    'order' => 15,
                ),
            ));
        } catch(Exception $e) {
            throw new Exception('exception occur ' . $e);

            \DB::rollBack();
        }

        \DB::commit();
    }
}

