<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\Admins\SizeService;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**-------
     * 
     * Inject ProductService into controller
     * 
     ------*/
    protected $SizeService;

    function __construct(SizeService $SizeService)
    {
        $this->SizeService = $SizeService;
    }

    /**-------
     * 
     * function View all Size
     * 
     ------*/
    function View()
    {
        $data = $this->SizeService->ListSize();
        return response()->json(
            [
                'status' => 'success',
                'data' => $data
            ],
            200
        );
    }

    /**-------
     * 
     * function add size
     * 
     ------*/

    function Add(Request $request)
    {
        //check request value
        $Check = $this->SizeService->CheckAdd($request->Name);
        if ($Check !== 1) {
            return response()->json([
                'status' => 'error',
                'errors' => $Check
            ], 400);
        }

        //check database Size
        if ($this->SizeService->CheckSizeName($request->Name) == 0) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Size already exists'
            ], 400);
        }
        $this->SizeService->AddSize($request->Name);
        return response()->json([
            'status' => 'success',
        ], 200);
    }


    /**-------
     * 
     * function delete size
     * 
     ------*/

    function Delete(int $id)
    {
        //check request value
        $Check = $this->SizeService->CheckDelete($id);
        if ($Check !== 1) {
            return response()->json([
                'status' => 'error',
                'errors' => $Check
            ], 400);
        }

        //check database Size
        if ($this->SizeService->CheckSizeId($id) == 0) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Size does not exist yet'
            ], 400);
        }

        $this->SizeService->DeleteSize($id);
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    /**-------
     * 
     * function Update size
     * 
     ------*/
    function Update(Request $request, int $id)
    {
        //check request name value
        $Check = $this->SizeService->CheckAdd($request->Name);
        if ($Check !== 1) {
            return response()->json([
                'status' => 'error',
                'errors' => $Check
            ], 400);
        }
        //check request Id value
        $Check = $this->SizeService->CheckDelete($id);
        if ($Check !== 1) {
            return response()->json([
                'status' => 'error',
                'errors' => $Check
            ], 400);
        }

        //check database Size
        if ($this->SizeService->CheckSizeId($id) == 0) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Size does not exist yet'
            ], 400);
        }
        $this->SizeService->UpdateSize($request->Name);
        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
