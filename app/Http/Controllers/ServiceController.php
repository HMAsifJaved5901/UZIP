<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Services\Index;
use App\Http\Requests\Services\Show;
use App\Http\Requests\Services\Create;
use App\Http\Requests\Services\Store;
use App\Http\Requests\Services\Edit;
use App\Http\Requests\Services\Update;
use App\Http\Requests\Services\Destroy;


/**
 * Description of ServiceController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        $categories = Category::where('is_deleted', 0)
            ->where('category_key', 'service')
            ->select('id', 'name')
            ->get();
        return view('pages.services.index',
            [
                'records' => Service::paginate(10),
                'categories' => $categories
            ]);
    }

    public function serviceIndex(Index $request)
    {
        $model = Service::join('categories', 'services.category_id', '=', 'categories.id')
            ->select('services.*', 'categories.name as category_name')
            ->get();
        $formattedModel = $model->map(function ($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'description' => $m->description,
                'category_name' => $m->category_name,
                'is_active' => $m->is_active,
                'is_deleted' => $m->is_deleted,
                'created_at' => $m->created_at
            ];
        });

        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getServiceById($id)
    {
        $model = Service::find($id);

        if ($model) {
            return response()->json([
                'success' => true,
                'data' => $model,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Service not found',
        ], 404);
    }

    public function getCategory(Request $request)
    {
        $categoryId = $request->category_id;

        // Fetch users based on the company
        $managers = Category::where('id', $categoryId)->get();

        return response()->json($managers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Store $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $message = isset($request->id) ? 'Updated' : 'created';
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255'],
            'category_id' => ['integer'],
        ]);

        Service::updateOrCreate(
            ['id' => $request->id], // Use `id` to identify record for update
            $validatedData
        );

        return redirect()->back()->with('success', 'Service ' . $message . ' successfully.');
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
        $model = Service::findOrFail($request->service_id);
        $deleted = $model->is_deleted == 1 ? 0 : 1;
        $model->is_deleted = $deleted;
        $model->save();

        return response()->json(['message' => 'Service successfully deleted.', 200]);
    }

    public function updateStatus(Request $request)
    {

        $id = $request->service_id;

        // Find the user to be operated
        $model = Service::findOrFail($id);

        // Perform status call by marking the user as active/inactive
        $model->is_active = $model->is_active == 1 ? 0 : 1;
        $model->save();

        return response()->json(['message' => 'Company status updated successfully.', 200]);
    }
}
