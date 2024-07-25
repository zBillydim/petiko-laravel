<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Models\User;


class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $auth = Auth::user();
        try{
            $user = User::where('id', $auth->id)->get();
            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Users fetched successfully',
                'data' => $user
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
                'message' => 'An error ocurred',
            ], 400);
        }
    }
    public function store(CreateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try{
            DB::beginTransaction();
            $newUser = new User();
            $newUser->fill($validated);
            $newUser->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'User registered successfully',
                'data' => $newUser
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
                'message' => 'Sorry we were unable to register at the moment',
            ], 400);
        }
    }
}
