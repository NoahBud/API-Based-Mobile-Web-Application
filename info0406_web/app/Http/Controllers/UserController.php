<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.list', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Gate::denies('create', User::class)) {
          return redirect()->route('users.index')->with('error', "Vous n'avez pas le droit de créer un utilisateur.");
        }

        
        $roles = ['admin', 'employe'];
        return view('users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if(Gate::denies('create', User::class)) {
          return redirect()->route('users.index')->with('error', "Vous n'avez pas le droit de créer un utilisateur.");
        }

        $request->validated();
        
        $user = User::create($request->input());
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Nouvel utilisateur créé !');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if(Gate::denies('update', User::class)) {
          return redirect()->route('users.index')->with('error', "Vous n'avez pas le droit de modifier un utilisateur.");
        }
        $roles = ['admin', 'employe'];
        return view('users.create', ['user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        if(Gate::denies('update', User::class)) {
          return redirect()->route('users.index')->with('error', "Vous n'avez pas le droit de modifier un utilisateur.");
        }
        $request->validated();

        $user->update($request->input());

        $user->syncRoles($request->input('role'));


        return redirect()->route('users.index', $user)->with('success', 'Utilisateur modifié !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(Gate::denies('delete', User::class)) {
          return redirect()->route('users.index')->with('error', "Vous n'avez pas le droit de supprimer un utilisateur.");
        }
        $user->delete();
 
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé !');
    }
}
