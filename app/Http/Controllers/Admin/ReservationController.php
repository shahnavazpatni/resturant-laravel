<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use App\Reservation;

use App\Notifications\ReservationConfirmed;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $reservations = Reservation::all();
            return Datatables::of($reservations)
                ->addColumn('status', function ($data) {
                    if($data->status == true){
                        $status = '<button class="badge badge-success">Confirmed</button>';
                    }else{
                    $status = '<button class="badge badge-danger">Pending</button>';
                    }
                    return $status;
                })
                ->addColumn('order', function ($data) {
                    $order = date("F j, Y, g:i a", strtotime($data->created_at));
                    return $order;
                })
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="btn btn-success btn-confirmed" title="Category Delete" data-id="' . $data->id . '"><span class="material-icons">check</span></button>';
                    $button .= '<button type="button" class="btn btn-danger btn-delete" title="Category Delete" data-id="' . $data->id . '"><span class="material-icons">delete</span></button>';
                    return $button;
                })
                ->rawColumns(['status','order','action',])
                ->make(true);
        }
        return view('admin.partials.reservation.index');
    }

    public function status($id){
        $reservation = Reservation::findOrFail($id);
        if($reservation->status == false){
            $reservation->status = true;
            $reservation->save();
            Notification::route('mail', $reservation->email)
            ->notify(new ReservationConfirmed());
            
            return response()->json([
                'message' => 'Success! Reservation Successfully Enable',
                'alert_type' => 'success',
                'html' => '<button class="badge badge-success">Confirmed</button>',
            ]);
        }else{
            $reservation->status = false;
            $reservation->save();
            return response()->json([
                'message' => 'Success! Reservation Successfully Disable',
                'alert_type' => 'success',
                'html' => '<button class="badge badge-danger">Pending</button>',
            ]);
        }
    }

    public function destroy($id){
        if ($id) {
            $reservation = Reservation::findOrFail($id)->delete();
            if($reservation == true){
                return response()->json([
                    "message" => "Success! Reservation deleted successfully:)",
                    "alert_type" => "success",
                ]);
            }else{
                return response()->json([
                    "message" => "Oppos!! Something Wrong,Contact Developer:)",
                    "alert_type" => "error",
                ]);
            }
        } else {
            return response()->json([
                "message" => "Error! Reservation does not deleted something wrong:)",
                "alert_type" => "error",
            ]);
        }
    }
}
