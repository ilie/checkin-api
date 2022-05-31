<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Services\ChangePasswordService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;

class UsersController extends Controller
{

    // List all users
    public function index(): UserCollection
    {
        $this->authorize('view', User::class);
        $users = User::query();
        return UserCollection::make(
            $users
                ->allowedSortFields()
                ->allowedFilterFields()
                ->jsonPaginate()
        );
    }

    // Get a user
    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);
        return UserResource::make($user);
    }

    // Create a new user
    public function store(StoreUserRequest $request, User $user): UserResource
    {
        $this->authorize('create', $user);
        $validatedData = $request->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
        return UserResource::make($user);
    }

    // Update a user
    public function update(UpdateUserRequest $request, $id): UserResource
    {
        $this->authorize('update', User::class);
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return UserResource::make($user);
    }

    // Delete a user
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json(null, 204);
    }

    // Search for a user
    public function search(Request $request)
    {
        $this->authorize('view', User::class);
        $users = User::where('name', 'like', '%' . $request->name . '%')
            ->orWhere('nif', 'like', '%' . $request->nif . '%')
            ->orWhere('email', 'like', '%' . $request->email . '%')
            ->orWhere('social_sec_num', 'like', '%' . $request->social_sec_num . '%')
            ->get();
        return UserCollection::make($users);
    }

    // Set a user's password
    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $this->authorize('update', $user);
        return ChangePasswordService::changePassword($request);
    }
}
