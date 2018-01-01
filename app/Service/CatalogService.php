<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class CatalogService
{
    public function addCatalog($catalog_data){
        DB::table('catalogs')->insert($catalog_data);
    }
    public function selectCatalogByLv($lv){
        return DB::table('catalogs')->where('catalog_lv',$lv)->get();
    }
    public function deleteCatalog($catalog_id){
        DB::table('catalogs')->where('catalog_id',$catalog_id)->delete();
    }
}