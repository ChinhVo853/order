<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\Admins\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    /**-------
     * 
     * Inject ProductService into controller
     * 
     ------*/
    protected $ProductServices;
    public function __construct(ProductService $ProductServices)
    {
        $this->ProductServices = $ProductServices;
    }
    /**-------
     * 
     * function View all product
     * 
     ------*/

    function View()
    {
        $data = $this->ProductServices->ViewProduct();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    /**-------
     * 
     * function delete a product
     * 
     ------*/

    function Delete(int $id)
    {
        //check request value
        $Check = $this->ProductServices->CheckDelete($id);
        if ($Check != 1) {
            return response()->json([
                'status' => 'error',
                'errors' => $Check
            ], 400);
        }
        //check database Size
        if ($this->ProductServices->CheckProductId($id) == 0) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Product does not exist yet'
            ], 400);
        }

        $this->ProductServices->DeleteProduct($id);
        return response()->json([
            'status' => 'success',
        ], 200);
    }


    /**-------
     * 
     * function add a product
     * 
     ------*/
    public function Add(Request $request)
    {
        //check request value
        $Check = $this->ProductServices->CheckAdd($request->Name, $request->Style, $request->Size, $request->Topping, $request->Price);

        if ($Check != 1) {
            return response()->json([
                'status' => 'error',
                'errors' => $Check
            ], 400);
        }

        if ($this->ProductServices->CheckName($request->Name) == 0) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Product already exists.'
            ], 400);
        }
        $imageName = 'default.jpg';
        if ($request->hasFile('image')) {

            // Lưu hình ảnh vào thư mục public/avatar
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('AnhMonAN'), $imageName);
            // Trả về phản hồi thành công
        }

        $Product = $this->ProductServices->AddProduct($request->Name, $request->Style, $request->Size, $request->Topping, $imageName, $request->Price);

        if ($Product == 0) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Add product failed'
            ], 400);
        }
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    /**-------
     * 
     * function add topping for a product
     * 
     ------*/

    public function AddTopping(Request $request)
    {
        if (count($request->Topping) == 0) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Please choose toppings'
            ], 400);
        }

        $CheckAndAdd = $this->ProductServices->AddToppingProduct($request->Topping, $request->IdProduct);
        if ($CheckAndAdd != 1) {
            return response()->json([
                'status' => 'error',
                'errors' => $CheckAndAdd
            ], 400);
        }
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    /**-------
     * 
     * function find a product
     * 
     ------*/
    public function Find(Request $request)
    {
        if (!isset($request->Name)) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Please enter the character you want to find.'
            ], 400);
        }
        $data = $this->ProductServices->FindProduct($request->Name);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
