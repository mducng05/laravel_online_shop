@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="    content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="{{ route('categories.store') }}" method="POST" id="categoryForm" name="categoryForm">
            @csrf <!-- Token bảo mật CSRF -->
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="hidden" id="image_id" name="image_id" value="">
                                <label for="image">Image</label>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control"> 
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Show on Home</label>
                                <select name="showHome" id="showHome" class="form-control"> 
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>										
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
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
    $("#categoryForm").submit(function(event) {
        event.preventDefault(); // Ngăn chặn form submit thông thường
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("categories.store") }}', // Đường dẫn lưu dữ liệu
            type: "POST",   
            data: element.serializeArray(), // Dữ liệu form
            dataType: 'json',
            success: function(response) { 
                $("button[type=submit]").prop('disabled', true);

                if (response["status"] === true) {

                    window.location.href ="{{ route('categories.index')}}";


                    // Xóa thông báo lỗi và class is-invalid nếu lưu thành công
                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");

                    $("#slug").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");   

                    alert('Category created successfully!');
                    // Reset form hoặc làm gì đó sau khi thành công
                    element[0].reset();
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

    Dropzone.autoDiscover = false;
const dropzone = $("#image").dropzone({
    init: function() {
        this.on("addedfile", function(file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });
    },
    url: "{{ route('temp-images.create') }}",  // Kiểm tra lại route đã khai báo
    maxFiles: 1,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Đổi từ _token sang csrf-token
    },
    success: function(file, response) {
        $("#image_id").val(response.image_id);
    },
    error: function(file, response) {
        console.log(response);  // Log lại response để kiểm tra nếu gặp lỗi
    }
});


</script>

@endsection

