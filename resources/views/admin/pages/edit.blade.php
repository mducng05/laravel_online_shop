@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Page</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('pages.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="    content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="POST" id="pageForm" name="pageForm">
            @csrf <!-- Token bảo mật CSRF -->
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input value="{{ $pages->name }}" type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input value="{!! $pages->slug !!}" type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="summernote" cols="30" rows="10">{!! $pages->content !!}</textarea>
                            </div>								
                        </div>  							
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('pages.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>


<!-- /.content -->
@endsection

@section('customJs')
<script>
    // Submit pageForm
    $("#pageForm").submit(function(event) {
        event.preventDefault(); // Ngăn chặn form submit thông thường
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("pages.update" , $pages->id) }}', // Đường dẫn lưu dữ liệu
            type: "put",   
            data: element.serializeArray(), // Dữ liệu form
            dataType: 'json',
            success: function(response) { 
                $("button[type=submit]").prop('disabled', true);

                if (response["status"] == true) {

                	// Xóa thông báo lỗi và class is-invalid nếu lưu thành công
                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");

                    $("#slug").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");

                    window.location.href ="{{ route('pages.index')}}";
                } else {
                    // Hiển thị lỗi nếu có
                    var errors = response['errors'];

                    if (errors['name']) {
                        $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['name']);   
                    } else {
                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                    }

                    if (errors['slug']) {
                        $("#slug").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['slug']);   
                    } else {
                        $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                    }
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong"); // Xử lý khi có lỗi
            }
        });
    });

    // Auto generate slug when name changes
    $("#name").change(function() {
        var element = $(this);
        $("button[type=submit]").prop('disabled', false);
        $.ajax({
            url: '{{ route("getSlug") }}', // Đường dẫn để lấy slug
            type: "get",   
            data: {title: element.val()}, // Chỉnh lại thành title cho đúng
            dataType: 'json',
            success: function(response) { 
                $("button[type=submit]").prop('disabled', false);

                if (response["status"] == true)  {
                    
                    $("#slug").val(response["slug"]);
                }
            },
            error: function(jqXHR, exception) {
                console.log("Error occurred while generating slug");
            }
        });
    });
</script>

@endsection

