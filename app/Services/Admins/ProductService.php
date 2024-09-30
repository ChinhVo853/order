<?php

namespace App\Services\Admins;

use Illuminate\Support\Facades\DB;

class ProductService
{
    /**-----------
     * 
     * function return data View 
     * 
     -------------------*/
    public function ViewProduct()
    {
        $data = DB::table('product as p')
            ->join('product_type as pt', 'pt.id', 'p.product_type_id')
            ->select('p.name as name_product', 'pt.name as name_product_type', 'image')
            ->get();
        return $data;
    }
}
