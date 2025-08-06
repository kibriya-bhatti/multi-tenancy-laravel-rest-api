<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Validations\CategoryValidation;
use App\Http\Resources\CategoryResource;
use App\Helpers\LogActivity;
class CategoryController extends BaseController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 10;
        return $this->success(
            CategoryResource::collection($this->categoryService->getAll($perPage)),
            "Categorys fetch successfully"
        );
    }

    public function show($id)
    {
        return $this->success(
            new CategoryResource($this->categoryService->find($id)),
            "Category fetch successfully"
        );
    }

    public function store(Request $request)
    {
        $validator =  CategoryValidation::store($request->all());
        if( $validator) {
            $post = $this->categoryService->store($request->all());
            LogActivity::addToLog('Category created');
            return $this->success(
                new CategoryResource($post),
                "Category created successfully"
            );
        }

    }

    public function update(Request $request, $id)
    {
        $validator =  CategoryValidation::update($id,$request->all());
        if( $validator) {
            $post = $this->categoryService->update($id, $request->all());
            LogActivity::addToLog('Category updated');
            return $this->success(
                new CategoryResource($post),
                "Category updated successfully"
            );
        }
    }

    public function destroy($id)
    {
        $this->categoryService->destroy($id);
        LogActivity::addToLog('Category deleted');
        return $this->success(
            '',
            "Category deleted successfully"
        );
    }
}
