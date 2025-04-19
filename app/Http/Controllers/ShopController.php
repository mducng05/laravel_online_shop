<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\ProductRating;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];

        // Lấy dữ liệu 'brand' từ request dưới dạng mảng
        $brands = $request->input('brand', []);

        // Chuyển đổi chuỗi thành mảng nếu cần
        if (is_string($brands)) {
            $brandsArray = explode(',', $brands);
        } elseif (is_array($brands)) {
            $brandsArray = $brands;
        }

        // Lấy danh sách danh mục và thương hiệu
        $categories = Category::orderBy('name', 'ASC')->with('sub_category')->where('status', 1)->get();
        $allBrands = Brand::orderBy('name', 'ASC')->where('status', 1)->get();

        // Khởi tạo truy vấn sản phẩm
        $products = Product::where('status', 1);

        // Áp dụng bộ lọc theo danh mục nếu có
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }
        }

        // Áp dụng bộ lọc theo danh mục con nếu có
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if ($subCategory) {
                $products = $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }

        // Áp dụng bộ lọc theo thương hiệu nếu có
        if (!empty($brandsArray)) {
            $products = $products->whereIn('brand_id', $brandsArray);
        }

        // Áp dụng bộ lọc theo giá nếu có
        $priceMin = intval($request->input('price_min', 0)); // Đặt giá trị mặc định là 0
        $priceMax = intval($request->input('price_max', 1000)); // Đặt giá trị mặc định là 1000
        if ($priceMax >= $priceMin) {
            $products = $products->whereBetween('price', [$priceMin, $priceMax]);
        }

        if(!empty($request->get('search'))){
            $products =$products->where('title','like','%'.$request->get('search').'%');
        }
        
        // Sắp xếp và lấy danh sách sản phẩm
        if ($request->get('sort')) {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'DESC'); // Sắp xếp theo mới nhất
            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'ASC'); // Sắp xếp theo giá tăng dần
            } else if ($request->get('sort') == 'price_desc') {
                $products = $products->orderBy('price', 'DESC'); // Sắp xếp theo giá giảm dần
            } else {
                $products = $products->orderBy('id', 'DESC'); // Mặc định
            }
        } else {
            $products = $products->orderBy('id', 'DESC'); // Nếu không có sort, mặc định sắp xếp theo ID
        }

        $products = $products->paginate(6);

        // Chuẩn bị dữ liệu để truyền vào view
        $data = [
            'categories' => $categories,
            'brands' => $allBrands,
            'products' => $products,
            'categorySelected' => $categorySelected,
            'subCategorySelected' => $subCategorySelected,
            'brandsArray' => $brandsArray,
            'priceMin' => intval($request->input('price_min', 0)), // Giá trị tối thiểu
            'priceMax' => intval($request->input('price_max', 1000)), // Giá trị tối đa
            'sort' => $request->get('sort'),

        ];

        return view('front.shop', $data);
    }
    public function product($slug)
    {
        $product = Product::where('slug', $slug)
            ->withCount('product_rating')
            ->withSum('product_rating','rating')
            ->with(['product_images','product_rating'])->first();

            // Kiểm tra sản phẩm có tồn tại không
            if ($product == null) {
            abort(404);
        }
        // dd($product);

        // fetch 
        $relatedProducts = [];
        // fetch related products
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->where('status',1)->get();
        }

        //Tính toán rating
        $avgRating ='0.00';
        $avgRatingPer =0;
        if($product->product_rating_count > 0){
            $avgRating = number_format(($product->product_rating_sum_rating/$product->product_rating_count),2);
            $avgRatingPer = ($avgRating*100)/5;
        }

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;
        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;
        return view('front.product', $data);
    }

    public function saveRating(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:2',
            'email' => 'required|email',
            'comment' => 'required',
            'rating' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        //Nếu đã comment rồi thì hiện thông báo
        $count = ProductRating::where('email', $request->email)->count();
        if($count >0){
            session()->flash('error','You already rated this product.');
            return response()->json([
                'status' => false,
            ]);
        }

        $productRating = new ProductRating();
        $productRating->product_id = $id;
        $productRating->username = $request->name;
        $productRating->email = $request->email;
        $productRating->comment = $request->comment;
        $productRating->rating = $request->rating;
        $productRating->status = 0;
        $productRating->save();

        $message = 'Thanks for your rating.';
        session()->flash('success',$message);
        return response()->json([
            'status' => true,
            'errors' => $message,
        ]);
    }
}
