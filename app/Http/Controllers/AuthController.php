<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\OrderItem;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function index(){
        if (Auth::check()) {
            return redirect()->route('account.profile');
        }
        return view('front.account.auth');

    }

    public function processRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);

        if ($validator->passes()) {

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'You have been registerd successfully.');

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|',
        ]);

        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {

                session()->flash('error' , 'Either email/password is incorrect. ');

                return redirect()->route('account.auth')
                    ->withInput($request->only('email'))
                    ->with('error', 'Either email/password is incorrect. ');
            }
        } else {
            return redirect()->route('account.auth')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }


    public function profile()
    {
        $userId = Auth::user()->id;
        $countries = Country::orderBy('name','ASC')->get();
        $user = User::where('id', Auth::user()->id)->first();
        $address = CustomerAddress::where('user_id',$userId)->first();
        return view('front.account.profile', [
            'user' => $user,
            'countries' => $countries,
            'address' => $address,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId . ',id',
            'phone' => 'required',
        ]);

        if ($validator->passes()) {
            // Cập nhật thông tin người dùng
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            $message = 'Profile updated successfully!';
            session()->flash('success',$message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'moblie' => 'required|string',
        ]);

        if ($validator->passes()) {
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'moblie' => $request->moblie,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ]
            );

            $message = 'Address updated successfully!';
            session()->flash('success',$message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function logout()
    {   
        // Xóa giỏ hàng
        // Cart::destroy();
        Auth::logout();
        return redirect()->route('account.auth');
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBY('created_at', 'DESC')->get();

        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }

    public function orderDetail($id)
    {
        $data = [];
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] = $order;

        $orderItems = OrderItem::where('order_id', $id)->get();
        $data['orderItems'] = $orderItems;

        $orderItemsCount = OrderItem::where('order_id', $id)->count();
        $data['orderItemsCount'] = $orderItemsCount;

        return view('front.account.order-detail', $data);
    }

    public function wishlist()
    {
        $wishlists = wishlist::where('user_id', Auth::user()->id)->get();
        $data = [];
        $data['wishlists'] = $wishlists;
        return view('front.account.wishlist', $data);
    }

    public function removeProductFromWishList(Request $request)
    {
        // Tìm sản phẩm trong wishlist của người dùng
        $wishlist = Wishlist::where('user_id', Auth::user()->id)
            ->where('product_id', $request->id)
            ->first();

        if ($wishlist == null) {
            // Nếu sản phẩm không có trong wishlist
            session()->flash('error', 'Product already removed!');
            return response()->json([
                'status' => true,
                'message' => 'Product was not in the wishlist',
            ]);
        } else {
            // Xóa sản phẩm khỏi wishlist
            Wishlist::where('user_id', Auth::user()->id)
                ->where('product_id', $request->id)
                ->delete();
            session()->flash('success', 'Product removed successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product removed successfully!',
            ]);
        }
    }

    public function showChangPasswordForm()
    {
        return view('front.account.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->passes()) {
            
            $user = User::select('id' ,'password')->where('id' , Auth::user()->id)->first();
            //dd($user)
            if (!Hash::check($request->old_password ,$user->password )) {

                session()->flash('error' , 'Your old password is incorrect! , please try again.');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id' , $user->id)->update([
                'password' => Hash::make($request->new_password),

            ]);

            session()->flash('success' , 'You have successfully changed your password!');
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

    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'email' => 'required|email|exists:users,email',  
        ]);

        if ($validator->fails()) {
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);
        DB::table('password_reset_tokens')->where('email' , $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send Email Here
        $user = User::where('email' ,$request->email)->first();
        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have requested to reset your password'
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        return redirect()->route('front.forgotPassword')->with('success' , 'Success!!! Please check your inbox to reset your password!');
    }

    public function resetPassword($token)
    {

        $tokenExist = DB::table('password_reset_tokens')->where('token' , $token)->first();
        if ($tokenExist == null) {
            return redirect()->route('front.forgotPassword')->with('error' , 'Invalid request');
        }

        return view('front.account.reset-password', [
            'token' => $token,
        ]);
    }

    public function processResetPassword(Request $request)
    {
        $token = $request->token;

        $tokenObj = DB::table('password_reset_tokens')->where('token' , $token)->first();
        if ($tokenObj == null) {
            return redirect()->route('front.forgot-password')->with('error' , 'Invalid request');
        }

        $user = User::where('email' , $tokenObj->email)->first();

        $validator = Validator::make($request->all() , [
            'new_password' => 'required|min:5',
            'cofirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('front.resetPassword',$token)->withErrors($validator);
        }

        User::where('id' , $user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        DB::table('password_reset_tokens')->where('email' , $user->email)->delete();
        
        return redirect()->route('account.auth')->with('success' ,'You have successfully update your password!');
    }
}