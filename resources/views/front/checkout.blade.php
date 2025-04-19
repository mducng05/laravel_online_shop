@extends('front.layouts.app')
@section('content')
	
	    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">

            <form id="orderForm" name="orderForm" action="" method="post">
                @csrf
                <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name :''}}">
                                        <p></p>
                                    </div>            
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name :'' }}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email :'' }}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select a Country</option>
                                            @if($countries->isNotEmpty())	
                                            	@foreach($countries as $country)

                                            	<option {{ (!empty($customerAddress)) && $customerAddress->country_id == $country->id ? 'selected' : ''}} value="{{ $country->id }}">{{ $country->name }}</option>

                                            	@endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control" >{{ (!empty($customerAddress)) ? $customerAddress->address :''}}</textarea>
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input  type="text" name="apartment" id="apartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment :''}}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($customerAddress)) ? $customerAddress->city :''}}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ (!empty($customerAddress)) ? $customerAddress->state :''}}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (!empty($customerAddress)) ? $customerAddress->zip :''}}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="moblie" id="moblie" class="form-control" placeholder="Mobile No." value="{{ (!empty($customerAddress)) ? $customerAddress->moblie : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                        <p></p>
                                    </div>            
                                </div>

                            </div>
                        </div>
                    </div>    
                </div>
                <div class="col-md-4">
                    <div class="sub-title">
                        <h2>Order Summery</h3>
                    </div>                    
                    <div class="card cart-summery">
                        <div class="card-body">

                        	@foreach(Cart::content() as $item)
								<div class="d-flex justify-content-between pb-2">
                                	<div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                <div class="h6">${{ $item->price * $item->qty }}</div>
                            </div>
                        	@endforeach
                            
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                            </div>

                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Discount</strong></div>
                                <div class="h6"><strong id="discount_value">${{ $discount }}</strong></div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong id="shippingAmount">${{ number_format($totalShippingCharge , 2) }}</strong></div>
                            </div>

                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong id="grandTotal">${{ number_format((float)$grandTotal , 2) }}</strong></div>

                            </div>                            
                        </div>
                    </div>   
                    
                    <div class="input-group apply-coupan mt-4">
                        <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code">
                        <button class="btn btn-dark" type="button" id="apply-discount">Apply Coupon</button>
                    </div> 

                    <div id="discount-response-wrapper">
                        @if (Session::has('code') && Session::get('code')->code)
                            <div class="mt-4" id="discount-response">
                                <strong>{{ Session::get('code')->code }}</strong>
                                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                            </div>
                        @endif
                    </div>                    

                    

                    <div class="card payment-form ">

                    	<h3 class="card-title h5 mb-3">Payment Method</h3>

                    	<div class="">
                    		<input checked type="radio" name="payment_method" value="cod" id="payment_method_one">
                    		<label for="payment_method_one" class="form-check-label">COD</label>
                    	</div>

                    	<div class="">
                    		<input type="radio" name="payment_method" value="cod" id="payment_method_two">
                    		<label for="payment_method_two" class="form-check-label">Stripe</label>
                    	</div>


                        <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                            <div class="mb-3">
                                <label for="card_number" class="mb-2">Card Number</label>
                                <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">Expiry Date</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">CVV Code</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                </div>
                            </div>

                        </div>  

                        <div class="pt-4">
                                <!-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> -->
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                        </div>

                    </div>

                          
                    <!-- CREDIT CARD FORM ENDS HERE -->
                    
                </div>
                </div>
            </form>    

        </div>
    </section>
@endsection

@section('customJs')

<script>
	// Khi người dùng nhấn vào phương thức thanh toán đầu tiên
	$("#payment_method_one").click(function(){
		if ($(this).is(":checked") == true){ // Kiểm tra nếu phương thức này được chọn
			$("#card-payment-form").addClass('d-none'); // Ẩn form thanh toán bằng thẻ
		}
	});

	// Khi người dùng nhấn vào phương thức thanh toán thứ hai
	$("#payment_method_two").click(function(){
		if ($(this).is(":checked") == true){ // Kiểm tra nếu phương thức này được chọn
			$("#card-payment-form").removeClass('d-none'); // Hiện form thanh toán bằng thẻ
		}
	});


    $("#orderForm").submit(function(event){
        event.preventDefault();
        
 
        $('button[ type = "submit" ]').prop('disabled' ,true);

        $.ajax({
            url : '{{ route("front.processCheckout") }}',
            type : 'post',
            data : $(this).serializeArray(),
            dataType : 'json',
            success : function(response){
                var errors = response.errors;
                $('button[ type = "submit" ]').prop('disabled' ,false);
                if(response.status == false){
                    //1
                    if (errors.first_name) {
                        $("#first_name").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.first_name);
                    } else{
                        $("#first_name").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //2
                    if (errors.last_name) {
                        $("#last_name").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.last_name);
                    } else{
                        $("#last_name").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //3
                    if (errors.email) {
                        $("#email").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.email);
                    } else{
                        $("#email").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //4
                    if (errors.country) {
                        $("#country").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.country);
                    } else{
                        $("#country").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //5
                    if (errors.address) {
                        $("#address").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.address);
                    } else{
                        $("#address").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //6
                    if (errors.appartment) {
                        $("#appartment").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.appartment);
                    } else{
                        $("#appartment").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //7
                    if (errors.city) {
                        $("#city").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.city);
                    } else{
                        $("#city").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //8
                    if (errors.state) {
                        $("#state").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.state);
                    } else{
                        $("#state").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //9
                    if (errors.moblie   ) {
                        $("#moblie").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.moblie  );
                    } else{
                        $("#moblie").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //10
                    if (errors.order_notes) {
                        $("#order_notes").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.order_notes);
                    } else{
                        $("#order_notes").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                    //11
                    if (errors.zip) {
                        $("#zip").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feeback')
                            .html(errors.zip);
                    } else{
                        $("#zip").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feeback')
                            .html('');
                    }
                }else{
                    window.location.href = "{{ url('/thanks/') }}/" + response.orderId;
                }
            }  
        });
    });

    $("#country").change(function(){
        $.ajax({
            url : ' {{ route("front.getOrderSummery") }} ',
            type : 'post',
            data : {country_id : $(this).val()},
            dataType : 'json',
            success : function(response){
                if(response.status == true){
                    $("#shippingAmount").html( '$' + response.shippingCharge);
                    $("#grandTotal").html( '$' + response.grandTotal);
                }
            }
        });
    });

    $("#apply-discount").click(function() {
    $.ajax({
        url: '{{ route("front.applyDiscount") }}',
        type: 'post',
        data: {
            code: $("#discount_code").val(),
            country_id: $("#country").val()
        },
        dataType: 'json',
        success: function(response) {
            if (response.status == true) {
                $("#shippingAmount").html('$' + response.shippingCharge);
                $("#grandTotal").html('$' + response.grandTotal);
                $("#discount_value").html('$' + response.discount);
                $("#discount-response-wrapper").html(response.discountString);
            } else {
                // Hiển thị thông báo lỗi nếu mã giảm giá không hợp lệ
                $("#discount-response-wrapper").html("<span class='text-danger'>"+response.message+"</span>");
            }
        },
        error: function(xhr, status, error) {
            // Xử lý lỗi AJAX
            console.error('AJAX Error:', error);
            alert('Đã xảy ra lỗi khi áp dụng mã giảm giá.');
        }
    });
});


$('body').on('click', "#remove-discount", function() {
    $.ajax({
        url: '{{ route("front.removeCoupon") }}',
        type: 'post',
        data: { country_id: $("#country").val() },
        dataType: 'json',
        success: function(response) {
            if (response.status == true) {
                $("#shippingAmount").html('$' + response.shippingCharge);
                $("#grandTotal").html('$' + response.grandTotal);
                $("#discount_value").html('$' + response.discount);
                $("#discount-response-wrapper").html(''); // Xóa phần hiển thị mã giảm giá
                $("#discount_code").val(''); // Xóa ô nhập mã
            } else {
                alert(response.message || 'Something went wrong!'); // Thông báo lỗi
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('An error occurred while removing the discount.'); // Thông báo lỗi
        }
    });
});


    // $("#remove-discount").click(function(){
        
    // });
</script>


@endsection