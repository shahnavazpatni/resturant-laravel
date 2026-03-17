<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Category;
use App\Item;
use App\Slider;
use App\Reservation;
use App\Contact;

use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function index(){
        $categoryCount = Category::count();
        $itemCount = Item::count();
        $sliderCount = Slider::count();
        $reservations = Reservation::where('status', false)->get();
        $contactCount = Contact::count();
        return view('admin.partials.dashboard', compact('categoryCount', 'itemCount', 'sliderCount', 'reservations', 'contactCount'));
    }
    public function reserve(Request $request){
        if ($request->ajax()) {
            $reservations = Reservation::where('status', false)->get();
            return Datatables::of($reservations)
                ->addColumn('status', function ($data) {
                    if ($data->status == true) {
                        $status = '<button class="badge badge-success">Confirmed</button>';
                    } else {
                        $status = '<button class="badge badge-danger">Pending</button>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="btn btn-success btn-confirmed" title="Category Delete" data-id="' . $data->id . '"><span class="material-icons">check</span></button>';
                    $button .= '<button type="button" class="btn btn-danger btn-delete" title="Category Delete" data-id="' . $data->id . '"><span class="material-icons">delete</span></button>';
                    return $button;
                })
                ->rawColumns(['status', 'action',])
                ->make(true);
        }
        return view('admin.partials.reservation.index');
    }
}
