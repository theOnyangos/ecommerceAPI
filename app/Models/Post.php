<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;
    protected $table = 'ptz_products';

    public static function getProducts()
    {
        $records = DB::table('ptz_products')->select('*')->get()->toArray();
        return $records;
    }
}
