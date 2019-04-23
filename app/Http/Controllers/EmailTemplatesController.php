<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use Illuminate\Http\Request;

use App\Http\Requests;

class EmailTemplatesController extends Controller
{
    /**
     * Save or update email template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveOrUpdate(Request $request)
    {
        $this->validate($request, [
            'template_key' => 'required',
        ]);

        $templateKey = $request->input('template_key');
        $emailTemplate = EmailTemplate::where('template_key', $templateKey)->get()->first();
        if ($emailTemplate) {
            $emailTemplate->template_content = $request->input('template_content');
            $emailTemplate->update();
        }
        else 
		{
            $emailTemplate = new EmailTemplate($request->all());
            $emailTemplate->save();
        }
        return back();
    }
}
