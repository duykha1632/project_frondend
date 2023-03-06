<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index() {
        $product = Product::all();
        return response()->json([
            'status' => 200,
            'products'=> $product
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
            'name' => 'required|min:5',
            'description' => 'required',
            'seller_price' => 'required|numeric',
            'origin_price' => 'required|numeric',
            'brand' => 'required',
            'color' => 'required',
            'quantity' => 'required',
            'image' => 'required|image|mimes: jpeg,png,jpg'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errorsMessage' => $validator->messages()
            ]);
        }else{
            $product = new Product();
            $product->category_id = $request->input('category_id');
            $product->name = $request->input('name');
            if($request->hasFile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move('uploads/product/', $filename);
                $product->image = 'uploads/product/'.$filename;
            }
            $product->slug = $request->input('slug');
            $product->description = $request->input('description');
            $product->brand = $request->input('brand');
            $product->seller_price = $request->input('seller_price');
            $product->origin_price = $request->input('origin_price');
            $product->color = $request->input('color');
            $product->quantity = $request->input('quantity');
            $product->status = $request->input('status') == true ? '1' : '0';

           
            $product->save();
            return response()->json([
                'status' => 200,
                'message' => 'Created product successfully'
            ]);
        }

    }

    public function edit( $id){
        $product = Product::find($id);
        if($product){
            return response()->json([
                'status' => 200,
                'product' => $product
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ]);
        }
    }
    public function update(Request $request,  $id){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|min:5',
            'description' => 'required',
            'seller_price' => 'required|numeric',
            'origin_price' => 'required|numeric',
            'brand' => 'required',
            'color' => 'required',
            'quantity' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errorsMessage' => $validator->messages()
            ]);
        }else{
            $product = Product::find($id);
            if($product){
                $product->category_id = $request->input('category_id');
                $product->name = $request->input('name');
                $product->slug = $request->input('slug');
                $product->description = $request->input('description');
                $product->brand = $request->input('brand');
                $product->seller_price = $request->input('seller_price');
                $product->origin_price = $request->input('origin_price');
                $product->color = $request->input('color');
                $product->quantity = $request->input('quantity');
                $product->status = $request->input('status');
                if($request->hasFile('image')){
                    $path = $product->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time().'.'.$extension;
                    $file->move('uploads/product/', $filename);
                    $product->image = 'uploads/product/'.$filename;
                }
               
                $product->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'Update product successfully'
                ]);
            }else{
                $product->save();
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found'
                ]);
            }
        }
    }

}
