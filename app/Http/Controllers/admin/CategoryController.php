<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $categories = $categories->paginate(10);

        // Trả về view với dữ liệu $categories
        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        // Sử dụng Validator với chữ viết hoa
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;

            $category->save();

            // Lưu hình ảnh ở đây
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                // Generate Image Thumbnail
                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $manager = new ImageManager(new Driver());

                $image = $manager->read($sPath);
                $image->cover(450, 600);
                $image->save($dPath);
                

                $category->image = $newImageName;
                $category->save();
            }

            // Sử dụng Session facade để lưu thông báo flash
            Session::flash('success', 'Category added successfully'); // Đã sửa

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index')->with('error', 'Category not found');
        }

        return view('admin.category.edit', compact('category'));
    }


    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            session()->flash('error', 'Category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }



        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $request->image;

            // Lưu hình ảnh ở đây
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                // Generate Image Thumbnail
                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sPath);
                $image->cover(450, 600);
                $image->save($dPath);


                $category->image = $newImageName;
                $category->save();


                //delete old image here
                File::delete(public_path() . '/uploads/category/thumb/)' . $oldImage);
                File::delete(public_path() . '/uploads/category/)' . $oldImage);
            }

            // Sử dụng Session facade để lưu thông báo flash
            Session::flash('success', 'Category updated successfully'); // Đã sửa

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        // Kiểm tra nếu category không tồn tại
        if (empty($category)) {
            session()->flash('error', 'Category NOT Found');
            return response()->json([
                'status' => true,
                'message' => 'Category NOT Found',
            ]);
            // return redirect()->route('categories.index');
        }

        // Xóa hình ảnh thumb và hình ảnh chính
        File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
        File::delete(public_path() . '/uploads/category/' . $category->image);

        // Xóa category
        $category->delete();

        // Flash thông báo thành công
        session()->flash('success', 'Category deleted successfully');

        // Trả về phản hồi JSON
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}