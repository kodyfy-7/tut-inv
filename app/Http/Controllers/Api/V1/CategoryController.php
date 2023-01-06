<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Permission;

class CategoryController extends Controller
{
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$this->permission->userHasPermission($request->user(), 'category-index')) {
            return response(['error' => 'Restricted access'], 403);
        } 

        $categories = Category::orderBy('name', 'ASC')->get();
        return response(['data' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if (!$this->permission->userHasPermission($request->user(), 'category-store')) {
            return response(['error' => 'Restricted access'], 403);
        }

        $category = Category::where('name', $request->name)->first();
        if($category)
        {
            return response(['error' => 'Category name exists'], 400);
        }
        Category::create([
            'name' => $request->name,
            'slug' => Str::kebab($request->name)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$this->permission->userHasPermission($request->user(), 'category-update')) {
            return response(['error' => 'Restricted access'], 403);
        }

        $category = Category::find($id);
        if(!$category)
        {
            return response(['error' => 'Category not found']);
        }

        if($request->name != $category->name)
        {
            $slug = Str::kebab($request->name);
        }

        Category::where('id', $id)->update([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return response(['success' => true, 'message' => 'Successfully updated'], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!$this->permission->userHasPermission($request->user(), 'category-delete')) {
            return response(['error' => 'Restricted access'], 403);
        }

        $category = Category::find($id);
        if(!$category)
        { 
            return response(['error' => 'Category not found'], 404);
        }
        $category->delete();
    }
}
