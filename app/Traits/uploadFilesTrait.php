<?php
/**
 * Store Image trait
 * 31 August 2022
 * Nkosana Gift
 * ncubesss@gmail.com
 */

namespace App\Traits;
use Illuminate\Http\Request;

trait uploadFilesTrait
{

    /**
     * @param $directory
     * @param $file
     * @param $moduleName
     * @param Request $request
     * @return mixed|null
     */
    public function uploadFile
    (
        Request $request,
        $file,
        $directory,
        $moduleName

    )
    {
        if ($request->hasFile($file)) {
            $file_name = $request->file($file);
            $image_ext = $file_name->extension();
            if (in_array($image_ext, ['jpg', 'png', 'jpeg','png', 'gif', 'doc', 'docx',
                    'pdf', 'xls', 'xlsx', 'txt', 'lic', 'xml', 'zip', 'rtf', 'rar']) &&
                $file_name->isValid()) {
                $filename = pathinfo($file_name->getClientOriginalName(), PATHINFO_FILENAME);
                $fileNameToStore = 'hardware-' . $filename . '-' . str_random(8) . '.' . $image_ext;
                $path = $file_name->storeAs('public' . '/' . $directory, $fileNameToStore);
                $moduleName->$file = $fileNameToStore;
                return $moduleName->update();
            }
        }
        return null;
    }
}