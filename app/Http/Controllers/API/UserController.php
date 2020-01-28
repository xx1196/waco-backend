<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Mail\UserCreatedMail;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();

        if ($users->count()) {
            return $this->showAll($users);
        } else {
            return $this->showNone();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->all());

        return $this->showOne($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = $request->password;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse("No se encuentra cambios para el usuario $user->name", 422);
        }

        $user->save();

        return $this->showOne($user, "El usuario $user->name se ha modificado con éxito");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($user)
    {
        $user = User::withTrashed()
            ->whereId($user)
            ->first();

        $user->forceDelete();

        return $this->showOne($user, "El usuario $user->name se ha eliminado de forma permanente con éxito");
    }

}
