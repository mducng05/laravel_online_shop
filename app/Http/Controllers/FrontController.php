<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator; // phân trang

class FrontController extends Controller
{
    // public function index()
    // {
    //     // dd('Controller is working');
    //     $products = Product::where('is_featured', 'Yes')
    //         ->orderBy('id', 'ASC')
    //         ->take(12)
    //         ->where('status', 1)
    //         ->get();
    //     $data['featureProducts'] = $products;

    //     $lastProducts = Product::orderBy('id', 'DESC')
    //         ->where('status', 1)
    //         ->take(12)
    //         ->get();
    //     $data['lastProducts'] = $lastProducts;

    //     return view('front.home', $data);
    // }

    public function index()
    {
        // Lấy các sản phẩm nổi bật và sản phẩm mới nhất
        $featureProducts = Product::where('is_featured', 'Yes')->get();
        $lastProducts = Product::latest()->take(10)->get();

        // Gộp hai danh sách sản phẩm và phân trang
        $allProducts = $featureProducts->merge($lastProducts);

        // Phân trang thủ công
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentItems = $allProducts->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator($currentItems, $allProducts->count(), $perPage, $currentPage);

        // Trả về view và truyền biến paginatedItems
        return view('front.home', compact('paginatedItems'));
    }

    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
            session(['url.intended' => url()->previous()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You need to log in to add items to your wishlist.'
                ]);
            }
            return redirect()->route('account.auth');
        }

        $product = Product::find($request->id);

        if (!$product) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }

        // Kiểm tra xem sản phẩm đã có trong wishlist chưa
        $wishlistItem = wishlist::where('user_id', Auth::user()->id)
            ->where('product_id', $request->id)
            ->first();

        if ($wishlistItem) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-info"><strong>"' . $product->title . '"</strong> is already in your wishlist.</div>'
            ]);
        }

        // Thêm sản phẩm vào wishlist
        wishlist::updateOrCreate([
            'user_id' => Auth::user()->id,
            'product_id' => $request->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"' . $product->title . '"</strong> added to your wishlist</div>'
        ]);
    }


    // $wishlist = new wishlist;   
    // $wishlist->user_id = Auth::user()->id;
    // $wishlist->product_id = $request->id;
    // $wishlist->save(); 

    public function page($slug)
    {
        $page = Page::where('slug' , $slug)->first();
        if($page == null)
        {
            abort(404);
        }

        return view('front.page' ,[
            'page' => $page,
        ]);
    }

    public function sendContactEmail(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:10',
            'mail_subject' => 'You have received a contact email!'
        ],[
            'subject.min' => 'The subject must be at least 10 characters.',
        ]);

        if($validator->passes())
        {
            //send email here
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'You have received a contact email'
            ];
            $admin = User::where('id' , 3)->first();
            if (!$admin) {
                return response()->json([
                    'status' => false,
                    'errors' => ['admin' => 'Admin email not found.'],
                ]);
            }

            Mail::to($admin->email)->send(new ContactEmail($mailData));
            session()->flash('success' , 'Thanks for contacting us , we will get back to you soon.');
            return response()->json([
                'status' => true,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
