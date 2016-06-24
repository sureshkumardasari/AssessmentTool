<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Resources\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Template extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'templates';
	protected $primaryKey = 'id';

	
	// creates new template
    public function saveIt($type, $assessId, $pdfContent = "", $headerHtml = "", $footerHtml = "", $originalTemplate = "", $originalTemplate_2 = "") {
        
        $this->pdf_content = $pdfContent;
        $this->header = trim($headerHtml,"\r\n");
        $this->footer = trim($footerHtml,"\r\n");
        $this->Type = $type;
        $this->assessment_id = $assessId;
        $this->changed = 'NO';
        $this->save();
        return $this->id;
    }

    public function getTemplates($tpl_id = 0)
    {
        $obj = DB::table('templates'); //new Lesson();
        if($tpl_id > 0){        
            $obj->where('id', $tpl_id);
            $templates = $obj->lists('id', 'assessment_id');
        }
        else
        {
            $templates = $obj->lists('id', 'assessment_id');
        }
        // dd($templates);
        return $templates;
    }

}
