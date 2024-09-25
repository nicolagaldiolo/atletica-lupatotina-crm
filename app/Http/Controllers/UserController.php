<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Retrieves the index page for the module.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        if (request()->ajax()) {
            return datatables()->eloquent(User::query()->with('roles'))->addColumn('action', function ($user) {
                return view('backend.users.partials.action_column', compact('user'));
            })->make(true);
        }else{
            return view('backend.users.index');
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $user = new user();
        
        $roles = null;
        $permissions = null;
        
        if(auth()->user()->can('assign', Role::class)){
            $roles = Role::get();
            $permissions = Permission::select('name', 'id')->get();
        }

        return view('backend.users.create', compact('user', 'roles', 'permissions'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function store(UsersRequest $request)
    {

        $this->authorize('create', User::class);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if(auth()->user()->can('assign', Role::class)){
            $roles = $request['roles'];
            $permissions = $request['permissions'];

            // Sync Roles
            if (isset($roles)) {
                $user->syncRoles($roles);
            }

            // Sync Permissions
            if (isset($permissions)) {
                $user->syncPermissions($permissions);
            }
        }

        Utility::flashMessage();

        return $this->canRedirectOrHome();
    }

    /**
     * Edit a record in the database.
     *
     * @param  int  $id  The ID of the record to be edited.
     * @return \Illuminate\View\View The view for editing the record.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user does not have the permission to edit users.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = null;
        $permissions = null;
        $userRoles = null;
        $userPermissions = null;

        if(auth()->user()->can('assign', Role::class)){
            $roles = Role::get();
            $permissions = Permission::select('name', 'id')->get();
            $userRoles = $user->roles->pluck('name')->all();
            $userPermissions = $user->permissions->pluck('name')->all();
        }

        return view('backend.users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));    
    }

    /**
     * Updates a user with the given ID.
     *
     * @param  Request  $request  The HTTP request object.
     * @param  int  $id  The ID of the user to update.
     * @return RedirectResponse The redirect response to the admin module.
     *
     * @throws NotFoundHttpException If the authenticated user does not have the 'edit_users' permission.
     */
    public function update(UsersRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->except(['roles', 'permissions']));

        if(auth()->user()->can('assign', Role::class)){
            $roles = $request['roles'];
            $permissions = $request['permissions'];

            // Sync Roles
            if (isset($roles)) {
                $user->syncRoles($roles);
            } else {
                $roles = [];
                $user->syncRoles($roles);
            }

            // Sync Permissions
            if (isset($permissions)) {
                $user->syncPermissions($permissions);
            } else {
                $permissions = [];
                $user->syncPermissions($permissions);
            }
        }

        Utility::flashMessage();

        return $this->canRedirectOrHome();
    }

    /**
     * Deletes a user by their ID.
     *
     * @param  int  $id  The ID of the user to be deleted.
     * @return Illuminate\Http\RedirectResponse
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException If the user with the given ID is not found.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
        Utility::flashMessage();

        return $this->canRedirectOrHome();
    }

    /**
     * Block a user.
     *
     * @param  int  $id  The ID of the user to block.
     * @return Illuminate\Http\RedirectResponse
     *
     * @throws Exception There was a problem updating this user. Please try again.
     */
    public function block(User $user)
    {
        $this->authorize('block', $user);

        $user->status = 2;
        $user->save();

        Utility::flashMessage();
        
        return $this->canRedirectOrHome();
    }

    /**
     * Unblock a user.
     *
     * @param  int  $id  The ID of the user to unblock.
     * @return RedirectResponse The redirect back to the previous page.
     *
     * @throws Exception If there is a problem updating the user.
     */
    public function unblock(User $user)
    {
        $this->authorize('block', $user);

        $user->status = 1;
        $user->save();

        Utility::flashMessage();

        return $this->canRedirectOrHome();
    }

    /**
     * Updates the password for a user.
     *
     * @param  int  $id  The ID of the user whose password will be changed.
     * @return \Illuminate\Contracts\View\View The view for the "Change Password" page.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user cannot be found.
     */
    public function changePassword(User $user)
    {
        $this->authorize('update', $user);

        return view("backend.users.change_password", compact('user'));
    }

    /**
     * Updates the password for a user.
     *
     * @param  Request  $request  The request object containing the new password.
     * @param  int  $id  The ID of the user whose password is being updated.
     * @return \Illuminate\Http\RedirectResponse The response object redirecting to the admin module.
     *
     * @throws \Illuminate\Validation\ValidationException If the validation fails.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user with the given ID is not found.
     */
    public function changePasswordUpdate(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $user->update([
            'password' => Hash::make($request->get('password'))
        ]);

        Utility::flashMessage();

        return $this->canRedirectOrHome();
    }

    protected function canRedirectOrHome()
    {
        if(auth()->user()->can('viewAny', User::class)){
            return redirect(route('users.index'));
        }else{
            return redirect(route('dashboard'));
        }
    }
}
