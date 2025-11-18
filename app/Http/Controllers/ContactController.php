<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ContactController extends Controller
{
    public function index(){
        $contact=Contact::with('user:id,name')->get();
        return response()->json($contact);
    }
    public function store(Request $request){
        $data= $request->validate([
            "phone"=> "required",
            "address"=> "required",
            "city"=>"nullable|string",
            "country"=> "nullable|string",
            ]);
            $data['user_id']=Auth::id();
            $contact=Contact::create($data);
            return response()->json([
                "status"=>"true",
                "message"=> "Sucesfully stored contact",
                "data"=>$data
            ]);
    }
    public function update(Request $request,$id){
        $data= $request->validate([
            "phone"=> "required",
            "address"=> "required",
            "city"=>"nullable|string",
            "country"=> "nullable|string",
            ]);
            $contact=Contact::find($id);
            $contact->update($data);
            return response()->json([
                "status"=> "true",
                "message"=> "successfull updated contact"
                ],200);
        }
    public function show($id)
    {
        $contact=Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'No contact found'], 404);
        }

        return response()->json(['message'=>'contacts'],200);
    }
    public function destroy($id){
        $contact = Contact::find($id);
        $contact->delete(); 
        return response()->json([
            'status'=> true,
            'message'=> 'Contact Deleted Successfull'],200);
    }
}