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

        $Product = $this->ProductServices->AddProduct($request->Name, $request->Style, $request->Size, $request->Topping, $request->Image, $request->Price);

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
}
