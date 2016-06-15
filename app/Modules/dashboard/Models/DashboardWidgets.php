<?php

namespace App\Modules\dashboard\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DashboardWidgets
 *
 * @property integer $id
 * @property boolean $is_fusion_chart
 * @property string $widget_type
 * @property string $widget_template
 * @property string $widget_div_id
 * @property string $widget_headline
 * @property string $widget_text
 * @property string $user_type
 * @property string $width
 * @property string $height
 * @property string $class
 * @property string $color_1
 * @property string $color_2
 * @property string $color_3
 * @property string $color_4
 * @property string $color_5
 * @property string $params
 * @property boolean $is_three_axis
 * @property string $axis_x_title
 * @property string $axis_y_title
 * @property string $axis_y1_title
 * @property boolean $has_button
 * @property string $button_text
 * @property string $button_link
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereIsFusionChart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereWidgetType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereWidgetTemplate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereWidgetDivId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereWidgetHeadline($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereWidgetText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereUserType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereClass($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereColor1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereColor2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereColor3($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereColor4($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereColor5($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereIsThreeAxis($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereAxisXTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereAxisYTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereAxisY1Title($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereHasButton($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereButtonText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereButtonLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DashboardWidgets whereUpdatedAt($value)
 */
class DashboardWidgets extends Model {

    protected $table = 'dashboard_widgets';

}
