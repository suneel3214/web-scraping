<?php

use Illuminate\Support\Facades\Route;
use App\Models\Products;
use App\Exports\ProductExport;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function() {
    $url = "https://rootsrwanda.rw/product-list/1/3";

    $category_name = 'Mobiles---Tablets';
    $product_brands ='Nokia ';
    $file_name = 'rootsrwanda_products_Nokia.csv';
    $crawler = Goutte::request('GET', $url);

		

    $categoryUrl =   $crawler->filter('.right-block .caption h4 a')->each(function ($node) {
       return  $node->attr('href');
    });

    $productsDetail = [];
    $loop1 = count($categoryUrl)-1;
    for($j = 0; $j <= (int)$loop1 ; $j++){
    	// if($j == 1){
    		// dd($categoryUrl[$j]);
    		// $crawler3 = Goutte::request('GET', $categoryUrl[$j]);
    	// }else{
    	// 	$crawler3 = Goutte::request('GET', $categoryUrl[$j].'/?page='.$j);
    	// }

    	// dd( $categoryUrl[$j]);
    	$crawler3 = Goutte::request('GET', $categoryUrl[$j]);

	       $titleProduct =   $crawler3->filter('.content-product-right .title-product h1')->each(function ($node) {
	       		return  $node->text();
		    });	

		     $productPriceNew =   $crawler3->filter('.product_page_price .price-new')->each(function ($node) {
	       		return  $node->text();
		    }); 
		    $productPriceOld =   $crawler3->filter('.product_page_price .price-old')->each(function ($node) {
	       		return  $node->text();
		    }); 
		     $productFeatures =   $crawler3->filter('.content-why .why-list li a' )->each(function ($node) {
	       		return  $node->text();
		    }); 
		    $productDescription =   $crawler3->filter('.short_description p' )->each(function ($node) {
	       		return  $node->text();
		    }); 
		     $productImg =   $crawler3->filter('.large-image img' )->each(function ($node) {
	       		return  $node->attr('src');
		    });
	foreach ($titleProduct as $key => $productTitle) {
      

      $productsDetail[] = [
	    		'category_id' =>'',
	    		'brand_id' => '',
	    		'Category' => $category_name,
	    		'Brand'		=> $product_brands,
	    		'Name' => isset($productTitle) ? $productTitle : '',
	    		'Image Link' => isset($productImg[$key]) ? $productImg[$key] : '',
	    		'Currency' => isset($productPriceNew[$key]) ? substr($productPriceNew[$key], 0, 3) : '',
	    		'Unit Price' => isset($productPriceOld[$key]) ? substr($productPriceOld[$key],3,strlen($productPriceOld[$key]) ): '0',
                'Purchase Price' => isset($productPriceNew[$key]) ? substr($productPriceNew[$key],3,strlen($productPriceNew[$key])) : '0',
	    		'Product Description' => isset($productDescription[$key]) ? $productDescription[$key] : '',



	    	];

    }

   }
    return Excel::download(new ProductExport($productsDetail), $file_name);

 
});
Route::get('/page', function() {
    $crawler = Goutte::request('GET', 'https://rootsrwanda.rw/product-list/1/1/Mobiles---Tablets/Apple-');
    dd($crawler);
   });