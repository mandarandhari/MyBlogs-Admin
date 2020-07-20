<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('searchterm') && $request->get('searchterm') != "") {
            $customers = Customer::where( 'name', 'like', '%' . $request->get('searchterm') . '%' )->orWhere( 'email', 'like', '%' . $request->get('searchterm') . '%' )->orderBy('id', 'desc')->paginate(10);
        } else {
            $customers = Customer::orderBy('id', 'desc')->paginate(10);
        }       

        return view('customers.list')->with('customers', $customers->appends( $request->except('page') ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.add');
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'alpha_num', 'min:8'],
            'confirmPassword' => ['required', 'alpha_num', 'same:password']
        ]);

        $customer = new Customer;

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->password = Hash::make($request->password);

        if ($customer->save()) {
            $notification = [
                'message' => 'Customer added',
                'alert-type' => 'success'
            ];

            return redirect('/customers')->with($notification);
        } else {
            $notification = [
                'message' => 'An unexpected error occured',
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
        $customer = Customer::find($id);

        if ( isset( $customer->id ) ) {
            return view('customers.edit')->with('customer', $customer);
        } else {
            $notification = [
                'message' => 'Customer does not exists',
                'alert-type' => 'error'
            ];

            return redirect('/customers')->with($notification);
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
        $validation_array = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255']
        ];

        if ($request->password != "" || $request->confirmPassword != "") {
            $validation_array['password'] = ['required', 'alpha_num', 'min:8'];
            $validation_array['confirmPassword'] = ['required', 'alpha_num', 'same:password'];
        }

        $request->validate($validation_array);

        $customer = Customer::find($id);

        if ( isset( $customer->id ) ) {
            $customer->name = $request->name;
            $customer->phone = $request->phone;

            if ($request->password != "") {
                $customer->password = Hash::make($request->password);
            }

            if ( $customer->update() ) {
                $notification = [
                    'message' => 'Customer updated',
                    'alert-type' => 'success'
                ];

                return redirect('/customers')->with($notification);
            } else {
                $notification = [
                    'message' => 'An unexpected error occured',
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }            
        } else {
            $notification = [
                'message' => 'Customer does not exists',
                'alert-type' => 'error'
            ];

            return redirect('/customers')->with($notification);
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
        $customer = Customer::find($id);

        if ( isset( $customer->id ) ) {
            if ( $customer->delete() ) {
                $notification = [
                    'message' => 'Customer deleted',
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
                'message' => 'Customer does not exists',
                'alert-type' => 'error'
            ];
        } 
        
        return redirect('/customers')->with($notification);
    }
}
