<?php
/**
 * BreadCramp trait
 * 31 August 2022
 * Nkosana Gift
 * ncubesss@gmail.com
 */
namespace App\Traits;

use Illuminate\Http\Request;

trait BreadCrumpTrait {

    /**
     * @param $activeModule
     * @param $activeRib
     * @param $icon
     * @param $pageTitle
     * @param $pageDescription
     * @param $path
     * @param $title1
     * @param $title2
     * @return array
     */
    public function breadCrump
    (
        $activeModule,
        $activeRib,
        $icon,
        $pageTitle,
        $pageDescription,
        $path,
        $title1,
        $title2
    )
    {
        $data['page_title'] = $pageTitle;
        $data['page_description'] = $pageDescription;

        $data['breadcrumb'] = [
            ['title' => $title1, 'path' => $path, 'icon' => $icon, 'active' => 0, 'is_module' => 1],
            ['title' => $title2, 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = $activeModule;
        $data['active_rib'] = $activeRib;

        return $data;
    }

}
