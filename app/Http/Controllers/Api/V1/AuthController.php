<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Logins the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LoginRequest $request)
    {
        $user = User::with('role')->where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(['error' => 'invalid credentials'], 401);
        }

        $user->token = $user->createToken('tut-inv-token')->plainTextToken;

        return response(['data' => $user], 200);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        $role = Role::where('slug', $request->type)->first();
        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'username' => Str::kebab($request->name),
                'role_id' => $role->id
            ]);
            
            Supplier::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'user_id' => $user->id
            ]);

            $user->token = $user->createToken('tut-inv-token')->plainTextToken;
            DB::commit();
            return response(['data' => $user], 201);
        } catch(\Exception $e)
        {
            DB::rollback();
            return response(['error' => $e->getMessage()], 500);
        }
    }

}
