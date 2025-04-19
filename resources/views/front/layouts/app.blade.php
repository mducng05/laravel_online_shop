<!DOCTYPE html>
<html class="no-js" lang="en_AU" />

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo !empty($title) ? 'Title-' . $title : 'Home'; ?></title>
    <meta name="description" content="" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

    <meta name="HandheldFriendly" content="True" />
    <meta name="pinterest" content="nopin" />

    <meta property="og:locale" content="en_AU" />
    <meta property="og:type" content="website" />
    <meta property="fb:admins" content="" />
    <meta property="fb:app_id" content="" />
    <meta property="og:site_name" content="" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="" />
    <meta property="og:image:height" content="" />
    <meta property="og:image:alt" content="" />

    <meta name="twitter:title" content="" />
    <meta name="twitter:site" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:image:alt" content="" />
    <meta name="twitter:card" content="summary_large_image" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap"
        rel="stylesheet">

    <!-- Fav Icon -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="#" />
    <style>
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }
        .bg-light {
            /* background-image: url('/front-assets/images/eb757a8e3e44f2486143dcf7ece59e61.jpg'); */
            background-size: cover; 
            background-position: center;
            background-repeat: no-repeat; 
        }
        .bg-dark {
            background: rgb(0,0,0);
            background: linear-gradient(90deg, rgba(0,0,0,1) 78%, rgba(0,0,0,1) 87%, rgba(0,0,0,1) 98%, rgba(0,0,0,1) 100%);
            
        }
        .section-4 {
            background-image: url('/front-assets/images/eb757a8e3e44f2486143dcf7ece59e61.jpg');
            background-size: cover; /* Phủ toàn bộ màn hình */
            background-position: center; /* Căn giữa hình nền */
            background-repeat: no-repeat; /* Không lặp lại hình ảnh */
        }
        .section-3 {
            background-image: url('/front-assets/images/eb757a8e3e44f2486143dcf7ece59e61.jpg');
            background-size: cover; /* Phủ toàn bộ màn hình */
            background-position: center; /* Căn giữa hình nền */
            background-repeat: no-repeat; /* Không lặp lại hình ảnh */
        }
        
        .section-7 {
            background: rgb(255,255,255);
            background: linear-gradient(90deg, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%, rgba(255,255,255,1) 100%);
            
        }
        
    </style>
</head>
<body data-instant-intensity="mousedown">
    <div class="bg-light top-header">
        <div class="container">
            <div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
                <div class="col-lg-4 logo">
                    <a href="{{ route('front.home') }}" class="text-decoration-none">
                        <span class="h1 text-uppercase text-primary px-2"><img src="{{asset('front-assets/images/DHD Gaming.png')}}" alt="" style="height: 1.12em; width: auto;"></span>
                        <span class="h1 text-uppercase text-darkaccount.login bg-primary px-2 ml-n1" style="font-style: italic !important; color: black !important;">DHD Gaming</span>
                    </a>
                </div>
                <div class="col-lg-6 col-6 text-left  d-flex justify-content-end align-items-center">
                    @if (Auth::check())
                        <a href="{{ route('account.profile') }}" class="nav-link text-dark">My Account</a>
                    @else
                        <a href="{{ route('account.auth') }}" class="nav-link text-dark">Login/Register</a>
                    @endif
                    <form action="{{route('front.shop')}}" method="get">
                        <div class="input-group">
                            <input value="{{Request::get('search')}}" type="text" placeholder="Search For Products" class="form-control" name="search" id="search">
                            <button type="submit" class="input-group-text">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-xl" id="navbar">
                <a href="index.php" class="text-decoration-none mobile-logo">
                    <span class="h2 text-uppercase text-primary bg-dark">Online</span>
                    <span class="h2 text-uppercase text-white px-2">SHOP</span>
                </a>
                <button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <!-- <span class="navbar-toggler-icon icon-menu"></span> -->
                    <i class="navbar-toggler-icon fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        @if (getCategories()->isNotEmpty())
                            @foreach (getCategories() as $category)
                                <li class="nav-item dropdown">
                                    <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        {{ $category->name }}
                                    </button>
                                    @if ($category->sub_category->isNotEmpty())
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            @foreach ($category->sub_category as $subCategory)
                                                <li><a class="dropdown-item nav-link"
                                                        href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}">{{ $subCategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="right-nav py-0">
                    <a href="{{ route('front.cart') }}" class="ml-3 d-flex pt-2">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        {{-- @if ($cartCount > 0)
                            <span class="badge bg-primary position-absolute top-0 start-100 translate-middle">
                                {{ $cartCount }}
                            </span>
                        @endif --}}
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark mt-5">
        <div class="container pb-5 pt-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Get In Touch</h3>
                        <p>Build PC đi, đừng mua laptop <br>
                            123 Street, New York, USA <br>
                            dhdgamingshop@gmail.com<br>
                            012 345 6789</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Important Links</h3>
                        <ul>
                            @if(staticPage()->isNotEmpty())
                                @foreach(staticPage() as $page)
                                <li><a href="{{route('front.page', $page->slug)}}" title="{{$page->name}}">{{$page->name}}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>My Account</h3>
                        <ul>
                            <li><a href="{{ route('account.auth') }}" title="Sell">Login/Register</a></li>
                            <li><a href="{{ route('account.orders') }}" title="Contact Us">My Orders</a></li>
                            <li><a href="{{ route('account.wishlist') }}" title="Contact Us">My Wishlist</a></li>
                            <li><a href="{{ route('front.cart') }}" title="Contact Us">My Cart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-3">
                        <marquee width="100%" direction="left" >
                        Chào mừng bạn đến với shop bán hàng của chúng tôi , chúc bạn một ngày tốt lành.
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- WishList Modal -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        AOS.init(); // Khởi tạo AOS
    </script>

    <script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('front-assets/js/custom.js') }}"></script>
    <script>
        window.onscroll = function() {
            myFunction()
        };

        var navbar = document.getElementById("navbar");
        var sticky = navbar.offsetTop;

        function myFunction() {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky")
            } else {
                navbar.classList.remove("sticky");
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token bảo mật CSRF
            },
        });

        function addToCart(id) {
            $.ajax({
                url: '{{ route('front.addToCart') }}',
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'  // Đảm bảo gửi token CSRF
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('front.cart') }}";
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        alert('Please login to add products to your cart.');
                        window.location.href = "{{ route('account.auth') }}"; // Chuyển hướng tới trang đăng nhập
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        }

        function addToWishList(id) {
            $.ajax({
                url: '{{ route('front.addToWishlist') }}',
                type: 'POST',
                data: {
					_token: '{{ csrf_token() }}',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#wishlistModal .modal-body").html(response.message);
                        $("#wishlistModal").modal('show');

						//Cập nhật thành trái tim đầy
						$('.whishlist[data-product-id="' + id + '"] i').removeClass('far').addClass('fas').css('color', '#FFD700'); // Màu vàng đậm hơn
                    } else {
                        alert('Please login to add products to your cart.');
                        window.location.href = "{{ route('account.auth') }}";
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        alert('Please login to add products to your cart.');
                        window.location.href = "{{ route('account.auth') }}"; // Chuyển hướng tới trang đăng nhập
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        }
    </script>
    @yield('customJs')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</body>

</html>
