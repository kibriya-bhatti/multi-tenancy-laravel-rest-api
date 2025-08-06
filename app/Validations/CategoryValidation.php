<?php
namespace App\Validations;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
class CategoryValidation
{
    public static function store($data): array
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'slug'         => 'required|string|unique:posts,slug'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $validator->validated();
    }

    public static function update(int $categoryId,$data): array
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'slug'         => [
                'required',
                'string',
                Rule::unique('categories', 'slug')
                ->ignore($categoryId)
                ->where(fn ($query) => $query->where('tenant_id', auth()->user()->tenant_id)),
            ]
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
