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
}
