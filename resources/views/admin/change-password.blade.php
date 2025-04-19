@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Change Password</h1>
            </div>
            <div class="col-sm-6 text-right">
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="" method="POST" id="changePasswordForm" name="changePasswordForm">
            @csrf <!-- Token bảo mật CSRF -->
            <div class="card">
                <div class="card-body">								
                    <div class="row">

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Old Password</label>
                                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password">	
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">   
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">   
                                <p></p>
                            </div>
                        </div>

                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>


<!-- /.content -->
@endsection

@section('customJs')
<script>
    // Submit form
    $("#changePasswordForm").submit(function(event) {
    event.preventDefault(); // Ngăn chặn form submit thông thường
    var element = $(this);
    $("button[type=submit]").prop('disabled', true); // Vô hiệu hóa nút khi gửi yêu cầu AJAX
    $.ajax({
        url: '{{ route("admin.processChangePassword") }}', // Đường dẫn lưu dữ liệu
        type: "POST",   
        data: element.serializeArray(), // Dữ liệu form
        dataType: 'json',
        success: function(response) { 
            if (response["status"] === true) {
                window.location.href ="{{ route('admin.showChangePasswordForm')}}";
            } else {
                // Hiển thị lỗi nếu có
                var errors = response.errors;
                if (errors.old_password) {
                    $("#old_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.old_password);
                }else{
                    $("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if (errors.new_password) {
                    $("#new_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.new_password);
                }else{
                    $("#new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if (errors.confirm_password) {
                    $("#confirm_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.confirm_password);
                }else{
                    $("#confirm_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }
            }
            $("button[type=submit]").prop('disabled', false); // Kích hoạt lại nút submit
        },
        error: function(jqXHR, exception) {
            console.log("Something went wrong"); // Xử lý khi có lỗi
            $("button[type=submit]").prop('disabled', false); // Kích hoạt lại nút submit
        }
    });
});


</script>

@endsection

