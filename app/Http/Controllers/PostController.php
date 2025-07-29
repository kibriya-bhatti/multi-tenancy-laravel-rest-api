<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Validations\PostValidation;
use App\Http\Resources\PostResource;
class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        return response()->json([
            'success' => true,
            'data' => PostResource::collection($this->postService->getAll($perPage)),
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' =>  new PostResource($this->postService->find($id)),
        ]);
    }

    public function store(Request $request)
    {
        $validator =  PostValidation::store($request->all());
        if( $validator) {
            $data['created_by'] = auth()->id();
            $post = $this->postService->store($request->all());
            if (!$post) {
                return response()->json(['success' => false, 'message' => 'Post creation failed'], 500);
            }
            return response()->json(['success' => true, 'data' =>  new PostResource($post)], 201);
        }


    }

    public function update(Request $request, $id)
    {
        $validator =  PostValidation::update($request->all(),$id);
        if( $validator) {
            $data['updated_by'] = auth()->id();
            $post = $this->postService->update($id, $data);
            if (!$post) {
                return response()->json(['success' => false, 'message' => 'Post not found'], 404);
            }
            return response()->json( new PostResource($post));
        }

    }

    public function destroy($id)
    {
        $this->postService->destroy($id);
        if (!$this->postService->find($id)) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }
        // Optionally, you can return a success message or status code
        // For example, returning a 204 No Content status:
        return response()->json(['success' => true, 'message' => 'Post deleted successfully'], 204);
    }
}
