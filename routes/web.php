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
//目录
Route::get('/', function () {
    return view('welcome');
});
Route::get('/catalog/show','CatalogController@showCatalog');
Route::post('/catalog/add','CatalogController@addCatalog');
Route::get('/catalog/delete/{catalog_id}','CatalogController@deleteCatalog');
Route::post('/catalog/update','CatalogController@editCatalog');
//文章：查、查列表、添加、更新、删除
Route::get('/article/{article_id}','ArticleController@showArticle');
Route::get('article/list/all','ArticleController@getAllArticleList');
Route::get('article/list/{catalog_id}','ArticleController@getArticleList');
//Route::post('/article/add','ArticleController@addArticle')->middleware('permission');
Route::post('/article/add','ArticleController@addArticle');
Route::post('/article/update','ArticleController@editArticle');
Route::get('/article/delete/{article_id}','ArticleController@deleteArticle');
//图片
Route::post('/picture/add','PictureController@addPicture');
Route::get('/picture/delete/{picture_id}','PictureController@deletePicture');
Route::get('/picture/show','PictureController@showPicture');

Route::post('/token/create','PasswordController@createToken');
Route::post('/upload','UploadController@upPic');
