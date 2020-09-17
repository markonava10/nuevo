<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use Illuminate\Support\Str;
use Illuminate\Support\Composer;

class GenerateBread extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:bread {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate BREAD from database';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * The name table.
     *
     * @var string
     */
    protected $slug;

    /**
     * The name file.
     *
     * @var string
     */
    protected $fileName;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->slug = $this->argument('name');
        $this->fileName = Str::studly("{$this->slug}BreadSeeder");
        $path = $this->getPath();
        $this->info("Processing seed for bread");

        if ($this->files->exists($path)) {
            $this->error("The seed for {$this->slug} already exist");
            unlink($path);
            $this->info("Removing seed");
        }

        $this->info("Generating {$this->fileName}.");
        $this->files->put($path, $this->buildClass($this->slug));
        $this->composer->dumpAutoloads();
        $this->info('Bread successfully generated.');

    }

    protected function getPath()
    {
        return base_path('database/seeds') . '/' . str_replace('\\', '/', "{$this->fileName}.php");
    }

    protected function replaceClass($stub)
    {
        return str_replace('$CLASS_NAME$', $this->fileName, $stub);
    }

    protected function buildClass()
    {
        $stub = $this->buildBread($this->files->get($this->getStub()));
        return $this->replaceClass($stub);
    }

    protected function buildBread($stub)
    {
        /* Generate DataType for slug */
        $dataType = $this->dataType(['slug' => $this->slug]);
        if (!$dataType) {
            $this->error("The BREAD for {$this->slug} not exist");
            exit();
        }
        $generatePermissions = $dataType->generate_permissions ? 'true' : 'false';
        $dataTypeText = "$&dataType = $&this->dataType('slug', '{$this->slug}');
        $&dataTypeArray = [
            'slug' => '{$dataType->slug}',
            'name' => '{$dataType->name}',
            'display_name_singular' => '{$dataType->display_name_singular}',
            'display_name_plural' => '{$dataType->display_name_plural}',
            'icon' => '{$dataType->icon}',
            'model_name' => '{$dataType->model_name}',
            'policy_name' => '{$dataType->policy_name}',
            'controller' => '{$dataType->controller}',
            'generate_permissions' => $generatePermissions,
            'description' => '{$dataType->description}',
        ];
        if (!$&dataType) {
            $&dataType = DataType::create($&dataTypeArray);
        } else {
            $&dataType->fill($&dataTypeArray)->save();
        }";

        $stub = str_replace('$&', '$', str_replace('$DATA_TYPE$', $dataTypeText, $stub));

        $dataRowText = '';
        $dataRows = $this->dataRows($dataType->id);
        foreach ($dataRows as $dataRow) {
            $details = str_replace(["'0'", "'1'"], ["true", "false"], $this->processDetail($dataRow->details));
            $required = $dataRow->required ? "true" : "false";
            $browse = $dataRow->browse ? "true" : "false";
            $read = $dataRow->read ? "true" : "false";
            $edit = $dataRow->edit ? "true" : "false";
            $add = $dataRow->add ? "true" : "false";
            $delete = $dataRow->delete ? "true" : "false";
            $dataRowText .= "\n
        /*            New Row           */
        $&dataRow = $&this->dataRows(['data_type_id' => $&dataType->id, 'field' => '{$dataRow->field}']);

        $&arrayRow = [
            'data_type_id' => $&dataType->id,
            'field' => '{$dataRow->field}',
            'type' => '{$dataRow->type}',
            'display_name' => '{$dataRow->display_name}',
            'required' => $required,
            'browse' => $browse,
            'read' => $read,
            'edit' => $edit,
            'add' => $add,
            'delete' => $delete,
            'order' => '{$dataRow->order}'
        ];

        if (!$&dataRow) {
            $&dataRow = DataRow::create($&arrayRow);";
            $dataRowText .= "\n        } else {
            $&dataRow->fill($&arrayRow)->save();
        }\n";

            if($details != 'null') {
                $dataRowText .= "\n        $&dataRow->details = {$details};
        $&dataRow->save(); \n";
            }

        }


        $stub = str_replace(['$&'], ['$'], str_replace('$DATA_ROWS$', $dataRowText, $this->buildMenu($stub)));

        return $stub;
    }

    protected function buildMenu($stub)
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();
        $nameMenu = ucwords(str_replace('-', ' ', $this->slug));
        $this->info("Menu: {$nameMenu}");
        $item = $this->getMenuItem(['menu_id' => $menu->id, 'title' => $nameMenu]);
        $stub = str_replace('$TABLE_NAME$', str_replace('-', '_', $this->slug), $stub);
        if(!$item) {
            $stub = str_replace('$DATA_MENU_ITEMS$', '', $stub);
            $this->error('Menu no found');
            return $stub;
        }
        $this->info('Menu Found');
        $itemsText = "$&item = $&this->getMenuItem(['menu_id' => {$menu->id}, 'title' => '{$nameMenu}']);
        if (!$&item) {
            $&itemArray = [
                'menu_id' => '{$menu->id}',
                'title' => '{$item->title}',
                'url' => '{$item->url}',
                'route' => '{$item->route}',
                'target' => '{$item->target}',
                'icon_class' => '{$item->icon_class}',
                'color' => '{$item->color}',
                'parent_id' => null,
                'order' => '{$item->order}'
            ];

            MenuItem::create($&itemArray);
        }";

        $stub = str_replace('$&', '$', str_replace('$DATA_MENU_ITEMS$', ($item ? $itemsText : ''), $stub));

        return $stub;
    }

    protected function getMenuItem($data) {
        return MenuItem::where($data)->first();
    }

    protected function getStub()
    {
        return __DIR__ . '/../../../database/seeds/stubs/bread.stub';
    }

    protected function dataType($data)
    {
        return DataType::where($data)->first();
    }

    protected function dataRows($id)
    {
        return DataRow::where('data_type_id', $id)->get();
    }

    protected function processDetail($details)
    {
        if(count((array) $details) < 1) {

            return 'null';
        }

        $detailArray = '[';
        foreach ($details as $key => $detail) {
            if ($detail != null) {
                if (is_object ($detail)) {
                    $detailArray .= "\n            '{$key}' => [";
                    foreach ($detail as $key2 => $detail2) {
                        if (is_object ($detail2)) {
                            $detailArray .= "\n                '{$key2}' => [";
                            foreach ($detail2 as $key3 => $detail3) {
                                $detailArray .= "\n                    '{$key3}' => '{$detail3}',";
                            }
                            $detailArray .= "\n                ],";
                        } else if (is_array($detail2)) {
                            $detailArray .= "\n                '{$key2}' => [";
                            foreach ($detail2 as $key4 => $detail4) {
                                $detailArray .= "\n                    '{$detail4}',";
                            }
                            $detailArray .= "\n                ],";
                        } else {
                            $detailArray .= "\n                '{$key2}' => '{$detail2}',";
                        }
                    }
                    $detailArray .= "\n            ],";
                } else {
                    $detailArray .= "\n                '{$key}' => '{$detail}',";
                }
            }
        }
        if ($detailArray != '[') {
            $detailArray .= "\n        ";
        }
        $detailArray .= ']';

        return $detailArray;
    }
}
