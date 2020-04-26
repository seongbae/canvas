<?php

namespace Seongbae\Canvas\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use File;
use Illuminate\Support\Facades\Log;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);

        return $file;
    }

    private function saveUserImage(UploadedFile $uploadedFile, $folder = null)
    {
        $file = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension(); 
		
		$filename = uniqid('user_').'.'. $extension;
		
		if ($folder)
		{
			if (!File::isDirectory(storage_path($folder)))
            	File::makeDirectory(storage_path($folder), 0777, true, true);

            $filePath = $folder . '/' . $filename;
        }
		else
			$filePath = $filename; 

		Log::info('filePath: '.$filePath);		

		$storagePath = storage_path('app/public/'.$filePath); 
		$assetPath = asset('storage/'.$filePath);		

        Image::make($uploadedFile)
            ->fit(400, 400)
            ->save($storagePath);

        return $assetPath;
    }
}