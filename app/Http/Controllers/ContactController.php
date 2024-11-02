<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        if($request->isMethod('post')){
            if (Auth::check()) {
                // Get the logged-in user's name and email
                $validatedData = $request->validate([
                    'message' => 'required|string',
                ]);

                $contactData = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'message' => $validatedData['message'],
                ];
            } else {
                // Validate all fields if user is not logged in
                $contactData = $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'message' => 'required|string',
                ]);
            }
            // Check if the user is authenticated

            // Create a new contact entry in the database
            Contact::create($contactData);
            // Redirect back with a success message
            return redirect()->back()->with('status', 'Your message has been sent successfully!');
        }
        else{

            return redirect()->route('/');
        }


    }

    public function viewUserMessages(){
        $contacts = Contact::all();
        return view('admin.user-messages', compact('contacts'));
    }
}
