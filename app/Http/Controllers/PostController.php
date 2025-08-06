<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Validations\PostValidation;
use App\Http\Resources\PostResource;
use App\Helpers\LogActivity;
class PostController extends BaseController
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 10;
        return $this->success(
            PostResource::collection($this->postService->getAll($perPage)),
            "Posts fetch successfully"
        );
    }

    public function show($id)
    {
        return $this->success(
            new PostResource($this->postService->find($id)),
            "Post fetch successfully"
        );
    }

    public function store(Request $request)
    {
        $validator =  PostValidation::store($request->all());
        if( $validator) {
            $post = $this->postService->store($request->all());
            LogActivity::addToLog('Post created');
            return $this->success(
                new PostResource($post),
                "Post created successfully"
            );
        }

    }

    public function update(Request $request, $id)
    {
        $validator =  PostValidation::update($id,$request->all());
        if( $validator) {
            $post = $this->postService->update($id, $request->all());
            LogActivity::addToLog('Post updated');
            return $this->success(
                new PostResource($post),
                "Post updated successfully"
            );
        }
    }

    public function destroy($id)
    {
        $this->postService->destroy($id);
        LogActivity::addToLog('Post deleted');
        return $this->success(
            '',
            "Post deleted successfully"
        );
    }
}
