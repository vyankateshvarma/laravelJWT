<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ContactController extends Controller
{
    public function index(){
        $contact=Contact::all();
        return response()->json($contact);
    }
    public function store(Request $request){
        $data= $request->validate([
            "phone"=> "required",
            "address"=> "required"
            ]);
            $data['user_id']=Auth::id();
            $contact=Contact::create($data);
            return response()->json([
                "status"=>"true",
                "message"=> "Sucesfully stored contact",
                "data"=>$data
            ]);
    }
    public function show()
    {
        $user = Auth::user();
        $contact = $user->contact;

        if (!$contact) {
            return response()->json(['message' => 'No contact found'], 404);
        }

        return response()->json(['contact' => $contact]);
    } 

}
