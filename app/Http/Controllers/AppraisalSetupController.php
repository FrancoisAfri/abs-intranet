 <?php

namespace App\Http\Controllers;







 public function show()
    {
		$Templates = appraisalTemplates::orderBy('template', 'asc')->get();
		if (!empty($Templates))
			$Templates = $Templates->load('jobTitle');
		$JobTitles = JobTitle::orderBy('name', 'asc')->get();
        $data['page_title'] = "Templates";
        $data['page_description'] = "Manage Templates";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Templates', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Setup';
        $data['Templates'] = $Templates;
        $data['JobTitles'] = $JobTitles;
		//return $data;
		AuditReportsController::store('Performance Appraisal', 'Templates Page Accessed', "Actioned By User", 0);
        return view('appraisals.templates')->with($data);
    }