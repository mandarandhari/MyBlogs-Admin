<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('searchterm') && $request->get('searchterm') != "") {
            $contacts = Contact::where('name', 'like', '%' . $request->get('searchterm') . '%')->orWhere('email', 'like', '%' . $request->get('searchterm') . '%')->orderBy('id', 'desc')->paginate(10);
        } else {
            $contacts = Contact::orderBy('id', 'desc')->paginate(10);
        }
        
        return view('contacts.list')->with('contacts', $contacts->appends( $request->except('page') ));
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        if ( isset( $contact->id ) ) {
            if ( $contact->delete() ) {
                $notification = [
                    'message' => 'Contact deleted',
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
                'message' => 'Contact does not exists',
                'alert-type' => 'error'
            ];
        }
        
        return redirect()->back()->with($notification);
    }
}
