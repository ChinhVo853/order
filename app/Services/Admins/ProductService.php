<?php

namespace App\Services\Admins;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    /**-----------
     * 
     * function return data View 
     * string: Name, int: Stype, array: Size, array: Topping, string: Image
     * 
     -------------------*/

    public function AddProduct(string $Name, int $Style, array $Size, array $Topping, string $Image, array $Price)
    {
        // Validate Size and Topping
        $Check = $this->CheckSizeAndTopping($Size, $Topping);
        if ($Check == 0) {
            return 0; // Return early if validation fails
        }

        // Insert product and get the ID
        $Product = DB::table('product')->insertGetId([
            'name' => $Name,
            'product_type_id' => $Style,
            'image' => $Image
        ]);

        // Loop through the Size array to insert into product_detail table
        for ($KeySize = 0; $KeySize < count($Size); $KeySize++) {
            DB::table('product_detail')->insert([
                'product_id' => $Product,
                'price' => $Price[$KeySize],
                'statu' => 1,
                'size_id' => $Size[$KeySize]
            ]);
        }

        // If Topping array is not empty, insert into product_topping table
        if (count($Topping) > 0) {
            for ($KeyTopping = 0; $KeyTopping < count($Topping); $KeyTopping++) {
                DB::table('product_topping')->insert([
                    'product_id' => $Product,
                    'topping_id' => $Topping[$KeyTopping]
                ]);
            }
        }

        return 1;
    }


    /**-----------
     * 
     * function delete product
     * 
     -------------------*/
    public function DeleteProduct(int $id)
    {
        DB::table('product')->where('id', '=', $id)->delete();
    }


    /**-----------
     * 
     * function Delete check Validator
     * id : int 
     * 
     -------------------*/
    public function CheckDelete(int $id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer'],
            [
                'id.required' => 'Product is currently empty',
                'id.integer' => 'must be a number'
            ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        }
        return 1;
    }

    /**-----------
     * 
     * function Add check Validator
     * string: Name, int: Stype, array: Size, array: Topping, string: Image
     * 
     -------------------*/

    public function CheckArray(array $Arr)
    {
        foreach ($Arr as $item) {
            $validator = Validator::make(
                ['id' => $item],
                ['id' => 'required'],
                [
                    'id.required' => 'It is required.',
                ]
            );
            if ($validator->fails()) {
                return $validator->errors();
            }
        }
        return 1;
    }


    /**-----------
     * 
     * function Add check Validator
     * string: Name, int: Stype, array: Size, array: Topping, string: Image
     * 
     -------------------*/
    public function CheckAdd(string $Name, int $Style, array $Size, array $Topping, array $Price)
    {
        $validator = Validator::make(
            [
                'Name' => $Name,
                'Style' => $Style,
                'Size' => $Size,
                'Topping' => $Topping,
                'Price' => $Price
            ],
            [
                'Name' => 'required|string|max:255',
                'Style' => 'required|integer',
                'Size' => 'required|array',
                'Topping' => 'required|array',
                'Price' => 'required|numeric'
            ],
            [
                'Name.required' => 'The name field is required.',
                'Name.max' => 'The name may not be greater than 255 characters.',
                'Style.required' => 'Style is required.',
                'Style.integer' => 'Style must be an integer.',
                'Size.required' => 'At least one size is required.',
                'Size.array' => 'Size must be an array.',
                'Topping.required' => 'At least one topping is required.',
                'Topping.array' => 'Topping must be an array.',
                'Price.required' => 'The price is required.',
                'Price.numeric' => 'The price must be a valid number.' // Custom error message for Price

            ]
        );

        // Validate the items inside the Size array
        $CheckSize = $this->CheckArray($Size);
        if ($CheckSize !== 1) {
            return $CheckSize;
        }

        // Validate the items inside the Topping array
        $CheckTopping = $this->CheckArray($Topping);
        if ($CheckTopping !== 1) {
            return $CheckTopping;
        }

        $CheckPrice = $this->CheckArray($Price);
        if ($CheckPrice !== 1) {
            return $CheckPrice;
        }
        if ($validator->fails()) {
            return $validator->errors();
        }


        return 1;
    }


    /**-------
     * 
     * function check id product
     * 
     *   * Name : String 
     * 
     ------*/
    function CheckProductId(string $id)
    {
        $Check = DB::table('product')->select('id')->where('id', '=', $id)->first();
        if (isset($Check)) {
            return 0;
        }
        return 1;
    }

    /**-----------
     * 
     * function check name product
     * 
     * 
     -------------------*/
    public function CheckName(string $Name)
    {
        $Check = DB::table('product')->select('id')->where('name', '=', $Name)->first();
        if (!isset($Check)) {
            return 0;
        }
        return 1;
    }

    /**-----------
     * 
     * function Add check Validator
     * 
     -------------------*/
    public function CheckSizeAndTopping(array $Size, array $Topping)
    {
        foreach ($Size as $item) {
            $Check = DB::table('size')->select('id')->where('id', '=', $item)->first();
            if (isset($Check)) {
                return 0;
            }
        }
        foreach ($Topping as $item) {
            $Check = DB::table('size')->select('id')->where('id', '=', $item)->first();
            if (isset($Check)) {
                return 0;
            }
        }
        return 1;
    }
}
