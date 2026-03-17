<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Reservation;
use Illuminate\Support\Facades\Validator;

use Brian2694\Toastr\Toastr;

class ReservationController extends Controller
{
    public function reverse(Request $request){
        //return response()->json($request);
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:20|min:2',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'date_and_time' => 'required',
        ]);
        if ($validation->passes()) {
            $reservations = new Reservation();
            $reservations->name = $request->name;
            $reservations->phone = $request->phone;
            $reservations->email = $request->email;
            $reservations->date_and_time = $request->date_and_time;
            $reservations->message = $request->message;
            $reservations->status = false;
            
            $save = $reservations->save();
            if($save){
                return response()->json([
                    'message' => 'Successfully Submit Your Information',
                    'alert_type' => 'success',
                ]);
            }else{
                return response()->json([
                    'message' => 'Database Failed',
                    'alert_type' => 'success',
                ]);
            }
        }else{
            return response()->json([
                'message' => 'Something Wrong!!',
                'alert_type' => 'error',
                'error' => $validation->errors()->all(),
            ]);
        }
    }
}
