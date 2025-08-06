<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\Support\TransactionService;
use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;

class AuthController extends BaseController
{
    public function __construct(
        protected TransactionService $transaction
    ) {}
    /**
     * User Registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'tenant' => 'required'
        ]);
        $user = $this->transaction->run(function () use ($validated) {
            return User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tenant_id' => $validated['tenant'] // BY default company tenant set 1. it can be change dynamically according to requirments
            ]);
        });
        $token = $user->createToken('api-token')->plainTextToken;
        return $this->success(["user" =>  new UserResource($user),"token"=>$token], 'Users register successfully.');
    }

    /**
     * User Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt login
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'The email or password is incorrect. Please try again.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        return $this->success(["user" => new UserResource($user),"token"=>$token], 'Login successful.');
    }

    /**
     * User Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success([],'Logged out successfully.');
    }
}
