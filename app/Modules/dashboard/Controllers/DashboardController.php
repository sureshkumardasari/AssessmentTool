<?php

namespace App\Modules\Dashboard\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

use App\Modules\dashboard\Models\DashboardWidgets;
use App\Classes\FusionCharts;
use Illuminate\Http\Request;
use stdClass;


class DashboardController extends BaseController
{
    /*
      |--------------------------------------------------------------------------
      | Dashboard Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
      |
     */


    private $currentUserId;

    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        $this->currentUserId = Auth::user()->id;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function home(Request $request)
    {  
       
        $user = Auth::User();

       // dd($user->role_id);
        if ($this->currentUserId) {
            //**workflow is:
            //  get the user's role
            //  identify the dashboards needed
            //  pull those dashboard widgets
            //  render assembled data
            $widgets = [];
            $filters = [];
            switch ($user->role_id) {
                case 1:
                    $userWidgets = $this->getInternalStaffWidgets($user,$filters);
                    break;
                case 2:
                    $userWidgets = $this->getStudentWidgets($user,$filters);                                     
                    break;
                case 3:
                    $userWidgets = $this->getTutorWidgets($user,$filters);
                    break;
            }

        } else {
            $errorMessage = 'Sorry, there is no dashboard for this user.';
            $errorDetails = 'Please try again.';

            return view('dashboard::error', compact('errorMessage', 'errorDetails'));
        }

        $role        = $userWidgets['role'];
        $dropdown    = $userWidgets['dropdown'];
        $dropOptions = $userWidgets['options'];  //only used when 'dropdown' is true
        $alerts      = $userWidgets['alerts'];
        $widgets     = $userWidgets['data'];
        dd($alerts);
        $dropDownLabel = $userWidgets['label']; 
        $studensList = $userWidgets['ListOptions'];        
        return view('dashboard::dashboard.home', compact('user', 'widgets', 'alerts', 'dropdown', 'dropOptions', 'role','studensList','dropDownLabel'));
    }

    /**
     * @param $user
     * @return mixed
     */
    private function getStudentWidgets($user)
    {
        $dashboardWidgets = new DashboardWidgets();
        $widgetsList = $dashboardWidgets->where('user_type','all')->orderby('id','asc')->get();

        if(!empty($widgetsList)) { 
            foreach($widgetsList as $key=>$row) {
                $data = [];           
                
                if($row->widget_type == 'plans') {
                    $data['most_recent_test_date'] = '';
                    $data['upcoming_test_date'] = '';
                    $assignment = new AssessmentAssignmentUser();
                    $aaUser = $assignment->where('UserId',Auth::user()->id)                                            
                                  ->where('Status','Upcoming')
                                  ->orderBy('created_at','desc')
                                  ->first();

                    if($aaUser) {
                        $assessmentAssignment = new AssessmentAssignment();        
                        $assignmentUpcoming = $assessmentAssignment
                                        ->where('Id',$aaUser->AssignmentId)
                                        ->first();
                    }
                    
                    $assignmentComplete = $assignment->where('UserId',Auth::user()->id)
                                  ->where('Status','Complete')
                                  ->orderBy('TakenDate','desc')                      
                                  ->first();

                    if($assignmentComplete) {    
                        $data['most_recent_test_date'] = $assignmentComplete->TakenDate;
                    }
                    if($assignmentComplete) { 
                        $data['upcoming_test_date']  = $assignmentUpcoming->StartDate;    
                    }     


                } 
                else if($row->widget_div_id == 'assignments') {
                     // $assignment = new AssessmentAssignmentUser(); 
                     // $assignments = $assignment->join('Assignments','AssessmentAssignmentUsers.AssignmentId','=','Assignments.Id')
                     //    ->where('Assignments.Types','files')
                     //    ->where('AssessmentAssignmentUsers.Status','Upcoming')
                     //    ->orderBy('Assignments.Id','DESC')
                     //    ->take(10)
                     //    ->get();
                     $assigmentsList = Dashboard::getFileAssignmentsForDashbaord();    
                     $data = $assigmentsList;
                }
                $widgetData[] = $this->getWidget($row->id,$data,''); 
               
            }
            //**students don't have dropdowns
            $widgets['dropdown'] = false;
            $widgets['options'] = '';

            //**students might have time alerts
            $widgets['alerts'] = false;

            //**role is fixed
            $widgets['role'] = 'student';

            $widgets['data'] = $widgetData;
          
        }
        $widgets['label'] = '';
        $widgets['ListOptions'] = '';
        return $widgets;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function getTutorWidgets($user)
    {
        
        $dashboardWidgets = new DashboardWidgets();
        $widgetsList = $dashboardWidgets->where('user_type','all')->orderby('id','asc')->get();

        if(!empty($widgetsList)) { 
            foreach($widgetsList as $key=>$row) { 
                //echo '<pre>';print_r($row);
                $data = [];                
               
                $widgetData[] = $this->getWidget($row->id,$data,'');
            }
            //**students don't have dropdowns
            $widgets['dropdown'] = false;
            $widgets['options'] = '';

            //**students might have time alerts
            $widgets['alerts'] = false;

            //**role is fixed
            $widgets['role'] = 'student';

            $widgets['data'] = $widgetData;
          
        } 
        $widgets['label'] = '';
        $widgets['ListOptions'] = '';
        return $widgets;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function getParentWidgets($user)
    {
        $dashboardWidgets = new DashboardWidgets();
        $widgetsList = $dashboardWidgets->where('user_type','all')->orderby('id','asc')->get();
        // get students list of parent
        $result =DB::table('Parents')
                    ->select('Students.UserId as pivot_StudentId')
                    ->leftJoin('StudentParents', 'Parents.Id', '=', 'StudentParents.ParentId')
                    ->leftJoin('Students', 'Students.Id', '=', 'StudentParents.StudentId')
                    ->where('Parents.UserId', '=', Auth::user()->id)
                    ->lists('pivot_StudentId');

        $result =DB::table('users')
                    ->select('id','FirstName','LastName')
                    ->whereIn('id',$result)
                    ->get();

            $dropdownHtml = '';
            if(!empty($result)){
                $dropdownHtml .= '<select name="students" id="students" class="custom_slct w150">';
                 $dropdownHtml .= '<option value="all">All</option>';
                foreach($result as $row) {
                    $dropdownHtml .= '<option value="'.$row->id.'">'.$row->FirstName.' '.$row->LastName.'</option>';
                }
                $dropdownHtml .= '</select>';
            }
           
        if(!empty($widgetsList)) { 
            foreach($widgetsList as $key=>$row) { 
                //echo '<pre>';print_r($row);
                $data = [];                
               
                $widgetData[] = $this->getWidget($row->id,$data,'');
            }
            //**students don't have dropdowns
            $widgets['dropdown'] = false;
            $widgets['options'] = '';

            //**students might have time alerts
            $widgets['alerts'] = false;

            //**role is fixed
            $widgets['role'] = 'student';

            $widgets['data'] = $widgetData;
          
        }
        $widgets['label'] = 'Students';
        $widgets['ListOptions'] = $dropdownHtml;
        
        return $widgets;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function getInternalTeacherWidgets($user)
    {
        
        $dashboardWidgets = new DashboardWidgets();
        $widgetsList = $dashboardWidgets->where('user_type','all')->orderby('id','asc')->get();

        if(!empty($widgetsList)) { 
            foreach($widgetsList as $key=>$row) { 
                //echo '<pre>';print_r($row);
                $data = [];                
               
                $widgetData[] = $this->getWidget($row->id,$data,'');
            }
            //**students don't have dropdowns
            $widgets['dropdown'] = false;
            $widgets['options'] = '';

            //**students might have time alerts
            $widgets['alerts'] = false;

            //**role is fixed
            $widgets['role'] = 'student';

            $widgets['data'] = $widgetData;
          
        }
        $widgets['label'] = '';
        $widgets['ListOptions'] = '';
        return $widgets;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function getInternalStaffWidgets($user)
    {
        $dashboardWidgets = new DashboardWidgets();
        $widgetsList = $dashboardWidgets->where('user_type','all')->orderby('id','asc')->get();
//dd($widgetsList);
        if(!empty($widgetsList)) { 
            foreach($widgetsList as $key=>$row) { 
                //echo '<pre>';print_r($row);
                $data = [];                


                $widgetData[] = $this->getWidget($row->id,$data,'');

            }
            //**students don't have dropdowns
            $widgets['dropdown'] = false;
            $widgets['options'] = '';

            //**students might have time alerts
            $widgets['alerts'] = false;

            //**role is fixed
            $widgets['role'] = 'student';

            $widgets['data'] = $widgetData;

        }
        $widgets['label'] = '';
        $widgets['ListOptions'] = '';

        return $widgets;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function getExternalStaffWidgets($user)
    {
        
        $dashboardWidgets = new DashboardWidgets();
        $widgetsList = $dashboardWidgets->where('user_type','all')->orderby('id','asc')->get();
        $result = $this->getProgramSchoolSections();
        //dd($result);
        $dropdownHtml = '';
        if(!empty($result)){
            $dropdownHtml .= '<select name="ProgramSchoolSection" id="ProgramSchoolSection" class="custom_sumoSelect2 w200" multiple>';
            foreach($result as $row) {
                $dropdownHtml .= '<option value="'.$row->pssId.'">'.$row->pssName.'</option>';
            }
            $dropdownHtml .= '</select>';
        }
        if(!empty($widgetsList)) { 
            foreach($widgetsList as $key=>$row) { 
                //echo '<pre>';print_r($row);
                $data = [];                
               
                $widgetData[] = $this->getWidget($row->id,$data,'');
            }
            //**students don't have dropdowns
            $widgets['dropdown'] = false;
            $widgets['options'] = '';

            //**students might have time alerts
            $widgets['alerts'] = false;

            //**role is fixed
            $widgets['role'] = 'student';

            $widgets['data'] = $widgetData;
          
        }
        $widgets['label'] = 'School Sections';
        $widgets['ListOptions'] = $dropdownHtml;
        return $widgets;
    }

     /**
     * @param $user
     * @return mixed
     */
    private function getAdminWidgets($user)
    {
        //**has dropdown
        $widgets['dropdown'] = false;
        $widgets['options'] = '';

        //**may have time alert
        $widgets['alerts'] = false;

        //**role is subject to query
        $widgets['role'] = '';

        $widgets['data'] = [];
        $widgets['label'] = '';
        $widgets['ListOptions'] = '';
        return $widgets;
    }
    /**
     * @param $theWidget
     * @param $data
     * @param $theClass
     * @return FusionCharts|array
     */
    private function getWidget($theWidget, $data, $theClass)
    { 
        //**give each widget an id
        //  give each widget alternating class (pull-left or pull-right)
        //  give each widget a partial/template type
        $completeWidget = [];
        //$thisWidget = DashboardWidgets::find($theWidget)->first();
        $thisWidget = DashboardWidgets::where('id',$theWidget)->first();

        if ($thisWidget && $thisWidget->is_fusion_chart) {
            $data = ['Composite'=>420000,'English'=>810000,'Mathematics'=>720000,'Reading'=>550000,'Science'=>910000];

            $completeWidget = $this->processChart($thisWidget, $data);
//dd($completeWidget);
        } elseif ($thisWidget) {

            switch($thisWidget->widget_type) {
                case 'plans':
                    $completeWidget = $this->processPlans($thisWidget, $data);
                    break;
                case 'list' :
                    $completeWidget = $this->processList($thisWidget, $data);
                    break;
                case 'table' :
                    $completeWidget = $this->processTable($thisWidget, $data);
                    break;
                case 'create' :
                    $completeWidget = $this->processCreate($thisWidget, $data);
                    break;
            }

        } else {
            //**something went wrong!
        }

        $completeWidget->template  = $thisWidget->widget_template;
        $completeWidget->div_id    = $thisWidget->widget_div_id;
        $completeWidget->headline  = $thisWidget->widget_headline;
        $completeWidget->text      = $thisWidget->widget_text;
        $completeWidget->class     = $thisWidget->class;
        $completeWidget->hasButton = $thisWidget->has_button;
        $completeWidget->button    = $thisWidget->button_text;
        $completeWidget->link      = $thisWidget->button_link;
        $completeWidget->alternate = $this->modClass($theClass);
//dd($completeWidget);
        return $completeWidget;
    }

    /**
     * @param $theWidget
     * @param $data
     * @return FusionCharts
     */
  

    private function processChart($theWidget, $data) {
        $type = $theWidget->widget_type;
     // dd($type);
        return $this->$type($theWidget, $data);
    }

     private function Column2D($theWidget, $data) { 
        $chartData = '"data": [';
        $i = 0;
        $loopcount = count($data);
        foreach ($data as $label => $value) { $i++;
            $chartData .= '{"label": "' . $label . '",';
            $chartData .= '"value": "' . $value . '"';
            if( $i == $loopcount ) {
                $chartData .= '}';
            } else {
                $chartData .= '},';
            }           
        }

        $chartData .= ']';
        $theChart = new stdClass();

        $theChart = new FusionCharts(
            $theWidget->widget_type, 
            $theWidget->widget_headline,
            '100%', 
            $theWidget->widget_height,
            $theWidget->widget_div_id,
            "json",
            '{
                "chart": {
                    "caption": "",
                    "subCaption": "",
                    "xAxisName": "",
                    "yAxisName": "",
                    "numberPrefix": "$",                   
                    "paletteColors": "' . $theWidget->color_1 . ',' . $theWidget->color_2 . ',' . $theWidget->color_3 . ',' . $theWidget->color_4 . ',' . $theWidget->color_5 . '",
                    "bgColor": "#ffffff",
                    "borderAlpha": "0",
                    "canvasBorderAlpha": "0",
                    "usePlotGradientColor": "0",
                    "plotBorderAlpha": "0",
                    "placeValuesInside": "0",
                    "rotatevalues": "0",
                    "valueFontColor": "#ffffff",
                    "showXAxisValues": "0",
                    "showYAxisValues": "0",
                    "xAxisLineColor": "#999999",
                    "divlineColor": "#FFFFFF",
                    "divLineIsDashed": "0",
                    "showAlternateHGridColor": "0",
                    "subcaptionFontSize": "14",
                    "subcaptionFontBold": "0"
                },
                '.$chartData.'
            }'
        );
        
        return $theChart;
    }

    private function multiaxisline($theWidget, $data) {
        $theChart = new stdClass();

        $theChart = new FusionCharts(
            $theWidget->widget_type, 
            $theWidget->widget_headline,
            '100%', 
            $theWidget->widget_height,
            $theWidget->widget_div_id,
            "json",
            '{
                "chart": {
                    "caption": "",
                    "subcaption": "",
                    "xAxisName": "",                  
                    "showValues": "0",
                    "showXAxisValues": "0",
                    "showYAxisValues": "1",
                    "borderAlpha": "0",
                    "canvasBorderAlpha": "0",
                    "usePlotGradientColor": "0",
                    "plotBorderAlpha": "1",
                    "placeValuesInside": "0",
                    "rotatevalues": "0",
                    "showBorder": "0",
                    "showShadow": "0",
                    "showCanvasBorder": "0",
                    "bgColor": "#ffffff",
                    "canvasBgColor" : "#ffffff",
                    "allowPinMode" : "0"        
                },
                "categories": [
                    {
                        "category": [
                            {
                                "label": ""
                            },
                            {
                                "label": ""
                            },
                            {
                                "label": ""
                            },
                            {
                                "label": ""
                            },
                            {
                                "label": ""
                            }
                        ]
                    }
                ],
                "axis": [
                    {
                        "title": "",
                        "divlineDashed": "0",
                        "dataset": [
                            {
                               
                                "lineThickness": "3",
                                "data": [
                                    {
                                        "value": "137500"
                                    },
                                    {
                                        "value": "124350"
                                    },
                                    {
                                        "value": "156700"
                                    },
                                    {
                                        "value": "131450"
                                    },
                                    {
                                        "value": "208300"
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "title": "",
                        "axisOnLeft": "0",
                        "dataset": [
                            {
                               
                                "data": [
                                    {
                                        "value": "22567"
                                    },
                                    {
                                        "value": "21348"
                                    },
                                    {
                                        "value": "24846"
                                    },
                                    {
                                        "value": "19237"
                                    },
                                    {
                                        "value": "20672"
                                    }
                                ]
                            }
                        ]
                    }                  
                ]
            }'
        );
        
        return $theChart;
    }

    /**
     * @param $theWidget
     * @param $data
     * @return stdClass
     */
    private function processPlans($theWidget, $data)
    {

        $theChart = new stdClass();

            $thePlan ='';
            $thePlan .= '<div class="box">';
            $thePlan .= '<div class="col1">';
            $thePlan .= 'Official Test Date';
            $thePlan .= '</div>';
            if(!empty($data)) {
                $thePlan .= '<div class="col2"><span class="text-large">'.date('M',strtotime($data['upcoming_test_date'])).' '.date('d',strtotime($data['upcoming_test_date'])).'</span>'; 
                $thePlan .= ucfirst(date('l',strtotime($data['upcoming_test_date']))).', '.date('Y',strtotime($data['upcoming_test_date'])).'</div>';    
            }else{
                $thePlan .= '<div class="col2"><span class="text-large">Aug 23</span>'; 
                $thePlan .= 'Friday, 2015</div>';    
            }
            $thePlan .= '</div>';
            $thePlan .= '<div class="upcoming-dates">';
            $thePlan .= '<div class="col1">';
            $thePlan .= ' Most Recent Test';
            if(!empty($data)) {
                $thePlan .= '<strong class="date">'.date('m/d/Y',strtotime($data['most_recent_test_date'])).'</strong>';
            } else {
                $thePlan .= '<strong class="date">09/05/2015</strong>';
            }        
            $thePlan .= '</div>';
            $thePlan .= '<div class="col2">';
            $thePlan .= 'Next Practice Test';
            if(!empty($data)) {
                $thePlan .= '<strong class="date">12/21/2015</strong>';
            }           
            $thePlan .= '</div>';
            $thePlan .= '</div>';            
            $theChart->content = $thePlan;

        return $theChart;
    }

    /**
     * @param $theWidget
     * @param $data
     * @return stdClass
     */
    private function processTable($theWidget, $data)
    {   

        $theChart = new stdClass();
        
        $theTable = '<strong class="title">Name<span class="info">Due Date</span></strong><ul>';
        if(!empty($data)) {

            foreach($data as $row) {
                $theTable .= '<li>';
                $theTable .= '<span class="date">'.date('m/d/Y',strtotime($row->EndDate)).'</span>';
                $theTable .= '<div class="text"><a href="#">'.$row->AssignmentName.'</a></div>';
                $theTable .= '</li>';
            }
        } else {
               //$theTable .= '<li><span class="date"></span><div class="text"><span>No Data Found !</span></div></li>';
                $theTable .= '<li>';
                $theTable .= '<span class="date">03/23/2015</span>';
                $theTable .= '<div class="text"><a href="#">Lorem Ipsum Assignment 01</a></div>';
                $theTable .= '</li>';
                $theTable .= '<li>';
                $theTable .= '<span class="date">03/23/2015</span>';
                $theTable .= '<div class="text">Lorem Ipsum Assignment 02</div>';
                $theTable .= '</li>';
                $theTable .= '<li>';
                $theTable .= '<span class="date">03/23/2015</span>';
                $theTable .= '<div class="text">Lorem Ipsum Assignment 03</div>';
                $theTable .= '</li>';
                $theTable .= '<li>';
                $theTable .= '<span class="date">03/23/2015</span>';
                $theTable .= '<div class="text"><a href="#">Lorem Ipsum Assignment 04</a></div>';
                $theTable .= ' </li>';
                $theTable .= '<li>';
                $theTable .= '<span class="date">03/23/2015</span>';
                $theTable .= '<div class="text"><a href="#">Lorem Ipsum Assignment 05</a></div>';
                $theTable .= '</li>';
                $theTable .= '<li>';
                $theTable .= '<span class="date">03/23/2015</span>';
                $theTable .= ' <div class="text">Lorem Ipsum Assignment 06</div>';
                $theTable .= '</li>';
        }
        $theTable .= '</ul>';
        $theChart->content = $theTable;

        // dd($theChart);
        return $theChart;
    }

    /**
     * @param $theWidget
     * @param $data
     * @return stdClass
     */
    private function processList($theWidget, $data)
    {

        $theChart = new stdClass();
            $theTable  = '<strong class="title">Name</strong>';
            $theTable .= '<ul>';
            $theTable .= '<li><a href="#">Lorem Ipsum Assignment 01</a></li>';
            $theTable .= '<li>Lorem Ipsum Assignment 02</li>';
            $theTable .= '<li>Lorem Ipsum Assignment 03</li>';
            $theTable .= '<li><a href="#">Lorem Ipsum Assignment 04</a></li>';
            $theTable .= '<li><a href="#">Lorem Ipsum Assignment 05</a></li>';
            $theTable .= '<li>Lorem Ipsum Assignment 06</li>';
            $theTable .= '<li>Lorem Ipsum Assignment 06</li>';
            $theTable .= '</ul>';

        $theChart->content = $theTable;

        return $theChart;
    }

    /**
     * @param $theWidget
     * @param $data
     * @return stdClass
     */
    private function processCreate($theWidget, $data)
    {

        $theChart = new stdClass();

        return $theChart;
    }

    /**
     * @param $startTime
     * @return mixed
     */
    private function timeAlert($startTime)
    {
        $theAlert = calc($startTime);

        return $theAlert;

    }

    /**
     * @param $widgetCount
     * @return string
     */
    public function modClass($widgetCount)
    {

        $theClass = ( $widgetCount % 2 ) == 0 ? 'pull-right' : 'pull-left';

        return $theClass;
    }

    public function getProgramSchoolSections($parms = null, $status =  'Active') {

        $query = Program::join('ProgramResources AS pr', 'pr.ProgramId', '=', 'Programs.Id')
                ->join('ProgramSchoolSections AS pss', 'pss.Id', '=', 'pr.ResourceId')
                ->join('Options AS opst', 'opst.Id', '=', 'pss.StatusId')
                ->leftjoin('Options AS o', 'o.Id', '=', 'pss.SubjectId')
                ->join('users AS u', 'u.id', '=', 'pss.TeacherId')
                ->leftJoin('Options as ops', 'ops.Id', '=', 'pss.StatusId')
                ->select('Programs.Id AS pId', 'pss.Id AS pssId', 'pss.Name AS pssName', 'pss.Description AS pssDescription', 'u.name AS pssTeacherName', 'o.Display AS pssSubject', 'ops.Display AS Status');

        if ($status == 'Active' || isset($parms['statusActive'])) {
            $query->where('opst.Display', '=', 'Active');
        }
        if (!empty($parms['mySections'])) {
            $query->where('pss.TeacherId', '=', Auth::user()->id);
        }
        $query->whereNull('pss.deleted_at');
        //$query->where('Programs.Id', '=', $parms['programId']);
        $query->where('pr.Resource_type', '=', 'App\Modules\Programs\Models\ProgramSchoolSection');
        if (isset($parms['all'])) {

            $result = $query->get();
            return $result;
        }

        if (!empty($parms['status'])) {
//            if (!empty($parms['mySections'])) {
//                $query->where(function($query)use ($parms) {
//                    $query->where('pss.StatusId', '=', $parms['status']);
//                    $query->orWhere('pss.TeacherId', '=', Auth::user()->id);
//                });
//            } else {
            $query->where('pss.StatusId', '=', $parms['status']);
//            }
        }

        $toTake = 5;
        $toSkip = isset($parms['skip']) ? $parms['skip'] : 0;

        // After two passes (which means when first six records have been skipped)
        // We need to take the 9 records for each of the request
//        if ($toSkip >= 10) {
//            $toTake = 9;
//        }
        //dd($parms['limit']);
        if (isset($parms['limit'])){
            $toTake = $parms['limit'];
        }
        $query->whereNull('pss.deleted_at');
        $result = $query->orderBy('pss.Name','asc')->take($toTake)->skip($toSkip)->get();
       
        return $result;
    }

}
