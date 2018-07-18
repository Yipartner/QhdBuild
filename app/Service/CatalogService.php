<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class CatalogService
{
    public function addCatalog($catalog_data)
    {
        DB::table('catalogs')->insert($catalog_data);
    }

    public function selectCatalogByLv($lv)
    {
        if ($lv > 2 || $lv < 1) {
            return response()->json([
                'code' => 4003,
                'message' => "不存在该等级的目录"
            ]);
        } else {
            return DB::table('catalogs')->where('catalog_lv', $lv)->get();
        }
    }

    public function selectCatalogByLastId($lastCatalogId)
    {
        if ($this->isSetCatalog($lastCatalogId)) {
            return DB::table('catalogs')->where('last_catalog_id', $lastCatalogId)->get();
        } else {
            return response()->json([
                'code' => 4002,
                'message' => '目录不存在'
            ]);
        }
    }

    public function deleteCatalog($catalog_id)
    {
        DB::table('catalogs')->where('catalog_id', $catalog_id)->delete();
        DB::table('articles')->where('article_catalog',$catalog_id)
            ->orWhere('article_first_catalog',$catalog_id)
            ->delete();
    }

    public function updateCatalog($catalog_data)
    {
        $catalog_id = $catalog_data['catalog_id'];
        if ($this->isSetCatalog($catalog_id)) {
            DB::table('catalogs')->where('catalog_id', $catalog_id)->update($catalog_data);
        } else {
            return response()->json([
                'code' => 4002,
                'message' => '目录不存在'
            ]);
        }
    }

    public function isSetCatalog($catalog_id)
    {
        $data = DB::table('catalogs')->where('catalog_id', $catalog_id)->first();
        if (isset($data->catalog_id)) {
            return true;
        } else {
            return false;
        }
    }
}