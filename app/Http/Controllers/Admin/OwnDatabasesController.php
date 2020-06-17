<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-13
 * Time: 01:48
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\CustomDataImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OwnDatabasesController extends Controller
{

    protected $path;

    public function __construct()
    {
        $this->path = resource_path('/files/custom_data_files');
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            return response()->json(['imports' => CustomDataImport::orderBy('id', 'desc')->get()]);
        }

        return view('admin.own-databases');
    }

    public function getFiles()
    {
        $files = File::files($this->path);

        $collection = collect($files);

        $files_names = $collection->map(function ($file) {
            return $file->getBasename();
        });

        return response()->json(['files' => $files_names]);
    }

    public function getFileFields(Request $request)
    {


        $line = file($this->path . '/' . $request->get('file'))[0];

        $exploded = explode($request->delimiter, $line);

        $files_exploded = collect($exploded)->filter(function ($item) {
            return !in_array($item, ['',]);
        });
        $mapTypes = [];

        foreach (CustomDataImport::MAP_TYPES as $key => $value) {
            array_push($mapTypes, [
                'key' => $key,
                'value' => $value,
            ]);
        }


        return response()->json(['fields' => $files_exploded, 'mapTypes' => $mapTypes]);

    }


    public function saveFileFields(Request $request)
    {

        CustomDataImport::create($request->all());

        return response()->json([]);

    }
}
