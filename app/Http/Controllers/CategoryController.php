<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Categories\Index;
use App\Http\Requests\Categories\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


/**
 * Description of CategoryController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        $model = Category::where('parent_id', 0)->get();
        return view('pages.categories.index',
            [
                'parentCategories' => $model
            ]);
    }

    public function dataTableList(Index $request)
    {
        $model = DB::table('categories as c1')
            ->select(
                'c1.id',
                'c1.name',
                'c1.description',
                DB::raw('(SELECT cr.name FROM categories cr WHERE cr.id = c1.parent_id) as parent_category'),
                'c1.parent_id',
                'c1.is_deleted',
                'c1.created_at'
            )
            ->get();

        $formattedModel = $model->map(function ($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'description' => $m->description,
                'parent_category' => $m->parent_category,
                'is_deleted' => $m->is_deleted,
                'created_at' => $m->created_at
            ];
        });

        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getById($id)
    {
        $model = Category::find($id);

        if ($model) {
            return response()->json([
                'success' => true,
                'data' => $model,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category not found',
        ], 404);
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
            'parent_id' => ['integer'],
        ]);

        $slug = Str::slug($validatedData->name, '-');

        $request->validate([
            'slug' => [
                Rule::unique('categories', 'slug')->ignore($request->id),
            ]
        ]);

        $validatedData['slug'] = $slug;

        Category::updateOrCreate(
            ['id' => $request->id], // Use `id` to identify record for update
            $validatedData
        );

        return redirect()->route('category.index')->with('success', 'Category ' . $message . ' successfully.');
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
        $request->validate([
            'id' => ['required', 'exists:categories,id'], // Ensure the user exists
        ]);

        // Find the user to be deleted
        $model = Category::findOrFail($request->user_id);

        // Perform soft delete by marking the model as deleted
        $model->is_deleted = 1;
        $model->save();

        return response()->json(['message' => 'Category successfully deleted.', 200]);
    }
}
