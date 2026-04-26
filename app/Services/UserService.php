<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function getAll($request)
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        return $query->get();
    }

    public function update(array $data, $id)
    {
        $user = User::findOrFail($id);

        $user->update($data);

        return $user;
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function delete($id)
    {
        $user = $this->find($id);
        $user->delete();
    }
}
