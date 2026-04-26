<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Http\Resources\UserResource;


class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // public function index(Request $request)
    // {
    //     return UserResource::collection(
    //         $this->userService->getAll($request)
    //     );
    // }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => new UserResource($user)
        ]);
    }

    public function show($id)
    {
        $user = $this->userService->find($id);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->update($request->validated(), $id);

        return response()->json([
            'message' => 'User berhasil diubah',
            'data' => new UserResource($user)
        ]);
    }

    public function destroy($id)
    {
        $this->userService->delete($id);

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }
}
