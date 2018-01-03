<?php

namespace App\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ArticleService
{

    public function addArticle($article_data)
    {
        $time = new Carbon();
        $article_data['created_at'] = $time;
        DB::table('articles')->insert($article_data);
    }

    public function updateArticle($article_data)
    {
        $article_id = $article_data['article_id'];
        $data = $this->selectArticle($article_id);
        if (isset($data->article_id)) {
            $time = new Carbon();
            $article_data['updated_at'] = $time;
            DB::table('articles')->where('article_id', $article_id)->update($article_data);
        } else {
            return response()->json([
                'code' => 2002,
                'message' => '文章不存在'
            ]);
        }
    }

    public function selectArticle($article_id)
    {
        $article_data = DB::table('articles')->where('article_id', $article_id)->first();
        return $article_data;
    }

    public function selectArticleList($catalog_id)
    {
        $article_list = DB::table('articles')->where('article_catalog', $catalog_id)->orderBy('created_at', 'desc')->select('article_id', 'article_title', 'article_catalog', 'created_at')->paginate(10);
        foreach ($article_list as $key => $value) {
            unset($value->article_real_content);
            unset($value->article_rendered_content);
        }
        return $article_list;
    }

    public function selectAllArticleList()
    {
        $allArticles = DB::table('articles')->select('article_id', 'article_title', 'article_catalog', 'created_at', 'article_first_catalog')->orderby('created_at', 'desc')->get();
        $allArticleList = [];
        foreach ($allArticles as $key => $value) {
            $thisArticle = [];
            $thisArticle['article_id'] = $value->article_id;
            $thisArticle['article_title'] = $value->article_title;
            $thisArticle['created_at'] = $value->created_at;
            //$thisArticle['article_first_catalog'] = $value->article_first_catalog;
            if (isset($allArticleList[$value->article_first_catalog]))
                $allArticleList[$value->article_first_catalog] = array_merge($allArticleList[$value->article_first_catalog], [$thisArticle]);
            else
                $allArticleList[$value->article_first_catalog] = [$thisArticle];
        }
        return $allArticleList;
    }

    public function deleteArticle($article_id)
    {
        DB::table('articles')->where('article_id', $article_id)->delete();
    }

}