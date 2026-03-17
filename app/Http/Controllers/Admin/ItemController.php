<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Item;
use App\Category;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(Request $request){
        $item = Item::latest()->get();
        if ($request->ajax()) {
            
            return Datatables::of($item)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="btn btn-success btn-edit" title="Category Edit" data-id="' . $data->id . '"><span class="material-icons">edit</span></button>';
                    $button .= '<button type="button" class="btn btn-danger btn-delete" title="Category Delete" data-id="' . $data->id . '"><span class="material-icons">delete</span></button>';
                    return $button;
                })
                ->addColumn('item_image', function ($data) {
                    $url = asset('upload/items/' . $data->image);
                    return '<img src="' . $url . '" border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('category', function ($data) {
                    return '' . $data->category->name . '';
                })
                ->rawColumns(['action', 'item_image', 'category'])
                ->make(true);
        }
        return view('admin.partials.item.index');
    }
    public function destroy($id){
        if($id){
            $item = Item::findOrFail($id);
            if (file_exists('upload/items/'.$item->image)) {
                unlink('upload/items/'.$item->image);
            }
            
            $del = $item->delete();
            if($del){
                return response()->json([
                    "message" => "Success! Item deleted successfully:)",
                    "alert_type" => "success",
                ]);
            }else{
                return response()->json([
                    "message" => "Oppos!! Sorry Query Problem,Contact to developer",
                    "alert_type" => "error",
                ]);
            }
        }else{
            return response()->json([
                "message" => "Error! Item does not deleted something wrong:)",
                "alert_type" => "error",
            ]);
        }
    }
    public function store(Request $request){
        $validation = Validator::make($request->all(), [
            'category'    => 'required|unique:sliders,title',
            'name'        => 'required|min:5',
            'description' => 'required',
            'price'       => 'required|numeric',
            'image'       => 'required|image|mimes:jpg,jpeg,png,gif,bmp,webp',
        ]);

        if ($validation->passes()) {
            $image = $request->file('image');
            if ($image) {
                $currentDate = Carbon::now()->toDateString();
                $imageName = $currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
                if (!file_exists('upload/items')) {
                    mkdir('upload/items', 0777, true);
                }
                $image->move(public_path('upload/items'), $imageName);
            } else {
                $imageName = 'default.png';
            }

            $item = new Item();
            $item->category_id = $request->category;
            $item->name        = $request->name;
            $item->description = $request->description;
            $item->price       = $request->price;
            $item->image       = $imageName;
            $save = $item->save();
            if ($save) {
                return response()->json([
                    'message' => 'Data Inserted Successfully',
                    'alert_type' => 'success',
                ]);
            } else {
                return response()->json([
                    'message' => 'Something Wrong,Query Problem',
                    'alert_type' => 'error',
                ]);
            }
            
            
        } else {
            return response()->json([
                'message' => $validation->errors()->all(),
                'class_name' => 'alert alert-danger',
            ]);
        }
    }
    public function edit($id){
        $data = Item::findOrFail($id);
        $category = Category::all();
        return response()->json([$data, $category]);
    }
    public function update(Request $request, $id){
        
        $item = Item::find($id);

        $validation = Validator::make($request->all(), [
            'category'    => 'required',
            'name'        => 'required|min:5',
            'description' => 'required',
            'price'       => 'required|numeric',
            //'image'       => 'image|mimes:jpg,jpeg,png,gif,bmp,webp',
        ]);

        if ($validation->passes()) {
            $image = $request->file('image');
            if ($image) {
                $currentDate = Carbon::now()->toDateString();
                $imageName = $currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
                if (!file_exists('upload/items')) {
                    mkdir('upload/items', 0777, true);
                }
                if (file_exists('upload/items/'.$item->image)) {
                    unlink('upload/items/'.$item->image);
                }
                $image->move(public_path('upload/items'), $imageName);
            } else {
                $imageName = $item->image;
            }

            
            $item->category_id = $request->category;
            $item->name        = $request->name;
            $item->description = $request->description;
            $item->price       = $request->price;
            $item->image       = $imageName;
            $save = $item->save();
            if ($save) {
                return response()->json([
                    'message' => 'Data Inserted Successfully',
                    'alert_type' => 'success',
                    'image' => $item->image,
                    'category' => $item->category->name,
                ]);
            } else {
                return response()->json([
                    'message' => 'Something Wrong,Query Problem',
                    'alert_type' => 'error',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Please Fillup all field and follow our rules',
                'alert_type' => 'error',
            ]);
        }
    }
}
