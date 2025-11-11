<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::with('preference')->get();
        // if ($users->isEmpty()) {
        //     return $this->sendError('No users found', [], 404);
        // }
        // return $this->sendSuccess('Users retrieved successfully', $users);

        // "meta": {
        // "total": 100,
        // "limit": 10,
        // "page": 1,
        // "total_pages": 10,
        // "has_next": true,
        // "has_previous": false}

        $perPage = request()->get('limit', 10);
        $users = User::with('preference')->paginate($perPage);
        $meta = [
            'total' => $users->total(),
            'limit' => $users->perPage(),
            'page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'has_next' => $users->hasMorePages(),
            'has_previous' => $users->currentPage() > 1,
        ];
        // return $this->sendSuccess('Users retrieved successfully', $users);
        return $this->sendSuccess('Users retrieved successfully', $users->items(), 200, $meta);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $data['password'] = hash('sha256', $data['password']);
        $user = User::create($data);
        // preference
        $user->preference->create([
            'email' => true,
            'push' => true
        ]);
        return $this->sendSuccess('User created successfully', $user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('preference')->find($id);
        if (!$user) {
            return $this->sendError('User not found', [], 404);
        }

        return $this->sendSuccess('User retrieved successfully', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendError('User not found', [], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'push_token' => 'string',
            'preference' => 'array',
            'preference.email' => 'nullable|boolean',
            'preference.push' => 'nullable.boolean',
        ]);

        $user->update($data);
        if ($data['preference']) {
            # code...
            $user->preference->update($data['preference']);
        }
        return $this->sendSuccess('User updated successfully', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendError('User not found', [], 404);
        }

        $user->delete();
        return $this->sendSuccess('User deleted successfully', [], 204);
    }

    // success message
    public function sendSuccess($message, $data = [], $code = 200, $meta = null)
    {
        $res = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
        if ($meta) {
            $res['meta'] = $meta;
        }
        return response()->json($res, $code);
    }

    // error message
    public function sendError($message, $code = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $message
        ], $code);
    }
}
