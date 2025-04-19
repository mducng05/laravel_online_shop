@extends('front.layouts.app')

@section('content')
<section class="section-1" class="row pb-3" data-aos="fade-up" data-aos-duration="2000">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner" style="height: 100vh;"> <!-- Chiều cao toàn màn hình -->
            <div class="carousel-item active" style="height: 100%;">
                <picture style="height: 100%;">
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/banner_170445142354207818e5028f0c1f969f8e17c9fbac.jpeg') }}" />
                    <img src="{{ asset('front-assets/images/mh-kv4.jpg') }}" alt="" style="object-fit: cover; height: 100%; width: 100%;">
                </picture>
            </div>
            <div class="carousel-item" style="height: 100%;">
                <picture style="height: 100%;">
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/banner_1725444679a80ecaab388a3c0c3b2995557eb6a2ec.jpeg') }}" />
                    <img src="{{ asset('front-assets/images/carousel-2.jpg') }}" alt="" style="object-fit: cover; height: 100%; width: 100%;">
                </picture>
            </div>
            <div class="carousel-item" style="height: 100%;">
                <picture style="height: 100%;">
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/banner_1720490670ec6b2fdabe0ebf4a46f1ab37bc6b1d89.jpeg') }}" />
                    <img src="{{ asset('front-assets/images/carousel-3.jpg') }}" alt="" style="object-fit: cover; height: 100%; width: 100%;">
                </picture>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<section class="section-3" class="row pb-3" data-aos="fade-up" data-aos-duration="2000">
    <div class="container">
        <div class="section-title">
            <h2>EXPLORE OUR PRODUCTS</h2>
        </div>
        <div class="product-slider">
            <div class="product-container">
                @if(getCategories()->isNotEmpty())
                    @foreach(getCategories() as $key => $category)
                        <div class="cat-card" data-index="{{ $key }}">
                            <div class="left">
                                @if($category->image != "")
                                    <img src="{{ asset('uploads/category/thumb/'.$category->image) }}" alt="" class="img-fluid">
                                @endif
                            </div>
                            <div class="cat-data">
                                <h2>{{$category->name}}</h2>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </div>
</section>



<section class="section-1">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" data-aos="fade-up" data-aos-duration="2000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <video class="d-block w-100" autoplay muted loop>
                    <source src="{{ asset('front-assets/images/acernitro.mp4') }}" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</section>

<section class="section-4 pt-5" class="row pb-3" data-aos="fade-up" data-aos-duration="2000">
    <div class="container">
        <div class="section-title">
            <h2>All Products</h2>
        </div>    
        <div class="row pb-3">
            @if ($paginatedItems->isNotEmpty())
                @foreach ($paginatedItems as $index => $product)
                    @php
                        $productImage = $product->product_images->first();
                    @endphp
                    <div class="col-md-3" data-aos="{{ $index % 2 == 0 ? 'fade-left' : 'fade-right' }}" data-aos-duration="1000">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                    @if (!empty($productImage->image))
                                        <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImage->image) }}">
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                    @endif
                                </a>
                                <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0)" data-product-id="{{ $product->id }}">
                                    <i class="{{ auth()->check() && Auth::user()->wishlist ? (Auth::user()->wishlist->contains($product->id) ? 'fas' : 'far') : 'far' }} fa-heart"></i>
                                </a>      
                                <div class="product-action">
                                    @if ($product->track_qty == 'yes')
                                        @if ($product->qty > 0)
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);">
                                                Out Of Stock
                                            </a>
                                        @endif
                                    @else
                                        <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    @endif
                                </div>
                            </div>                         
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="{{route("front.product",$product->slug)}}">{{$product->title}}</a>
                                <div class="price mt-2">
                                    <!-- Định dạng giá với dấu phân cách hàng nghìn và thêm VND -->
                                    <span class="h5"><strong>{{ number_format($product->price, 0, '.', '.') }} VND</strong></span>
                                    @if($product->compare_price > 0)
                                        <span class="h6 text-underline"><del>{{ number_format($product->compare_price, 0, '.', '.') }} VND</del></span>
                                    @endif
                                </div>
                            </div>                         
                        </div>                                               
                    </div>
                @endforeach
            @endif               
        </div>

        <!-- Thêm liên kết phân trang -->
        <div class="pagination-links">
            {{ $paginatedItems->links() }}
        </div>
    </div>
</section>

<section class="section-1">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" data-aos="fade-up" data-aos-duration="2000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <video class="d-block w-100" autoplay muted loop>
                    <source src="{{ asset('front-assets/images/Mac - Apple - Google Chrome 2024-11-10 15-51-41.mp4') }}" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</section>
<script>
let currentIndex = 0; // Chỉ số slide hiện tại
const slides = document.querySelectorAll('.cat-card'); // Lấy tất cả các slide
const totalSlides = slides.length; // Tổng số slide

function moveSlide(direction) {
    // Cập nhật chỉ số slide mới
    currentIndex += direction;

    // Nếu currentIndex nhỏ hơn 0 thì quay lại slide cuối
    if (currentIndex < 0) {
        currentIndex = totalSlides - 1; // Quay lại slide cuối
    } else if (currentIndex >= totalSlides) {
        currentIndex = 0; // Quay lại slide đầu tiên ngay lập tức
    }

    // Cập nhật vị trí của product-container
    const offset = -currentIndex * (100 / 5); // 100% chia cho 5 sản phẩm trong hàng
    document.querySelector('.product-container').style.transform = `translateX(${offset}%)`;
}

    </script>
@endsection