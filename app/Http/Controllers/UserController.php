<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('id', '>', 1);

        if ($request->query('searchterm') != "") {
            $users = $users->where('name', 'like', '%' . $request->query('searchterm') . '%')
                        ->orWhere('email', 'like', '%' . $request->query('searchterm') . '%');
        }

        $users = $users->paginate(10);

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
                'message' => "User added",
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
        if ($id != 1) { //user id 1 is Superadmin
            $user = User::find($id);

            return view('users.edit')->with('user', $user);
        } else {
            $notification = [
                'message' => "Invalid operation",
                'alert-type' => 'error'
            ];

            return redirect('/users')->with($notification);
        }       
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
        $validation = [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:admin,author']            
        ];

        if ($request->password != "" || $request->confirmPassword != "") {
            $validation['password'] = ['required', 'alpha_num', 'min:8'];
            $validation['confirmPassword'] = ['required', 'same:password'];
        }

        $request->validate($validation);

        if ($id != 1) {
            $user = User::find($id);

            if (isset($user->id)) {
                $user->name = $request->name;
                $user->type = $request->type;
                
                if ($request->password != "") {
                    $user->password = Hash::make($request->password);
                }

                if ($user->update()) {
                    $notification = [
                        'message' => 'User updated',
                        'alert-type' => 'success'
                    ];

                    return redirect('/users')->with($notification);
                } else {
                    $notification = [
                        'message' => 'An unexpected error occured',
                        'alert-type' => 'error'
                    ];

                    return redirect()->back()->with($notification);
                }                
            } else {
                $notification = [
                    'message' => 'User does not exists',
                    'alert-type' => 'error'
                ];

                return redirect('/users')->with($notification);
            }            
        } else {
            $notification = [
                'message' => "Invalid operation",
                'alert-type' => 'error'
            ];

            return redirect('/users')->with($notification);
        }       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id != 1) {
            $user = User::find($id);

            if (isset($user->id)) {
                if ($user->delete()) {
                    $notification = [
                        'message' => 'User deleted',
                        'alert-type' => 'success'
                    ];
                } else {
                    $notification = [
                        'message' => 'An unexpected error occured',
                        'alert-type' => 'error'
                    ];
                }                
            } else {
                $notification = [
                    'message' => 'User does not exists',
                    'alert-type' => 'error'
                ];
            }         
        } else {
            $notification = [
                'message' => "Invalid operation",
                'alert-type' => 'error'
            ];
        }

        return redirect('/users')->with($notification);
    }

    public function profile() 
    {
        $user = User::find(Auth::user()->id);

        return view('users.profile')->with('user', $user);
    }

    public function update_profile(Request $request)
    {
        $validation = [
            'name' => ['required', 'string', 'max:255']
        ];

        if ($request->password != "" || $request->confirmPassword != "") {
            $validation['password'] = ['required', 'alpha_num', 'min:8'];
            $validation['confirmPassword'] = ['required', 'same:password'];
        }

        $request->validate($validation);
        
        $user = User::find(Auth::user()->id);

        $user->name = $request->name;
        
        if ($request->password != "") {
            $user->password = Hash::make($request->password);
        }

        if ($user->update()) {
            $notification = [
                'message' => 'Profile updated',
                'alert-type' => 'success'
            ];
        } else {
            $notification = [
                'message' => 'An unexpected error occured',
                'alert-type' => 'error'
            ];
        }

        return redirect()->back()->with($notification);
        
    }
}
