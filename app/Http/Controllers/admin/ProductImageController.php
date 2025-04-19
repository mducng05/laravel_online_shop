<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\ProductImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

        class ProductImageController extends Controller
        {
        public function update(Request $request)
        {
            $image = $request->image;

            // Lấy phần mở rộng của ảnh
            $ext = $image->getClientOriginalExtension();

            // Lấy đường dẫn tạm thời của ảnh
            $sourcePath = $image->getPathName();

            // Tạo đối tượng ProductImage mới
            $productImage = new ProductImage();
            $productImage->product_id = $request->product_id;
            $productImage->image = 'NULL';
            $productImage->save();

            // Tạo tên ảnh mới với product_id và id
            $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
            $productImage->image = $imageName;
            $productImage->save();

            // Large Image
            $destPath = public_path() . '/uploads/product/large/' . $imageName;

            $manager = new ImageManager(new Driver());

            $image = $manager->read($sourcePath);
            $image->scaleDown(1400);
            $image->save($destPath);


            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->scaleDown(1400);
            $image->save($destPath);

            // Small Image
            $destPath = public_path() . '/uploads/product/small/' . $imageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->cover(300, 300);
            $image->save($destPath);



            return response()->json([
                'status' => true,
                'image_id' => $productImage->id,
                'ImagePath' => asset('uploads/product/small/' . $productImage->image),
                'message' => 'Image saved successfully'
            ]);
        }
        public function destroy(Request $request)
        {
            $productImage = ProductImage::find($request->id);

            if (empty($productImage)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Image not found'
                ]);
            }

            // Delete images from folder
            File::delete(public_path('uploads/product/large/' . $productImage->image));
            File::delete(public_path('uploads/product/small/' . $productImage->image));

            $productImage->delete();

            return response()->json([
                'status' => true,
                'message' => 'Image deleted successfully'
            ]);
        }
    }