<?php

namespace App\Services\Admins;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ToppingService
{
    public function ViewAll(int $Page)
    {
        $count = 0;
        if (isset($Page) || $Page == 1) {
            $data = DB::table('topping')
                ->select('id', 'name', 'price', 'image')
                ->orderBy('id')
                ->limit(20)
                ->get();
            return $data;
        }
        $count = $Page * 20;
        $data = DB::table('topping')
            ->select('id', 'name', 'price', 'image')
            ->orderBy('id')
            ->skip($count) // Bỏ qua 20 bản ghi đầu tiên
            ->take(20) // Lấy 20 bản ghi tiếp theo
            ->get();
        return $data;
    }

    public function DeleteTopping(int $id)
    {
        $data =
            DB::table('topping')
            ->where('id', $id)->first();
        if (isset($id) && isset($data)) {
            return 0;
        }
        DB::table('topping')
            ->where('id', $id)
            ->delete();
        return 1;
    }
}
