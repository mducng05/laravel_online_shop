<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();


            // Save to temp_images table
            $tempImage = new TempImage();
            $tempImage->name = 'TEST';
            $tempImage->save();
            $newName = $tempImage->id . '-' . time() . '.' . $ext;

            $tempImage->name = $newName;
            $tempImage->save();
            // Move the image to the temp directory
            $image->move(public_path() . '/temp', $newName);

            // Generate thumbnail (assuming you use Intervention Image)
            $manager = new ImageManager(new Driver());
            $sourcePath = public_path() . '/temp/' . $newName;
            $destPath = public_path() . '/temp/thumb/' . $newName;
            $image = $manager->read($sourcePath);
            $image->cover(300, 275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/' . $newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}