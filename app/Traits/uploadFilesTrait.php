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
            $File_ext = $file_name->extension();
            if (in_array($File_ext, ['jpg', 'png', 'jpeg', 'png', 'gif', 'doc', 'docx',
                    'pdf', 'xls', 'xlsx', 'txt', 'lic', 'xml', 'zip', 'rtf', 'rar']) &&
                $file_name->isValid()) {
                $filename = pathinfo($file_name->getClientOriginalName(), PATHINFO_FILENAME);
                $fileNameToStore = 'hardware-' . $filename . '-' . str_random(8) . '.' . $File_ext;
                $path = $file_name->storeAs($directory, $fileNameToStore);
                $moduleName->$file = $fileNameToStore;
                return $moduleName->update();
            }
        }
        return null;
    }


    public function uploadDocumentFiles(
        Request $request,
                $directory,
                $fieldname,
                $moduleName
    )
    {

            if ($request->hasFile($fieldname)) {
                $file_name = $request->file($fieldname);
                $file_ext = $file_name->extension();
                if (in_array($file_ext, ['jpg', 'png', 'jpeg', 'png', 'gif', 'doc', 'docx',
                        'pdf', 'xls', 'xlsx', 'txt', 'lic', 'xml', 'zip', 'rtf', 'rar']) &&
                    $file_name->isValid()) {
                    $filename = pathinfo($file_name->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileNameToStore = time() . $filename . $file_ext;
                    $path = $file_name->storeAs($directory, $fileNameToStore);
                    $moduleName->$fieldname = $fileNameToStore;
                    return $moduleName->update();
                }
            }
            return null;

    }
}