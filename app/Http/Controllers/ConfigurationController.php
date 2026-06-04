<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * Description of ConfigurationController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $settingKey = $request->route('type');
        $configuration_list = config('general.configuration_list');

        // Filter all keys that contain the substring "wage" (case-insensitive)
        $filteredConfiguration = array_filter($configuration_list, function ($key) use ($settingKey) {
            return str_contains(strtolower($key), strtolower($settingKey)); // Case-insensitive match
        }, ARRAY_FILTER_USE_KEY);

        $services = Service::select(['id', 'name'])->where('is_deleted', 0)->where('is_active', 1)->get();
        return view('pages.categories.configuration_index',
            [
                'configuration_list' => $filteredConfiguration,
                'services' => $services,
                'settingKey' => $settingKey,
            ]
        );
    }

    public function dataTableList(Request $request)
    {
        $settingKey = $request->route('type');
        $model = Configuration::leftJoin('services','services.id','=','configurations.service_id')
            ->select(
                'configurations.*',
                DB::raw("COALESCE(services.name, 'NA') as service_name")
            )
            ->where('configurations.is_deleted', 0)
            ->get();

        // Filter the model based on the $settingKey
        $model = $model->filter(function ($item) use ($settingKey) {
            return strpos($item->config_key, $settingKey) !== false; // Only include items with the $settingKey in the config_key
        });

        $configList = config('general.configuration_list');
        $formattedModel = $model->map(function ($m) use ($configList) {
            return [
                'id' => $m->id,
                'config_key' => $configList[$m->config_key] ?? 'Unknown Key',
                'service_name' => $m->service_name,
                'label' => $m->label,
                'value' => $m->value,
                'value_unit' => $m->value_unit,
                'description' => $m->description,
                'is_deleted' => $m->is_deleted
            ];
        })->values(); // Reset keys to be sequential

        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getById($id)
    {
        $model = Configuration::find($id);

        if ($model) {
            return response()->json([
                'success' => true,
                'data' => $model,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Configuration not found',
        ], 404);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = isset($request->id) ? 'Updated' : 'created';
        $validatedData = $request->validate([
            'service_id' => 'integer',
            'config_key' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'value_unit' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            Configuration::updateOrCreate(
                ['id' => $request->id],
                $validatedData
            );
        } catch (\Exception $e) {
            Log::error('Database Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'configuration ' . $message . ' successfully.');

    }

    /**
     * Delete a  resource from  storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        // Find the user to be deleted
        $model = Configuration::findOrFail($request->id);
        // Perform soft delete by marking the model as deleted
        $deleted = $model->is_deleted == 1 ? 0 : 1;

        $model->is_deleted = $deleted;
        $model->save();

        $msg = $model->is_deleted == 1 ? 'restored' : 'deleted';

        return redirect()->back()->with('app_message', 'configuration successfully ' . $msg);

    }
}
