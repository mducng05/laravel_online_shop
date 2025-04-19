<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        // Xác thực các trường đầu vào
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Nếu xác thực thành công
        if ($validator->passes()) {
            if (Auth::guard('admin')->attempt(
                [
                    'email' => $request->email,
                    'password' => $request->password
                ],
                $request->get('remember')
            )) {

                $admin = Auth::guard('admin')->user();

                if ($admin->role == 2) {
                    return redirect()->route('admin.dashboard');
                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'you are not authorized to access admin panel');
                }
            } else {
                return redirect()->route('admin.login')
                    ->with('error', 'Email hoặc Mật khẩu không đúng');
            }
        } else {
            // Nếu xác thực không thành công
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
}