<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//文章：查、查列表、添加、更新、删除
Route::get('/article/{article_id}','ArticleController@showArticle');
Route::get('article/list/{catalog_id}','ArticleController@getArticleList');
Route::post('/article/add','ArticleController@addArticle');
Route::post('/article/update','ArticleController@editArticle');
Route::get('/article/delete','ArticleController@deleteArticle');


Route::post('/upload','UploadController@uppic');
