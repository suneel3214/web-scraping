<?php

use Illuminate\Support\Facades\Route;
use App\Models\Products;
use App\Exports\ProductExport;
use Illuminate\Support\Facades\Storage;
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
    $url = "https://bazaar.shopclues.com/mobiles-smartphones.html?facet_brand[]=Poco&facet_brand[]=Redmi&facet_brand[]=Mi&fsrc=facet_brand";

    $category_name = 'Mobiles---Tablets';
    $product_brands ='Redmi ';
    $file_name = 'shoclus.csv';
    $crawler = Goutte::request('GET', $url);

		

    $categoryUrl =   $crawler->filter('.column a')->each(function ($node) {
       return  $node->attr('href');
    });
// dd($categoryUrl);
    $productsDetail = [];
    $loop1 = count($categoryUrl)-1;
    for($j = 1; $j <= (int)$loop1 ; $j++){
    	// if($j == 1){
    		// dd($categoryUrl[$j]);
    		// $crawler3 = Goutte::request('GET', $categoryUrl[$j]);
    	// }else{
    	// 	$crawler3 = Goutte::request('GET', $categoryUrl[$j].'/?page='.$j);
    	// }

    	// dd( $categoryUrl[$j]);
    	$crawler3 = Goutte::request('GET', $categoryUrl[$j]);

	       $titleProduct =   $crawler3->filter('.prd_mid_info h1')->each(function ($node) {
	       		return  $node->text();
		    });	
			// dd($titleProduct);

			$productImg =   $crawler3->filter('.visited_img img' )->each(function ($node) {
				return  $node->attr('src');
		    });
			// dd($productImg);
			$Discountprice =   $crawler3->filter('.o_price1')->each(function ($node) {
				return  $node->text();
		    });	
		    //  dd($Discountprice);

			$Firstprice =   $crawler3->filter('.f_price')->each(function ($node) {
				return  $node->text();
		    });	
		    //  dd($Firstprice);

			$Discount =   $crawler3->filter('.discount')->each(function ($node) {
				return  $node->text();
		    });	
		    //  dd($Discount);

		     $productAllDetail =   $crawler3->filter('.prd_mid_info span')->each(function ($node) {
	       		return  $node->text();
		    }); 
			// dd($productAllDetail); 
		     
	foreach ($titleProduct as $key => $productTitle) {
      

      $productsDetail[] = [
	    		'category_id' =>'',
	    		'brand_id' => '',
	    		'Category' => $category_name,
	    		'Brand'	=> $product_brands,
	    		'Name' => isset($productTitle) ? $productTitle : '',
	    		'Image Link' => isset($productImg[$key]) ? $productImg[$key] : '',
	    		'DiscountPrice'	=> isset($Discountprice[$key]) ? $Discountprice[$key] : '',
	    		'First price' => isset($Firstprice[$key]) ? $Firstprice[$key] : '',
	    		'Discount' => isset($Discount[$key]) ? $Discount[$key] : '',
	    		'Product Description' => isset($productAllDetail[1]) ? $productAllDetail[1] : '',

				

	    	];
		     	$imgurl = $productImg[$key];
				$contents = file_get_contents($imgurl);
				$name = substr($imgurl, strrpos($imgurl, '/') + 1);
				Storage::put($name, $contents);
			   

    }
	// dd($productsDetail);
	//   dd($imgurl);

   }
    return Excel::download(new ProductExport($productsDetail), $file_name);

 
});
Route::get('/page', function() {
    $crawler = Goutte::request('GET', 'https://rootsrwanda.rw/product-list/1/1/Mobiles---Tablets/Apple-');
    // dd($crawler);
   });