<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAll(){
        $category = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'categories'=> $category
        ]);
    }
    public function index(){
        $category = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'categories'=> $category
        ]);
    }
    public function garbageView(){
        $category = Category::where('status', '1')->get();
        return response()->json([
            'status' => 200,
            'categories'=> $category
        ]);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => 'required|unique:categories|min:5',
            'slug' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'err_mess' => $validator->messages()
            ]);
        }else{
            $category = new Category();
            $category->name = $req->input('name');
            $category->slug = $req->input('slug');
            $category->status = $req->input('status') == true ? '1' : '0';
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Category was created successfully'
            ]);
        } 
    }
    public function edit($id){
        $idCate = Category::find($id);
        if($idCate){
            return response()->json([
                'status' => 200,
                'category' => $idCate
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Id Category not found'
            ]);
        }
    }
    public function update(Request $req, $id){
        $validator = Validator::make($req->all(),[
            'name' => 'required|min:5',
            'slug' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'err_mess' => $validator->messages()
            ]);
        }else{
            $category =  Category::find($id);
            if($category){
                $category->name = $req->input('name');
                $category->slug = $req->input('slug');
                $category->status = $req->input('status') == true ? '1' : '0';
                $category->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Category was update successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'No Category was found'
                ]);
            }
        } 
    }
    public function delete($id){
        $category = Category::find($id);
        $isProduct = Product::where('category_id', $id)->limit(1)->get()->count();
        if($isProduct > 0){
            return response()->json([
                'status' => 404,
                'message' => 'Have product in category'
            ]);
        }else{
            $category->update([
                'status' => '1',
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Category was move to garbage'
            ]);
        }
    }

    public function restore($id){
        $category = Category::find($id);
        if($category){
            $category->update([
                'status' => '0',
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Category was restored successfully'
            ]);
        }
    }
    public function destroy($id){
        $category = Category::find($id);
        if($category){
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category was delete successfully'
            ]);
        }
    }
}
