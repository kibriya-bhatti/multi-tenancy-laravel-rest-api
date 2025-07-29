<?php
namespace App\Validations;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
class PostValidation
{
    public static function store($data): array
    {
        $rules = [
            'title'        => 'required|string|max:255',
            'slug'         => 'required|string|unique:posts,slug',
            'category_id'  => 'required|exists:categories,id',
            'content'      => 'nullable|string',
            'featured_img' => 'nullable|image|max:2048',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $validator->validated();
    }

    public static function update($data,int $postId ): array
    {
        $rules = [
            'title'        => 'required|string|max:255',
            'slug'         => ['required', 'string', Rule::unique('posts', 'slug')->ignore($postId)],
            'category_id'  => 'required|exists:categories,id',
            'content'      => 'nullable|string',
            'featured_img' => 'nullable|image|max:2048',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $validator->validated();
    }

    public static function delete(): array
    {
        return [
            'id' => 'required|exists:posts,id',
        ];
    }

    public static function find(): array
    {
        return [
            'id' => 'required|exists:posts,id',
        ];
    }
    public static function all(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
    public static function search(): array
    {
        return [
            'query' => 'required|string|max:255',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
