<?php
/**
 * Store Image trait
 * 31 August 2022
 * Nkosana Gift
 * ncubesss@gmail.com
 */

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait StoreImageTrait
{

    /**
     * @param $directory
     * @param $fieldname
     * @param $moduleName
     * @param Request $request
     * @return mixed|null
     */
    public function verifyAndStoreImage
    (
        $directory,
        $fieldname,
        $moduleName,
        Request $request
    )
    {
        if ($request->hasFile($fieldname)) {
            $image_name = $request->file($fieldname);
            $image_ext = $image_name->extension();
            if (in_array($image_ext, ['jpg', 'png', 'jpeg']) &&
                $image_name->isValid()) {
                $filename = pathinfo($image_name->getClientOriginalName(), PATHINFO_FILENAME);
                $fileNameToStore = $filename . '-' . time() . '.' . $image_ext;
                $path = $image_name->storeAs($directory, $fileNameToStore);
                $moduleName->$fieldname = $fileNameToStore;
                return $moduleName->update();
            }
        }
        return null;
    }

}