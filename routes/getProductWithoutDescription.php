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
    $url = "https://rootsrwanda.rw/product-list/1/1/Mobiles---Tablets/Apple-";

    $category_name = 'Mobiles---Tablets';
    $file_name = 'rootsrwanda_products_Apple.csv';
    $crawler = Goutte::request('GET', $url);

		

 //    $category =   $crawler->filter('.sc-1a4vh2k-0 .content .sc-1a4vh2k-3 .menu .category span a')->each(function ($node) {
 //       return  $node->text();
 //    });

 //    $subCats =   $crawler->filter('.sc-1a4vh2k-0 .content .sc-1a4vh2k-3 .detail .subCats .column .list a')->each(function ($node) {
 //       return  $node->text();
 //    });

	// $brands =   $crawler->filter('.gfELas .gKInrG label span input')->each(function ($node) {
 //       return  $node->attr('value');
 //    }); 

 //    $paginate =   $crawler->filter('.jTPOKY ul li a')->each(function ($node) {
 //       return  $node->text();
 //    });  

 //    $catg =   $crawler->filter('.categoryLinkContainer button .linkText')->each(function ($node) {
 //       return  $node->text();
 //    }); 



    
    // $loop = collect($paginate)->max();
    $loop =1;
	    $products = [];

        // $product_purchase_p[] =   $crawler->filter('.productContainer a .jtiosv')->each(function ($node) {
        //    return  isset(explode(' ', $node->text())[1]) ? substr(explode(' ', $node->text())[1], 0) : '0' ;
        // });

    for($i = 1; $i <= (int)$loop ; $i++){
    	if($i == 1){
    		$crawler1 = Goutte::request('GET', $url);
    	}else{
    		$crawler1 = Goutte::request('GET', $url.'/?page='.$i);
    	}

    	$product_names =   $crawler->filter('.right-block .caption h4 a')->each(function ($node) {
       		return  $node->attr('title');
	    });
	    $product_images =   $crawler1->filter('.products-category img')->each(function ($node) {
	       return  $node->attr('src');
	    });

	    $product_currencies =   $crawler1->filter('.products-category .price-new')->each(function ($node) {
	       return  $node->text();
	    });

	    $product_prices =   $crawler1->filter('.products-category .price-new')->each(function ($node) {
	       return  $node->text();
	    });

        $product_purchase_p =   $crawler->filter('.products-category .price-old')->each(function ($node) {
           return  isset(explode(' ', $node->text())[1]) ? substr(explode(' ', $node->text())[1], 0) : '0' ;
        });

	    // $product_brands =   $crawler1->filter('.contenty .products-category .title-category')->each(function ($node) {
	    //    return  $node->text();
	    // });

		 $product_brands =   $crawler->filter('#content .products-category h3')->each(function ($node) {
	       return  $node->text();
	    });

	    foreach ($product_names as $key => $product_name) {
	    	$products[] = [
	    		'category_id' =>'',
	    		'brand_id' => '',
	    		'Category' => $category_name,
	    		'Brand'		=> isset($product_brands) ? implode('', $product_brands ): '',
	    		'Name' => isset($product_name ) ? $product_name : '',
	    		'Image Link' => isset($product_images[$key]) ? $product_images[$key] : '',
	    		'Currency' => isset($product_currencies[$key]) ? substr($product_currencies[$key], 0, 3) : '',
	    		'Unit Price' => isset($product_purchase_p[$key]) ? substr($product_purchase_p[$key],0,strlen(explode('.',$product_prices[$key])[0])) : '0',

                'Purchase Price' => isset($product_prices[$key]) ? substr($product_prices[$key], 3) : '0',


	    	];
	    	// Products::create($products);
	    }


    }
    // return $products;

    return Excel::download(new ProductExport($products), $file_name);

    echo "<pre>";
    // krsort($paginate);
   	print_r($products);
   
    echo "</pre>";
    // return view('welcome');
});
Route::get('/page', function() {
    $crawler = Goutte::request('GET', 'https://rootsrwanda.rw/product-list/1/1/Mobiles---Tablets/Apple-');
    dd($crawler);
   });