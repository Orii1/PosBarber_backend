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

        // 🔍 filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 🔎 search name/email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 📄 pagination
        $perPage = $request->get('per_page', 5);

        return $query->paginate($perPage);
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
