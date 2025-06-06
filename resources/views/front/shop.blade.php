@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                <li class="breadcrumb-item active">Shop</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">            
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>Categories</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionExample">
                            @if($categories->isNotEmpty())
                                @foreach ($categories as $key => $category)
                                    <div class="accordion-item">
                                        @if($category->sub_category->isNotEmpty())
                                            <h2 class="accordion-header" id="headingOne-{{$key}}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{$key}}" aria-expanded="false" aria-controls="collapseOne-{{$key}}">
                                                    {{$category->name}}
                                                </button>
                                            </h2>
                                            <div id="collapseOne-{{$key}}" class="accordion-collapse collapse {{ ($categorySelected == $category->id)? 'show' : ''}}" aria-labelledby="headingOne-{{$key}}" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="navbar-nav">
                                                        @foreach ($category->sub_category as $subCategory)
                                                            <a href="{{route("front.shop",[$category->slug,$subCategory->slug])}}" class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id)? 'text-primary' : ''}}">{{$subCategory->name}}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{route("front.shop",$category->slug)}}" class="nav-item nav-link {{ ($categorySelected == $category->id)? 'text-primary' : ''}}">{{$category->name}}</a>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                </div>

                <div class="sub-title mt-5">
                    <h2>Brand</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        @if($brands->isNotEmpty())
                        @foreach ($brands as $brand)
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input brand-label" 
                            name="brand[]" 
                            value="{{ $brand->id }}" 
                            id="brand-{{ $brand->id }}"
                            {{ (is_array($brandsArray) && in_array($brand->id, $brandsArray)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="brand-{{ $brand->id }}">
                            {{ $brand->name }}
                        </label>

                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>Price</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <input type="text" class="js-range-slider" name="my_range" value="" />
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                <select name="sort" id="sort">
                                    <option value="latest" {{ ($sort == 'latest') ? 'selected' : ''}}>Latest</option>
                                    <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : ''}}>Price High</option>
                                    <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : ''}}>Price Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if ($products->isNotEmpty())
                    @foreach ($products as $product)
                    @php
                        $productImage = $product->product_images->first();
                    @endphp
                    <div class="col-md-4">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{route("front.product",$product->slug)}}" class="product-img">
                                    @if (!empty($productImage->image))
                                        <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImage->image) }}">
                                    @else
                                        <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                    @endif
                                </a>
                                <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0)" data-product-id="{{ $product->id }}">
                                    <i class="{{ auth()->check() && Auth::user()->wishlist && Auth::user()->wishlist->contains($product->id) ? 'fas' : 'far' }} fa-heart"></i>
                                </a>
                                <div class="product-action">
                                    @if($product->track_qty == 'Yes' )
                                        @if($product->qty > 0 )
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);">
                                                Out Of Stock
                                            </a>
                                        @endif
                                    @else
                                        <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    @endif                       
                                </div>
                            </div>                        
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="{{route("front.product",$product->slug)}}">{{$product->title}}>{{$product->title}}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>${{$product->price}}</strong></span>
                                    @if($product->compare_price > 0)
                                    <span class="h6 text-underline"><del>${{$product->compare_price}}</del></span>
                                    @endif
                                </div>
                            </div>                        
                        </div>                                               
                    </div>
                    @endforeach
                    @endif

                    <div class="col-md-12 pt-5">
                        {{$products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    // Giá trị mặc định ban đầu cho thanh trượt
    var initialMin = 0;
    var initialMax = 1000000000;

    // Kiểm tra nếu đã có giá trị từ sessionStorage, nếu có thì sử dụng giá trị đó
    var storedMin = sessionStorage.getItem('price_min') || initialMin;
    var storedMax = sessionStorage.getItem('price_max') || initialMax;

    // Khởi tạo slider với giá trị từ sessionStorage hoặc giá trị mặc định
    var rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: initialMin,
        max: initialMax,
        from: storedMin,
        to: storedMax,
        step: 10,
        skin: "round",
        max_postfix: "+",
        prefix: "$",
        onFinish: function(data){
            // Lưu giá trị khi người dùng thay đổi thanh trượt
            sessionStorage.setItem('price_min', data.from);
            sessionStorage.setItem('price_max', data.to);
            apply_filters(); // Gọi hàm apply_filters khi người dùng điều chỉnh slider
        }
    });

    var slider = $(".js-range-slider").data("ionRangeSlider");

    // Khi người dùng thay đổi checkbox thương hiệu, gọi hàm apply_filters
    $(".brand-label").change(function() {
        apply_filters();
    });

    // Khi người dùng thay đổi sắp xếp, gọi hàm apply_filters
    $("#sort").change(function(){
        apply_filters();
    });

    function apply_filters() {
        var brands = [];

        // Lấy các thương hiệu đã chọn
        $(".brand-label:checked").each(function() {
            brands.push($(this).val());
        });

        // Tạo URL với các tham số để lọc
        var url = '{{ url()->current() }}';
        
        // Khởi tạo biến cho các tham số query
        var params = [];

        // Thêm các thương hiệu vào URL nếu có
        if (brands.length > 0) {
            params.push('brand=' + brands.join(',')); // Sử dụng join để nối các ID bằng dấu phẩy
        }

        // Lấy giá trị từ slider mà không cập nhật lại
        params.push('price_min=' + slider.result.from);
        params.push('price_max=' + slider.result.to);

        // Nối các tham số lại thành một query string
        if (params.length > 0) {
            url += '?' + params.join('&');
        }

        // Sorting
        var keyword = $("#search").val();
        if (keyword.length > 0) {
            url += '&search=' + keyword;
        }
        url += '&sort=' + $("#sort").val();

        // Chuyển hướng đến URL mới
        window.location.href = url;
    }
</script>
    



@endsection
