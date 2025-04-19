<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách thương hiệu theo thứ tự giảm dần theo 'id'
        $brands = Brand::latest('id');

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->get('keyword')) {
            $brands = $brands->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Phân trang kết quả
        $brands = $brands->paginate(10);

        // Trả về view với danh sách thương hiệu
        return view('admin.brands.list', compact('brands'));
    }



    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if ($validator->passes()) {
            // Tạo đối tượng Brand và lưu vào DB
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            session()->flash('error', 'Record not found');
            return redirect()->route('brands.index');
        }

        $data['brand'] = $brand;
        return view('admin.brands.edit', $data); // Missing $data argument here
    }
    public function update($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            session()->flash('error', 'Record not found');

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id . ',id',
        ]);

        if ($validator->passes()) {
            // Tạo đối tượng Brand và lưu vào DB
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            session()->flash('error', 'Brand NOT Found');
            return response()->json([
                'status' => true,
                'message' => 'Brand NOT Found',
            ]);
        }

        $brand->delete();

        // Flash thông báo thành công
        session()->flash('success', 'Brand deleted successfully');

        // Trả về phản hồi JSON
        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully',
        ]);
    }
}
