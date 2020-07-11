<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('searchterm') != "") {
            $users = User::where('id', '>', 1)->where('name', 'like', '%' . $request->query('searchterm') . '%')->orWhere('email', 'like', '%' . $request->query('searchterm') . '%')->paginate(10);
        } else {
            $users = User::where('id', '>', 1)->paginate(10);
        }            

        return view('users.list')->with('users', $users->appends($request->except('page')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'type' => ['required', 'in:admin,author'],
            'password' => ['required', 'alpha_num', 'min:8'],
            'confirmPassword' => ['required', 'same:password']
        ]); 

        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            $notification = [
                'message' => "User added successfully",
                'alert-type' => 'success'
            ];

            return redirect('/users')->with($notification);        
        } else {
            $notification = [
                'message' => "An unexpected error occured",
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);            
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
