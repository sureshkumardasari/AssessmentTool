<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Branding extends Model {

	 protected $table = 'brandings';
    protected $fillable = ['id','institution_id','title','filepath',  'header_bg_color', 'header_text_color', 'box_header_bg_color', 'box_header_text_color', 'box_text_color', 'button_color'
        
    ];

}
