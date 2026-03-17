<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Category;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {

    public function index(Request $request){
        if($request->ajax()){
            $categories = Category::latest()->get();
            return Datatables::of($categories)
            ->addColumn('action', function ($data) {
                $button = '<button type="button" class="btn btn-success btn-edit" title="Category Edit" data-id="' . $data->id . '"><span class="material-icons">edit</span></button>';
                $button .= '<button type="button" class="btn btn-danger btn-delete" title="Category Delete" data-id="' . $data->id . '"><span class="material-icons">delete</span></button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.partials.category.index');
    }
    public function destroy($id){
        if($id){
            Category::find($id)->delete();
            return response()->json([
                "message" => "Success! Category deleted successfully:)",
                "alert_type" => "success",
            ]);
        }else{
            return response()->json([
                "message" => "Error! Category does not deleted something wrong!",
                "alert_type" => "error",
            ]);
        } 
    }
    public function store(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name|max:20|min:3',
        ]);
        if ($validation->passes()){
           $data = new Category;
           $data->name = $request->name;
           $data->slug = Str::slug($request->name);
           $store = $data->save();
           if($store){
                return response()->json([
                    'message' => 'Success: Category Inserted Successfully',
                    'alert_type' => 'success',
                ]);
            }else{
                return response()->json([
                    'error' => 'Oppos!: Sorry Query Connection Failed!',
                    'alert_type' => 'error',
                ]);
           }
        }else{
            return response()->json([
                'error' => $validation->errors()->all(),
                'alert_type' => 'error',
            ]);
        }
    }
    public function edit($id){
        $data = Category::findOrFail($id);
        return response()->json($data);
    }
    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'update_name' => 'required|max:20|min:3',
        ]);

        $id = $request->hidden_id;
        $data = Category::find($id);

        if($validation->passes()){
            $data->name = $request->update_name;
            $data->slug = Str::slug($request->update_name);
            $update = $data->save();
            if ($update != false) {
                return response()->json([
                    'message' => 'Category Updated Successfully',
                    'alert_type' => 'success',
                    'name' => $request->update_name,
                    'slug' => Str::slug($request->update_name),
                ]);
            } else {
                return response()->json([
                    'message' => 'Sorry Data does not update! Database Problem',
                    'alert_type' => 'error',
                ]);
            }
        }else {
            return response()->json([
                'require' => $validation->errors()->all(),
                'alert_type' => 'error',
            ]);
        }
    }

    public function getCategory(){
        $data = Category::all();
        return response()->json($data);
    }
}
