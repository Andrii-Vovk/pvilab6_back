<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gamer;

class GamerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $gamer = Gamer::where('username', '=', $request->username)->first();

        if ($gamer === null) {
            return [
                'message' => 'An error occured',
                'errors' => [
                    'not found' => 'Username does not exist'
                ]
            ];
        }

        if ($gamer->password != $request->password) {
            return [
                'message' => 'An error occured',
                'errors' => [
                    'Invalid password' => 'This password is incorrect'
                ]
            ];
        }

        return [
            'message' => 'Logged in successfully!'
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'username' => ['required', 'max:15', 'min:4'],
            'email' => ['required', 'max:50', 'email:rfc,dns'],
            'password' => ['required', 'max:15', 'min:4'],
            'rank' => ['required'],
            'region' => ['required'],
            'about' => ['max:200']
        ]);

        $errsarray = array();

        if (Gamer::where('email', '=', $request->email)->exists()) {
            $errsarray = array_merge($errsarray, ['email' => 'This email already exists']);
        }
        if (Gamer::where('username', '=', $request->username)->exists()) {
            $errsarray = array_merge($errsarray, ['username' => 'This username already exists']);
        }

        if (empty($errsarray)) {
            Gamer::create($request->all());
            return ['message' => 'saved data successfully'];
        }

        $errs = [
            'message' => 'An error occured',
            'errors' => $errsarray
        ];

        return $errs;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Gamer::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gamer = Gamer::find($id);
        $gamer->update($request->all());

        return $gamer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Gamer::destroy($id);
    }
}
