<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\configuration;


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
        return view('pages.categories.configuration_index',
            [
                'configuration_list' => config('general.configuration_list'),
            ]);
    }

    public function ConfigIndex(Request $request)
    {
        $model = configuration::all();
        $formattedModel = $model->map(function ($m) {
            return [
                'id' => $m->id,
                'config_key' => $m->config_key,
                'label' => $m->label,
                'value' => $m->value,
                'description' => $m->description,
                'is_deleted' => $m->is_deleted
            ];
        });

        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getConfigById($id)
    {
        $model = configuration::find($id);

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
            'config_key' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255'],
        ]);

        configuration::updateOrCreate(
            ['id' => $request->id], // Use `id` to identify record for update
            $validatedData
        );

        return  redirect()->back()->with('success', 'configuration ' . $message . ' successfully.');

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
        $model = configuration::findOrFail($request->id);
        // Perform soft delete by marking the model as deleted
        $deleted = $model->is_deleted == 1 ? 0 : 1;

        $model->is_deleted = $deleted;
        $model->save();

        $msg = $model->is_deleted == 1 ? 'restored' : 'deleted';

        return redirect()->back()->with('app_message','configuration successfully ' .$msg);

    }
}
