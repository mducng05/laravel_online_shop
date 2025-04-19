<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Exception;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        // Kiểm tra nếu có từ khóa tìm kiếm
        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
        }

        $subCategories = $subCategories->paginate(10);

        // Trả về view với dữ liệu $subCategories
        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        return view('admin.sub_category.create', $data);
    }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         // Kiểm tra dữ liệu đầu vào
    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required',
    //             'slug' => 'required|unique:sub_categories,slug,' . $id, // Tránh trùng lặp slug khi chỉnh sửa
    //             'category' => 'required',
    //             'status' => 'required'
    //         ]);

    //         if ($validator->passes()) {
    //             // Tìm SubCategory cần cập nhật
    //             $subCategory = SubCategory::findOrFail($id);
    //             $subCategory->name = $request->name;
    //             $subCategory->slug = $request->slug;
    //             $subCategory->status = $request->status;
    //             $subCategory->category_id = $request->category;
    //             $subCategory->save();

    //             // Flash message cho phiên làm việc hiện tại
    //             session()->flash('success', 'Sub Category updated successfully');

    //             // Trả về phản hồi JSON thành công
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Sub Category updated successfully'
    //             ]);
    //         } else {
    //             // Trả về lỗi nếu validate thất bại
    //             return response()->json([
    //                 'status' => false,
    //                 'errors' => $validator->errors()
    //             ]);
    //         }
    //     } catch (Exception $e) {
    //         // Log lỗi khi có ngoại lệ xảy ra
    //         Log::error('Error updating sub-category: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong. Please try again later.'
    //         ], 500);
    //     }
    // }
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            // Lưu dữ liệu vào database nếu validate thành công
            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->category_id = $request->category; // Lưu category_id từ request
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            // Trả về phản hồi JSON nếu thành công
            return response([
                'status' => true,
                'message' => 'SubCategory created successfully'
            ]);
        } else {
            // Trả về lỗi nếu validate thất bại
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            session()->flash('error', 'Record Not Found');
            return redirect()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        return view('admin.sub_category.edit', $data);
    }

    public function update($id, Request $request)
    {
        $subCategory = SubCategory::find($id);


        if (empty($subCategory)) {
            session()->flash('error', 'Record Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
            // return redirect()->route('sub-categories.index');
        }

        // Kiểm tra dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'slug' => 'required|unique:sub_categories',
            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            // Lưu dữ liệu vào database nếu validate thành công
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->category_id = $request->category; // Lưu category_id từ request
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            Session()->flash('success', 'Sub Category Updated Successfully');

            // Trả về phản hồi JSON nếu thành công
            return response([
                'status' => true,
                'message' => 'SubCategory Updated successfully'
            ]);
        } else {
            // Trả về lỗi nếu validate thất bại
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($id, Request $Request)
    {
        $subCategory = SubCategory::find($id);


        if (empty($subCategory)) {
            session()->flash('error', 'Record Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }
        $subCategory->delete();
        Session()->flash('success', 'Sub Category Delete Successfully');

        // Trả về phản hồi JSON nếu thành công
        return response([
            'status' => true,
            'message' => 'SubCategory Delete successfully'
        ]);
    }
}