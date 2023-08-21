<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\profile\UpdateProflleRequest;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $id = Auth::user()->id;

        $data['status'] = true;
        $data['user'] = User::find($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProflleRequest $request)
    {
        $fields = $request->validated();

        $id = Auth::user()->id;
        $profile = User::find($id);

        DB::transaction(function () use ($fields, $profile) {
            $profile->update($fields);
        });

        $data['status'] = true;
        $data['user']= $profile;
        $data['msg']= "Password updated";

        return response()->json($data);
    }
}
