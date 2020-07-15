<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Category extends Model
{
    protected $primaryKey = "category_id";
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    public static function getArray($arr) {
        $return = array();
        foreach ($arr as $key => $value) {
            $value = new Collection($value);
            $value = $value->all();
            $return[$key] = $value;
        }
        return $return;
    }
}
