<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Contact;

class ContactController extends Controller
{
    public function sendMessage(Request $request){
        $validation = Validator::make($request->all(), [
            'name'    => 'required|max:20|min:2',
            'email'   => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);
        if($validation->passes()){
            $contact = new Contact();
            $contact->name    = $request->name;
            $contact->email   = $request->email;
            $contact->subject = $request->subject;
            $contact->message = $request->message;

            $save = $contact->save();
            if ($save) {
                return response()->json([
                    'message' => 'Successfully Submit Your Information',
                    'alert_type' => 'success',
                ]);
            } else {
                return response()->json([
                    'message' => 'Database Failed',
                    'alert_type' => 'success',
                ]);
            }
        }else{
            return response()->json([
                'error' => $validation->errors()->all(),
            ]);
        }
    }
}
