<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use App\Contact;

class ContactController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $contact = Contact::all();
            return Datatables::of($contact)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="btn btn-danger btn-details" title="Message Details" data-id="' . $data->id . '"><span class="material-icons">details</span></button>';
                    $button .= '<button type="button" class="btn btn-danger btn-delete" title="Message Delete" data-id="' . $data->id . '"><span class="material-icons">delete</span></button>';
                    return $button;
                })
                ->addColumn('order', function ($data) {
                    $order = date("F j, Y, g:i a", strtotime($data->created_at));
                    return $order;
                })
                ->rawColumns(['action','order'])
                ->make(true);
        }
        return view('admin.partials.contact.index');
    }

    public function destroy($id){
        if ($id) {
            $contact = Contact::findOrFail($id)->delete();
            if ($contact == true) {
                return response()->json([
                    "message" => "Success! Reservation deleted successfully:)",
                    "alert_type" => "success",
                ]);
            } else {
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

    public function show($id){
        $contact = Contact::find($id);
        return response()->json($contact);
    }
}
