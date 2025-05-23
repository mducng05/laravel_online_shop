<?php

namespace App\Http\Controllers;

use App\Models\DiscountCoupon;
use App\Models\Country;
use App\Models\Product;
use App\Models\AuthController;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\CartControlle;
use App\Models\shippingCharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Redis;
// use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
         // Kiểm tra xem người dùng đã đăng nhập chưa
        if (Auth::check() == false) {
            return response()->json([
                'status' => false,
                'message' => 'Please login to add products to your cart.'
            ]);
        }
        $product = Product::with('product_images')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ]);
        }

        if (Cart::count() > 0) {
            // $message = 'Product already in cart';
            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }
            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, [
                    'productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''
                ]);
                $status = true;
                $message = '<strong>' . $product->title . '</strong> added in your cart successfully.';
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title .    ' already added in cart.';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, [
                'productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''
            ]);
            $status = true;
            $message = '<strong>' . $product->title . '</strong> added in your cart successfully.';

            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {
        $cartContent = Cart::content();
        // dd($cartContent);
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        //Kiểm tra số lượng sản phẩm trong kho
        if ($product->track_qty == 'Yes') {
            if ($qty <=  $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Request qty(' . $qty . ') not available in stock.';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully';
            $status = true;
            session()->flash('success', $message);
        }


        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);
        if ($itemInfo == null) {
            $errorMessage = 'Item not found in cart';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item remove from cart successfully.';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout()
    {
        $discount = 0;

        //--- if cart is empty redirect to cart page
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        //--if user is not logged in then redirect to login page
        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.auth');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();

        // Tính toán tổng số tiền trước khi áp dụng mã giảm giá
        $subTotal = Cart::subtotal(2, '.', '');

        // Áp dụng mã giảm giá ở đây
        if (session()->has('code')) {


            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // Tính toán phí vận chuyển
        if ($customerAddress != '') {
            $userCountry = $customerAddress->country_id;
            $shippingInfo = shippingCharge::where('country_id', $userCountry)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            // Kiểm tra nếu shippingInfo không phải null
            if ($shippingInfo) {
                $totalShippingCharge = $totalQty * $shippingInfo->amount;
            } else {
                // Xử lý trường hợp không tìm thấy phí vận chuyển
                $totalShippingCharge = 0;  // hoặc có thể thông báo "Không tìm thấy phí vận chuyển"
            }
            // $totalShippingCharge = $totalQty * $shippingInfo->amount;
            $grandTotal = $subTotal - $discount + $totalShippingCharge;
        } else {
            $grandTotal = $subTotal - $discount;
            $totalShippingCharge = 0;
        }

        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => number_format($grandTotal, 2), // Đảm bảo giá trị grandTotal được định dạng
        ]);
    }

    public function processCheckout(Request $request)
    {
        // step - 1 Apply Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'moblie' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'please fix the errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // step - 2 save user address
        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'moblie' => $request->moblie,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
            ]
        );


        // step - 3 store data in orders table
        if ($request->payment_method == "cod") {

            // calculate Shpping
            $discountCodeId = NULL;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2, '.', '');
            $grandTotal = $subTotal + $shipping;

            //Apply Discount here
            if (session()->has('code')) {
                $code = session()->get('code');
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            $shippingInfo = shippingCharge::where('country_id', $request->country)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            } else {
                $shippingInfo = shippingCharge::where('country_id', 'rest_of_world')->first();
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            }



            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->moblie  = $request->moblie  ;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
            $order->country_id = $request->country;
            $order->save();






            // step - 4 store order items in order items table


            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();

            // Update Product Stock
            $productData = Product::find($item->id);
            if ($productData->track_qty == 'Yes') {
                $currentQty = $productData->qty; // Số lượng hiện tại
                $updateQty = $currentQty - $item->qty; // Trừ số lượng đặt hàng từ số lượng tồn kho
                $productData->qty = $updateQty; // Cập nhật số lượng tồn kho
                $productData->save(); // Lưu thay đổi
            }
            }
            //Send Order Mail
            orderEmail($order->id,'customer');
            
            session()->flash('success', 'you have successfully place your order!');

            Cart::destroy();
            session()->forget('code');
            return response()->json([
                'message' => 'Order saved successfully!',
                'orderId' => $order->id,
                'status' => true
            ]);
        } else {
            //
        }
    }


    public function thankyou($id)
    {
        return view('front.thanks', [

            'id' => $id

        ]);
    }


    public function getOrderSummery(Request $request)
    {
        


        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;
        $discountString = '';

        //Apply Discount here
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            // Tạo chuỗi HTML cho discount
            $discountString = '<div class="mt-4" id="discount-response">
    <strong>' . session()->get('code')->code . '</strong>
    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
</div>';
        }




        if ($request->country_id > 0) {

            $shippingInfo = shippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = $shippingCharge + ($subTotal - $discount);

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            } else {
                $shippingInfo = shippingCharge::where('country_id', 'rest_of_world')->first();
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = $shippingCharge + ($subTotal - $discount);

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal - $discount), 2),
                'discount' => number_format($discount,2),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0, 2),
            ]);
        }
    }

    public function applyDiscount(Request $request)
    {
        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Discount coupon!',
            ]);
        }
        $now = Carbon::now();
        //echo $now->format('Y-m-d H:i:s');
        // \Log::info('Current time: ' . $now->toDateTimeString()); // Ghi log thời gian hiện tại
        // \Log::info('Coupon starts at: ' . $code->starts_at); // Ghi log thời gian bắt đầu của coupon

        // Check start date
        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Discount coupon not yet valid!',
                ]);
            }
        }
        // Check expiry date
        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Discount coupon has expired!',
                ]);
            }
        }
        //Kiểm tra số lần sử dụng của mã giảm giá
        if($code->max_uses >0){
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();
            if($couponUsed >= $code->max_uses){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon',
                ]);
            }
        }
        //Kiểm tra số nguời dùng sử dụng của mã giảm giá
        if($code->max_uses_user >0){
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();
            if($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status' => false,
                    'message' => 'You already used this coupon code',
                ]);
            }
        }

        $subTotal = Cart::subtotal(2, '.', '');
        //Kiểm tra số tiền tối thiểu
        if($code->min_amount > 0){
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'message' => 'You min amount must be $'.$code->min_amount,
                ]);
            }
        }

        session()->put('code', $code);
        return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}