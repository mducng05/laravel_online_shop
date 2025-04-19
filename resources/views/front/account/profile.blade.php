@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('front.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name"
                                            placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                                            placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone"
                                            placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Shipping Address</h2>
                    </div>
                    <form action="" name="addressForm" id="addressForm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">First Name</label>
                                    <input value="{{ !empty($address) ? $address->first_name : '' }}" type="text"
                                        name="first_name" id="first_name" placeholder="Enter Your First Name"
                                        class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name">Last Name</label>
                                    <input value="{{ !empty($address) ? $address->last_name : '' }}" type="text"
                                        name="last_name" id="last_name" placeholder="Enter Your Last Name"
                                        class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input value="{{ !empty($address) ? $address->email : '' }}" type="text"
                                        name="email" id="email" placeholder="Enter Your Mail" class="form-control">
                                    <p></p>
                                </div>
                                <div class=" col-md-6 mb-3">
                                    <label for="phone">Mobile</label>
                                    <input value="{{ !empty($address) ? $address->moblie : '' }}" type="text"
                                        name="moblie" id="moblie" placeholder="Enter Your Phone" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Select a Country</option>
                                        @if ($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                                <option
                                                    {{ !empty($address) && $address->country_id == $country->id ? 'selected' : '' }}
                                                    value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Address</label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ !empty($address) ? $address->address : '' }}</textarea>
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Apartment</label>
                                    <input value="{{ !empty($address) ? $address->apartment : '' }}" type="text"
                                        name="apartment" id="apartment" placeholder="Apartment" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">City</label>
                                    <input value="{{ !empty($address) ? $address->city : '' }}" type="text"
                                        name="city" id="city" placeholder="City" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">State</label>
                                    <input value="{{ !empty($address) ? $address->state : '' }}" type="text"
                                        name="state" id="state" placeholder="State" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">ZIP</label>
                                    <input value="{{ !empty($address) ? $address->zip : '' }}" type="text"
                                        name="zip" id="zip" placeholder="ZIP" class="form-control">
                                    <p></p>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-dark" style="margin-left: auto;">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        //PROFILE FORM
        $("#profileForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateProfile') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#profileForm #name")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');
                        $("#profileForm #email")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');
                        $("#profileForm #phone")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        window.location.href = '{{ route('account.profile') }}'
                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $("#profileForm #name")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.name)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profileForm #name")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.email) {
                            $("#profileForm #email")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.email)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profileForm #email")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.phone) {
                            $("#profileForm #phone")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.phone)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profileForm #phone")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });

        //ADDRESS FORM
        $("#addressForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateAddress') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#name")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');
                        $("#email")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');
                        $("#phone")
                            .removeClass('is-invalid')
                            .siblings('p')
                            .html('')
                            .removeClass('invalid-feedback');

                        window.location.href = '{{ route('account.profile') }}'
                    } else {
                        var errors = response.errors;
                        if (errors.first_name) {
                            $("#addressForm #first_name")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.first_name)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #first_name")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.last_name) {
                            $("#addressForm #last_name")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.last_name)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #last_name")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.email) {
                            $("#addressForm #email")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.email)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #email")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.email)
                                .removeClass('invalid-feedback');
                        }

                        if (errors.moblie) {
                            $("#addressForm #moblie")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.phone)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #moblie")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.country_id) {
                            $("#addressForm #country_id")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.country_id)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #country_id")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }
                        
                        if (errors.apartment) {
                            $("#addressForm #apartment")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.apartment)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #apartment")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.address) {
                            $("#addressForm #address")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.address)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #address")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.city) {
                            $("#addressForm #city")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.city)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #city")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.state) {
                            $("#addressForm #state")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.state)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #state")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }

                        if (errors.zip) {
                            $("#addressForm #zip")
                                .addClass('is-invalid')
                                .siblings('p')
                                .html(errors.zip)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #zip")
                                .removeClass('is-invalid')
                                .siblings('p')
                                .html('')
                                .removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });
    </script>
@endsection
