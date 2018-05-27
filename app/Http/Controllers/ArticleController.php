<?php

namespace App\Http\Controllers;

use App\Service\ArticleService;
use App\Service\CatalogService;
use App\Tool\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    private $articleService;
    private $catalogService;

    public function __construct(ArticleService $articleService, CatalogService $catalogService)
    {
        $this->articleService = $articleService;
        $this->catalogService = $catalogService;
    }

    public function addArticle(Request $request)
    {
        $rule = [
            'article_title' => 'required',
            'article_real_content' => 'required',
            'article_rendered_content' => 'required',
            'article_catalog' => 'required',
            'article_first_catalog' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return response()->json([
                'code' => 1001,
                'message' => '表单验证出错'
            ]);
        } else {
            $article_data = ValidationHelper::getInputData($request, $rule);
            $this->articleService->addArticle($article_data);
            return response()->json([
                'code' => 1000,
                'message' => '文章添加成功'
            ]);
        }
    }

    public function showArticle($article_id)
    {
        $article_data = $this->articleService->selectArticle($article_id);
        if (isset($article_data->article_id)) {
            return response()->json([
                'code' => 1000,
                'data' => $article_data
            ]);
        } else
            return response()->json([
                'code' => 2002,
                'message' => '文章不存在'
            ]);
    }

    public function getArticleList($catalog_id)
    {
        $article_list = $this->articleService->selectArticleList($catalog_id);
        if (isset($article_list[0]))
            return response()->json([
                'code' => 1000,
                'list' => $article_list
            ]);
        else
            return response()->json([
                'code' => 3002,
                'message' => '无文章'
            ]);
    }

    public function getAllArticleList()
    {
        $Lv1Catalog =$this->catalogService->selectCatalogByLv(1);
        $articleList = $this->articleService->selectAllArticleList();
        $articleTrueList=[];
        $num =0;
        foreach ($Lv1Catalog as $key => $value){
            $thisCatalog=[];
            $thisCatalog['catalog_id']=$value->catalog_id;
            $thisCatalog['catalog_name']=$value->catalog_name;
            if (isset($articleList[$value->catalog_id]))
                $thisCatalog['article_list']=$articleList[$value->catalog_id];
            else
                $thisCatalog['article_list']=[];
            $articleTrueList[$num]=$thisCatalog;
            $num++;
        }
        return response()->json([
            'code' => 1000,
            'articleList' => $articleTrueList
        ]);
    }

    public function editArticle(Request $request)
    {
        $rule = [
            'article_id' => 'required',
            'article_title' => 'required',
            'article_real_content' => 'required',
            'article_rendered_content' => 'required',
            'article_catalog' => 'required',
            'article_first_catalog' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return response()->json([
                'code' => 1001,
                'message' => '表单验证出错'
            ]);
        } else {
            $article_data = ValidationHelper::getInputData($request, $rule);
            $this->articleService->updateArticle($article_data);
            return response()->json([
                'code' => 1000,
                'message' => '编辑成功'
            ]);
        }
    }

    public function deleteArticle($article_id)
    {
        $article_data = $this->articleService->selectArticle($article_id);
        if (isset($article_data->article_id)) {
            $this->articleService->deleteArticle($article_id);
            return response()->json([
                'code' => 1000,
                'message' => '删除成功'
            ]);
        } else
            return response()->json([
                'code' => 2002,
                'message' => '文章不存在'
            ]);


    }
    public function searchArticle(string $title){
        $articleList=DB::table('articles')->where([
            ['article_title','like','%'.$title.'%']
        ])->select('article_id','article_title')->paginate(10);
        return response()->json([
            'code'=>1000,
            'data'=>$articleList
        ]);
    }
}
