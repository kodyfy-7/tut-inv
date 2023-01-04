<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'type' => 'required|in:customer,vendor',
            'email' => 'required',
            'password' => 'required',
            'name' => 'required',
            'phone' => 'required'
        ]);
        
        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()->all()]);
        }

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
