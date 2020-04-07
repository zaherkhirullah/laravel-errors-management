<?php

//use App\Attachment;
use App\Attachment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('version')) {

    /**
     * @param $file
     *
     * @return mixed
     */
    function version($file)
    {
        return File::exists($file) ? File::lastModified($file) : '1';
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('asset_v')) {
    function asset_v($path, $prefix = 'v')
    {
        return asset($path)."?{$prefix}=".version(public_path($path));
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('getFolderPath')) {
    function getFolderPath($folder)
    {
        // get folder public path
        $path = public_path($folder);

        // check if folder exist
        if (!file_exists($path)) {
            File::makeDirectory($path, 0777, true);
        }

        return $path;
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('store_file')) {
    /**
     * @param $path
     * @param $file
     *
     * @return string
     */
    function store_file($path, $file)
    {
        // get file extension
        $extension = $file->getClientOriginalExtension();
        // filename to store
        // time +_+ 00 + XXX + 000 + x + 0000 = // time_00XXX000x0000.png
        $hash = md5(time()).'_'.rand(10, 99).Str::random(3).rand(100, 999).chr(rand(65, 90)).rand(1000, 9999);

        $filename = $hash.'.'.$extension;

        $file->move($path, $filename);

        return $filename;
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('uploadImage')) {
    /**
     * @param      $request
     * @param      $field_name
     * @param      $folder
     * @param null $old_image
     *
     * @return string
     */
    function uploadImage($request, $field_name, $folder, $old_image = null)
    {
        //delete old file
        if ($old_image) {
            unlinkFile($old_image, $folder);
        }

        // public path for folder
        $path = getFolderPath($folder);

        // get file from request
        $file = $request->file($field_name);

        // save file in folder and return name
        return store_file($path, $file);
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('uploadImages')) {
    /**
     * @param $request
     * @param $field_name
     * @param $folder
     *
     * @return array
     */
    function uploadImages($request, $field_name, $folder)
    {
        // public path for folder
        $path = getFolderPath($folder);

        $arrFileNames = [];
        // get just extension
        foreach ($request->file($field_name) as $file) {
            // save file in folder and return name
            $arrFileNames[] = store_file($path, $file);
        }

        return $arrFileNames;
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('attachFiles')) {
    /**
     * @param      $request
     * @param      $field_name
     * @param      $folder
     * @param null $row
     * @param null $type
     *
     * @return array
     */
    function attachFiles($request, $field_name, $folder, $row = null)
    {

        // public path for folder
        $path = getFolderPath($folder);

        $arrFileNames = [];
        // get just extension
        foreach ($request->file($field_name) as $file) {
            // filename to store
            $original_name = $file->getClientOriginalName();
            $size = $file->getSize();

            $type = getItemIfExists(explode('/', $file->getMimeType()), 0);

            // save file in folder and return name
            $storage_name = store_file($path, $file);

            $arrFileNames[] = saveAttachments($request, $row, $original_name, $storage_name, $folder, $size, $type);
        }

        return $arrFileNames;
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('saveAttachments')) {
    function saveAttachments($request, $row, $original_name, $storage_name, $folder, $size, $type)
    {
        $file = new Attachment();
        $file->file_name = $original_name;
        $file->storage_name = $storage_name;
        $file->path = $folder;
        $file->size = $size;
        $file->type = $type;
        if ($row) {
            $row->attachments()->save($file);
        } else {
            $file->attachable_id = $request->attachable_id;
            $file->attachable_type = $request->attachable_type;
            $file->save();
        }

        return $file;
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('unlinkFile')) {
    /**
     * for delete file from directory.
     *
     * @param $fileName   ( obj->file )
     * @param $folderName ('uploads/folderName')
     */
    function unlinkFile($fileName, $folderName)
    {
        // get file source
        if ($fileName && $fileName != '') {
            $old = public_path($folderName.'/'.$fileName);
            if (File::exists($old)) {
                // unlink or remove previous image from folder
                unlink($old);
            }
        }
    }
}
/*---------------------------------- </> ----------------------------------*/

if (!function_exists('uploadFromTiny')) {
    /**
     * @param        $request
     * @param string $field_name
     * @param        $folder
     *
     * @return mixed
     */
    function uploadFromTiny($request, $field_name, $folder)
    {
        try {
            $folder = "uploads/{$folder}";

            $file = $request->file($field_name);

            $path = getFolderPath($folder);

            $hash = 'image_'.time().'_'.$file->hashName();

            $filename = $file->move($path, $hash);

            $path = asset("uploads/{$folder}/{$filename}");

            return response(['location' => $path], 200);
        } catch (Exception $exp) {
            return response(['location' => $exp], 401);
        }
    }
}
