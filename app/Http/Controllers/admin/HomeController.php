<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // This month Revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $revenueThisMonth = Order::where('status' , '!=' , 'cancelled')
                        ->whereDate('created_at' , '>=' , $startOfMonth)
                        ->whereDate('created_at' , '<=' , $currentDate)
                        ->sum('grand_total');

        // Last Month Revenue
        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $revenueLastMonth = Order::where('status' , '!=' , 'cancelled')
                        ->whereDate('created_at' , '>=' , $lastMonthStartDate)
                        ->whereDate('created_at' , '<=' , $lastMonthEndDate)
                        ->sum('grand_total');


        // Last 30 days sale
        $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $revenueLastThirtyDays = Order::where('status' , '!=' , 'cancelled')
                        ->whereDate('created_at' , '>=' , $lastMonthStartDate)
                        ->whereDate('created_at' , '<=' , $currentDate)
                        ->sum('grand_total');


                        
        $totalOrders = Order::where('status' , '!=' , 'cancelled')->count();
        $totalRevenue = Order::where('status' , '!=' , 'cancelled')->sum('grand_total');
        $totalCustomers = User::where('role' , 1)->count();
        $totalProduct = Product::count();

        //Delete temp images
        $dayBeforeToday = Carbon::now()->subDay(1)->format('Y-m-d H:i:s');
        // dd($dayBeforeToday);
        $tempImages = TempImage::where('created_at','<=',$dayBeforeToday)->get();
        foreach($tempImages as $tempImage){
             // Kiểm tra giá trị $tempImage->name
            if ($tempImage->name) {
                // Đường dẫn file gốc
                $path = public_path('/temp/' . $tempImage->name);
                // Đường dẫn file thumb
                $thumbPath = public_path('/temp/thumb/' . $tempImage->name);
                
                //Delete Main Image
                if(File::exists($path)){
                    File::delete($path);
                }
                //Delete Thumb Image
                if(File::exists($thumbPath)){
                    File::delete($thumbPath);
                }
                TempImage::where('id',$tempImage->id)->delete();
                
            } else {
                echo "No image name found.<br>";
            }
        }
        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
            'totalProduct' => $totalProduct,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueLastThirtyDays' => $revenueLastThirtyDays,
            'lastMonthName' => $lastMonthName,
        ]);
        // $admin = Auth::guard('admin')->user();

        // // Kiểm tra xem admin đã đăng nhập chưa
        // if ($admin) {
        //     echo 'Welcome, ' . $admin->name . ' <a href="' . route('admin.logout') . '">Logout</a>';
        // } else {
        //     return redirect()->route('admin.login');
        // }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}