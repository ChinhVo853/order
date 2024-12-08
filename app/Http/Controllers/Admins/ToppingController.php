<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\Admins\ToppingService;
use Illuminate\Http\Request;

class ToppingController extends Controller
{
    /**-------
     * 
     * Inject ProductService into controller
     * 
     ------*/
    protected $toppingService;
    public function __construct(ToppingService $toppingService)
    {
        $this->toppingService = $toppingService;
    }
    //
    /**-----------
     * 
     * function return data View 
     * int: id
     * 
     -------------------*/

    public function View(int $Page)
    {
        $data = $this->toppingService->ViewAll($Page);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    /**-----------
     * 
     * function delete data  
     * string: Name, int: Stype, array: Size, array: Topping, string: Image
     * 
     -------------------*/


    public function Delete(int $id)
    {
        $res = $this->toppingService->DeleteTopping($id);
        if ($res == 0) {
            return response()->json([
                'status' => 'error',
            ], 400);
        }
        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
