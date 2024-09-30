<?php


namespace App\Services\Admins;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SizeService
{
    /**-----------
     * 
     * function return data View 
     * 
     -------------------*/
    function ListSize()
    {
        $data = DB::table('size')
            ->select('id', 'name')
            ->get();
        return $data;
    }
    /**-------
     * 
     * function add size
     * 
     *  * Name : String 
     * 
     ------*/
    function AddSize(string $Name)
    {
        DB::table('size')->insert(['name' => $Name]);
    }

    /**-------
     * 
     * function edit size
     * 
     *  * Name : String 
     * 
     ------*/
    function UpdateSize(string $Name)
    {
        DB::table('size')->where('name', '=', $Name)->update(
            [
                'name' => $Name
            ]
        );
    }


    /**-------
     * 
     * function delete size
     * 
     *  * id : Int 
     * 
     ------*/
    function DeleteSize(string $id)
    {
        DB::table('size')->where('id', $id)->delete();
    }


    /**-------
     * 
     * function Add check Validator
     *  * Name : String 
     * 
     ------*/

    function CheckAdd(string $Name)
    {
        $validator = Validator::make(
            ['name' => $Name],
            ['name' => 'required|max:30'],
            [
                'name.required' => 'Size is currently empty',
                'name.max' => 'Maximum size 30 characters'
            ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        }
        return 1;
    }

    /**-------
     * 
     * function Delete check Validator
     *  * id : int 
     * 
     ------*/

    function CheckDelete(int $id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer'],
            [
                'id.required' => 'Size is currently empty',
                'id.integer' => 'must be a number'
            ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        }
        return 1;
    }

    /**-------
     * 
     * function check name size
     * 
     *   * Name : String 
     * 
     ------*/
    function CheckSizeName(string $Name)
    {
        $Check = DB::table('size')->select('id')->where('Name', '=', $Name)->first();
        if (isset($Check)) {
            return 0;
        }
        return 1;
    }

    /**-------
     * 
     * function check id size
     * 
     *   * Name : String 
     * 
     ------*/
    function CheckSizeId(string $id)
    {
        $Check = DB::table('size')->select('id')->where('id', '=', $id)->first();
        if (isset($Check)) {
            return 0;
        }
        return 1;
    }
}
