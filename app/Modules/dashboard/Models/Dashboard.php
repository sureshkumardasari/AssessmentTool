<?php

namespace App\Modules\dashboard\Models;

// use App\Models\Group;
// use App\Modules\Accounts\Models\Classes;
// use App\Modules\Accounts\Models\Program;
// use App\Modules\Accounts\Models\Section;
// use App\Modules\Accounts\Models\Student;
// use App\Modules\Accounts\Models\Institution;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Modules\Resources\Models\File;

use App\Models\Option;
use App\Models\State;
use App\Modules\Accounts\Models\Student;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentAssignmentUser;
use App\Modules\Assessment\Models\AssessmentAssignment;

use Zizaco\Entrust\EntrustFacade;
use App\Modules\Programs\Models\Program;
use App\Modules\Programs\Models\ProgramSectionUser;

use stdClass;
use DB;

/**
 * Assignment Model
 * 
 * Hoses all the business logic related to the resource entity.
 */
class Dashboard extends Model {

    //getting assigmnments
    public static function getAssignments($userType, $userID){
        $deliveryId = Option::getOptionId('Delivery_Method', 'Online');
        
        $query = DB::table('Assessments')
        ->select('Assessments.Title as title', 'AssessmentAssignment.StartDate as date', 'AssessmentAssignment.Id as AssessmentAssignmentId', 'Assessments.Id as assessmentID')
        ->join('AssessmentAssignment', 'Assessments.Id', '=', 'AssessmentAssignment.assessmentId')
        ->join('AssessmentAssignmentUsers', 'AssessmentAssignment.Id', '=', 'AssessmentAssignmentUsers.AssignmentId')
        ->where(DB::raw(' "Assessments"."CategoryId" IN (select DISTINCT "Assessments"."CategoryId" FROM "Assessments") AND "Assessments"."TestTypeId" IN (select DISTINCT "Assessments"."TestTypeId" FROM "Assessments" ) and "AssessmentAssignmentUsers"."UserId" = '.$userID.' and "AssessmentAssignmentUsers"."Type" = \'User\' and "AssessmentAssignmentUsers"."Status" <> \'Complete\' and "AssessmentAssignment"."DeliveryMethodId" = \''.$deliveryId.'\' AND "Assessments"."deleted_at" '))->get(); //lists('title', 'date');
       //dd($query);
    }

    //getting upcomming evetns/assessments of specific userType
    public static function getUpcommingEvents($userType, $userID){
        $upevt_arr = array();
            
        if($userType=='student' || $userType=='parent'){
            $assessment = new Assessment();
            $tests      = Dashboard::getTests($userID);
            foreach($tests as $k=>$v){
                //echo "<br>"; print_r($v);
                $upevt_arr[] = array($v->Name, $v->Id, $v->StartDate, 'assessment');
            }
            //dd($tests);
            $lessons = Dashboard::getAssignLessons($userID);
            // echo "--->".count($lessons);
            foreach($lessons as $k=>$v){
                //echo "<br>"; print_r($v);
                $upevt_arr[] = array($v->Name, $v->assignmentId, $v->StartDate, 'lessions');
            }
            //dd($lessons);
            //dd($tests);
            // $query = DB::table('Assessments')
            // ->select('Assessments.Title as title', 'AssessmentAssignment.StartDate as date', 'AssessmentAssignment.Id as AssessmentAssignmentId', 'Assessments.Id as assessmentID')
            // ->join('AssessmentAssignment', 'Assessments.Id', '=', 'AssessmentAssignment.assessmentId')
            // ->join('AssessmentAssignmentUsers', 'AssessmentAssignment.Id', '=', 'AssessmentAssignmentUsers.AssignmentId')
            // ->where(DB::raw(' "Assessments"."CategoryId" IN (select DISTINCT "Assessments"."CategoryId" FROM "Assessments") AND "Assessments"."TestTypeId" IN (select DISTINCT "Assessments"."TestTypeId" FROM "Assessments" ) and ("AssessmentAssignment"."StartDate" + "AssessmentAssignment"."StartTime" > \''.date('Y-m-d H:i:s').'\') and "AssessmentAssignmentUsers"."UserId" = '.$userID.' and "AssessmentAssignmentUsers"."Type" = \'User\' and "AssessmentAssignmentUsers"."Status" <> \'Complete\' and "AssessmentAssignment"."DeliveryMethodId" = \'489\' AND "Assessments"."deleted_at" '))->get(); 
            // dd($query);
            //lists('title', 'date');
        }else {
           
            $res = Dashboard::getAssessmentAssignments($userID);
            //dd($res);

        }
        return $upevt_arr;
       //dd($query);
    }

    //------------------------------------------------------------------
    /*
     * get all the lesson that is assigned to student 
     * @return array of lessons;
     */
    //------------------------------------------------------------------
    public static function getAssignLessons($userID){
       $query = DB::table('users as u')
          ->join('Students as s', 'u.id','=','s.UserId' )
          ->join('AssignmentStudents as testApp', function($join){
              $join->on('testApp.UserId', '=', 'u.id');
             })
          ->join('Assignments as a','a.Id','=', 'testApp.AssignmentId')
          ->join('ResourceAssignments as ra', function($join){
              $join->on('a.Id', '=', 'ra.AssignmentId');
              $join->on('ra.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\ResourceTestType'"));
            })
          ->join('ResourceTestTypes as rt', function($join){
              $join->on('rt.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\Lesson'"));
              $join->on('rt.Id', '=', 'ra.ResourceId');
            })
          ->join('Lessons as l ',function($join){ 
              $join->on('rt.ResourceId', '=', 'l.Id');
             })
          ->join('Options as testType', 'rt.TestTypeId', '=', 'testType.Id')
          ->join('Options as subj', 'rt.SubjectId', '=', 'subj.Id')
          ->join('Options as topic', 'rt.TopicId', '=', 'topic.Id') 
          ->select(
              'l.Name as lesson_name',
              'l.Id as lesson_id',
              'l.Description as lesson_description',
              'testType.Display as test_type_name',
              'testType.Id as test_type_id',
              'topic.Display as topic_name',
              'topic.Id as topic_id',
              'a.Name as Name', 
              'a.Id as assignmentId', 
              'a.StartDate')->orderBy('a.StartDate');
          $query->where('a.StartDate','>',date('Y-m-d'))->where('a.EndDate','>=',date('Y-m-d'));
          $query->where('u.id','=',$userID);
          return $query->get();
    }

    public static function getTests($userID) {        

        $deliveryId = Option::getOptionId('Delivery_Method', 'Online');
        // available tests
         $data = array();
         // $data['Available'] = Assessment::join('AssessmentAssignment', 'Assessments.Id', '=', 'AssessmentAssignment.assessmentId')
         //                                ->join('AssessmentAssignmentUsers', 'AssessmentAssignment.Id', '=', 'AssessmentAssignmentUsers.AssignmentId')
         //                                ->whereRaw(DB::raw(' "Assessments"."CategoryId" IN (select DISTINCT "Assessments"."CategoryId" FROM "Assessments") AND "Assessments"."TestTypeId" IN (select DISTINCT "Assessments"."TestTypeId" FROM "Assessments" ) '))
         //                                ->whereRaw('(("AssessmentAssignment"."StartDate" + "AssessmentAssignment"."StartTime" <= \''. date('Y-m-d H:i:s') .'\'')
         //                                ->whereRaw('"AssessmentAssignment"."EndDate" + "AssessmentAssignment"."EndTime" >= \''. date('Y-m-d H:i:s') .'\' AND "AssessmentAssignment"."Expires" = TRUE) OR ("AssessmentAssignment"."StartDate" + "AssessmentAssignment"."StartTime" > \''. date('Y-m-d H:i:s') .'\' AND ("AssessmentAssignment"."Expires" = FALSE OR "AssessmentAssignment"."Expires" IS NULL)))')
         //                                ->where('AssessmentAssignmentUsers.UserId', $userID)
         //                                ->where('AssessmentAssignmentUsers.Type', 'User')
         //                                ->where('AssessmentAssignmentUsers.Status', '<>', 'Complete')
         //                                ->where('AssessmentAssignment.DeliveryMethodId', $deliveryId)
         //                                ->get();
         //                               //dd($data['Available']); 
        // upcoming tests
        //$data = Assessment::
        $data = DB::table('Assessments')
                ->join('AssessmentAssignment', 'Assessments.Id', '=', 'AssessmentAssignment.assessmentId')
                                        ->join('AssessmentAssignmentUsers', 'AssessmentAssignment.Id', '=', 'AssessmentAssignmentUsers.AssignmentId')
                                         ->whereRaw(DB::raw(' "Assessments"."CategoryId" IN (select DISTINCT "Assessments"."CategoryId" FROM "Assessments") AND "Assessments"."TestTypeId" IN (select DISTINCT "Assessments"."TestTypeId" FROM "Assessments" ) '))
                                        ->whereRaw('("AssessmentAssignment"."StartDate" + "AssessmentAssignment"."StartTime" > \''. date('Y-m-d H:i:s') .'\')') 
                                        ->where('AssessmentAssignmentUsers.UserId', $userID)
                                        ->where('AssessmentAssignmentUsers.Type', 'User')
                                        ->where('AssessmentAssignmentUsers.Status', '<>', 'Complete')
                                        ->where('AssessmentAssignment.DeliveryMethodId', $deliveryId)
                                        ->select('AssessmentAssignment.Name', 'AssessmentAssignment.StartDate', 'AssessmentAssignment.StartTime', 'AssessmentAssignment.Id')
                                        ->get();       
        
        return $data;
    }

    /**
     * fetchClassForSearchBy | it will fetch Classes(Graduation Years)  When SearchBy is Class
     * @param array $params
     * @return mixed | boolean/Collection of Result
     */
    public static function getAssessmentAssignments($userID, $ids = null){       

        // dd($ids);
        // $parsed1 = date_parse('16:44:00');
        // $startTime1 = $parsed1['hour'] * 3600 + $parsed1['minute'] * 60 + $parsed1['second'];
        // $startTime1 =$startTime;

        $parsed = date_parse(date('H:i:s'));
        $startTime = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
        $startTime =$startTime + 900;
        $startTime = date('H:i:s', $startTime);
        $nowDate = date('Y-m-d');

        // $query = AssessmentAssignment::join('Assessments AS a', 'a.Id', '=', 'AssessmentAssignment.assessmentId')
        //                 ->join('AssessmentAssignmentUsers AS aau', 'aau.AssignmentId', '=', 'AssessmentAssignment.Id')
        //                 ->join('Options AS o', 'o.Id', '=', 'AssessmentAssignment.LaunchTypeId')
        //                 ->join('Options AS om', 'om.Id', '=', 'AssessmentAssignment.DeliveryMethodId')
        //                 ->select(
        //                         'AssessmentAssignment.Id AS AssessmentAssignmentId',
        //                         'AssessmentAssignment.Name AS AssessmentName',
        //                         'a.Title AS AssignmentAssessmentName',
        //                         'AssessmentAssignment.StartDate AS AssessmentStartDate',
        //                         'AssessmentAssignment.EndDate AS AssessmentEndDate',
        //                         'AssessmentAssignment.StartTime AS AssessmentStartTime',
        //                         'AssessmentAssignment.EndTime AS AssessmentEndTime',
        //                         'AssessmentAssignment.Expires AS Expires');
        // $query->whereNull('AssessmentAssignment.deleted_at');
        // $query->whereNull('a.deleted_at');
        // $query->where('aau.UserId', '=', $userID);
        // $query->where(function($q){
        //     $q->whereRaw(\DB::raw('("AssessmentAssignment"."StartDate" + "AssessmentAssignment"."StartTime" < \''. date('Y-m-d H:i:s') .'\' AND "AssessmentAssignment"."EndDate" + "AssessmentAssignment"."EndTime" > \''. date('Y-m-d H:i:s') .'\')   OR ("AssessmentAssignment"."StartDate" + "AssessmentAssignment"."StartTime" < \''. date('Y-m-d H:i:s') .'\' AND "AssessmentAssignment"."Expires" = FALSE)'));
        // });
        // // $query->orWhere('AssessmentAssignment.StartTime', '<=', date('H:i:s'));
        // $query->where('aau.Type', '=', 'Proctor');
        // $query->where('o.Option', '=', 'Proctor Launches Test');
        // $query->where('om.Option', '=', 'Online');

        // if (!empty( $ids )) {
        //     $query->whereNotIn('AssessmentAssignment.Id', $ids);
        // }

        // $query->whereIn('AssessmentAssignment.Status', ['Upcoming', 'Instructions','Test'])->orderBy('AssessmentAssignment.StartTime', 'ASC');

        // $result['Available'] = $query->get();

        $query = AssessmentAssignment::join('Assessments AS a', 'a.Id', '=', 'AssessmentAssignment.assessmentId')
                        ->join('AssessmentAssignmentUsers AS aau', 'aau.AssignmentId', '=', 'AssessmentAssignment.Id')
                        ->join('Options AS o', 'o.Id', '=', 'AssessmentAssignment.LaunchTypeId')
                        ->join('Options AS om', 'om.Id', '=', 'AssessmentAssignment.DeliveryMethodId')
                        ->select(
                                'AssessmentAssignment.Id AS AssessmentAssignmentId',
                                'AssessmentAssignment.Name AS AssessmentName',
                                'a.Title AS AssignmentAssessmentName',
                                'AssessmentAssignment.StartDate AS AssessmentStartDate',
                                'AssessmentAssignment.EndDate AS AssessmentEndDate',
                                'AssessmentAssignment.StartTime AS AssessmentStartTime',
                                'AssessmentAssignment.EndTime AS AssessmentEndTime',
                                'AssessmentAssignment.Expires AS Expires');
        $query->whereNull('AssessmentAssignment.deleted_at');
        $query->whereNull('a.deleted_at');
        $query->where('aau.UserId', '=', $userID);
        $query->where(function($q){
            $q->where('AssessmentAssignment.EndDate', '>=', date('Y-m-d'));
            $q->orWhere('AssessmentAssignment.Expires', '=', false);
        });
        // $query->Where('AssessmentAssignment.StartDate', '>=', date('Y-m-d'));
        // $query->Where('AssessmentAssignment.StartTime', '>', date('H:i:s'));
        $query->whereRaw(\DB::raw('("AssessmentAssignment"."StartDate" + "AssessmentAssignment"."StartTime" > \''. date('Y-m-d H:i:s') .'\')'));
            $query->where('aau.Type', '=', 'Proctor');
        $query->where('o.Option', '=', 'Proctor Launches Test');
        $query->where('om.Option', '=', 'Online');

        if (!empty( $ids )) {
            $query->whereNotIn('AssessmentAssignment.Id', $ids);
        }

        $query->whereIn('AssessmentAssignment.Status', ['Upcoming', 'Instructions','Test'])->orderBy('AssessmentAssignment.StartTime', 'ASC');
        //dd($query->toSql());
        $result['Current'] = $query->get();

        return $result;
    }
  
    public static function getFileAssignmentsForDashbaord($filters = null) {

        $query = DB::table('Assignments')
                ->leftJoin('ResourceAssignments', 'Assignments.Id', '=', 'ResourceAssignments.AssignmentId')
                ->leftJoin('Files', function($join) {
                    $join->on('ResourceAssignments.ResourceId', '=', 'Files.Id');
                    $join->on('ResourceAssignments.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                })
                ->leftJoin('ResourceTestTypes', function( $join ) {
                    $join->on('ResourceTestTypes.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                    $join->on('Files.Id', '=', 'ResourceTestTypes.ResourceId');
                })
                ->leftJoin('ResourceStandardSystems as standSys', function ( $join) {
                    $join->on('standSys.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                    $join->on('standSys.ResourceId', '=', 'Files.Id');
                })
                ->leftJoin('ResourceTestApplicability as testApp', function ( $join) {
                    $join->on('testApp.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                    $join->on('testApp.ResourceId', '=', 'Files.Id');
                })
                ->leftJoin('ResourceCurriculumCategories as currCat', function ( $join) {
            $join->on('currCat.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
            $join->on('currCat.ResourceId', '=', 'Files.Id');
        });

        $query->select('Assignments.Id as AssignmentId', 'Assignments.Name as AssignmentName', 'Assignments.StartDate', 'Assignments.EndDate', 'Files.Name as FileName');

        $query->where('Assignments.Types', '=', 'file');
        $query->where('Assignments.deleted_at', '=', NULL)->groupBy('Assignments.Id', 'Files.Name');

        //apply search filter
        if ($filters) {
            if ($filters['searchTxt']) {
                $query->where(function( $q)use( $filters ) {
                    $q->where('Files.Name', 'ILIKE', '%' . $filters['searchTxt'] . '%');
                    $q->orWhere('Assignments.Name', 'ILIKE', '%' . $filters['searchTxt'] . '%');
                });
            }

            //apply status i.e. Active or Inactive
            if ($filters['filterStatusId'] != -1) {
                if ($filters['filterStatusId'] == 1) {
                    // for active                                       
                    $query->where('Assignments.EndDate', '>=', date('Y,m,d'));
                } else if ($filters['filterStatusId'] == 2) {
                    // for expired                    
                    $query->where('Assignments.EndDate', '<', date('Y,m,d'));
                }
            }

            //apply type file i.e. ACT, SAT etc
            if ($filters['filterTypeId'] != -1) {
                $query->where('ResourceTestTypes.TestTypeId', '=', $filters['filterTypeId']);
            }

            //  if( $filters['instituteId']!=-1 && $filters['instituteId']!=0){
            //     $query->where( 'Institutions.Id', '=', $filters['instituteId'] );
            // }  
            //apply lesson assignment name 
            if ($filters['lessonAssignmentId'] != -1 && $filters['lessonAssignmentId'] != 0) {
                $query->where('Assignments.Id', '=', $filters['lessonAssignmentId']);
            }
            if (isset($filters['standardSystem']) && !empty($filters['standardSystem'])) {
                $query->whereIn('standSys.StandardSystemId', $filters['standardSystem']);
            }

            if (isset($filters['testApplicability']) && !empty($filters['testApplicability'])) {
                $query->whereIn('testApp.TestApplicabilityId', $filters['testApplicability']);
            }

            if (isset($filters['curriculumCategory']) && !empty($filters['curriculumCategory'])) {
                $query->whereIn('currCat.CurriculumCategoryId', $filters['curriculumCategory']);
            }

            //sort the data by given field and sort order .i.e. Assignment.StartDate->ASC
            if ($filters['sortBy'] != '') {
                $query->orderby('Assignments.' . $filters['sortBy'], $filters['sortTo']);
            } else {
                $query->orderby('Assignments.Id', 'ASC');
            }
            // get the displayed items number and get the total number of records
            $toTake = 10 + $filters['toSkip'];
            $toSkip = isset($filters['toSkip']) ? $filters['toSkip'] : 0;

            // After two passes (which means when first ten records have been skipped)
            // We need to take the 18 records for each of the request
            if ($toSkip >= 20) {
                $toTake = 18;
            }

            $assigmentsList = $query->take($toTake)->skip($toSkip)->get();
        } else {
            $assigmentsList = $query->paginate(10);
        }


        return $assigmentsList;
    }

    /**
     * Code by Srinivas @10-03-2016
     * method for get recent act test
     * @return array
     */
    public static function getMostRecentTestList($testType, $userID,$limit=1,$schoolSectionId=[],$programIds=[])
    {
//        $user = Auth::user();
//        $user->id=131874;
        //get school section users
//        print_r($schoolSectionId);
        if(count($schoolSectionId)){
            $schoolSectionUsers=DB::table("ProgramSectionUsers")
                ->select('UserId')
                ->where('Resource_type','App\Modules\Programs\Models\ProgramSchoolSection')
                ->whereIn('ResourceId',$schoolSectionId)
                ->get();
            foreach($schoolSectionUsers as $user){
                $userArray[]=$user->UserId;

            }

        }else if($programIds){
            $programUsers=DB::table("users as u")
                ->select('u.id')
                ->join('ProgramRosters as pr','u.id','=','pr.UserId')
                ->join('Programs as pro','pr.ProgramId',"=",'pro.Id')
                ->whereIn('pro.Id',$programIds)
                ->where('u.ProfileType','Student')
                ->whereNull('u.deleted_at')
                ->get();
            foreach($programUsers as $user){
                $userArray[]=$user->id;
            }
        }
        else{
            $userArray=[$userID];
        }
        $userIdsStr=implode(",",$userArray);
        //get latest act test
        $resentTests=DB::table("AssessmentAssignmentUsers as aau")
            ->select("AssignmentId")
            ->join("AssessmentAssignment as aa","aa.Id","=","aau.AssignmentId")
            ->join("Assessments as a","a.Id","=","aa.assessmentId")
            ->join("Options as op","op.Id","=","a.TestTypeId")
            ->whereIn("UserId",$userArray)
            ->where("op.Id",$testType)
            ->where("aau.Status","Complete")
            ->orderby("aau.Id","DESC")
            ->limit($limit)
            ->get();
        $resentTests=array_map("unserialize", array_unique(array_map("serialize", $resentTests)));
        if(count($resentTests)>0) {
            $da = [];
            foreach ($resentTests as $resentTest) {
                //getting sections

                $sectionIds = DB::table('AssignmentSubsections as asub')
                    ->join('Subsections as sub', 'asub.SubsectionId', '=', 'sub.Id')
                    ->join('Options as op', "op.Id", "=", "sub.SubjectId")
                    ->where('asub.AssignmentId', '=', $resentTest->AssignmentId)
                    ->selectRaw('
                CASE
                    WHEN sub."ParentId" IS NULL THEN sub."Id"
                    ELSE sub."ParentId"
                END AS "SectionId",
                sub."SubjectId",
                op."Display"
            ')->get();

                $tempSections = [];
                foreach ($sectionIds as $section) {
                    $tempSections[strtolower(str_replace(" ", "_", $section->Display))] = $section->SectionId;
                }

            $sectionIds = $tempSections;

            $subjSql = '';
            $whereScore = '';
            $selectScore = '';
            $subjColumns = ['subject_composite'];

            // For composite subject score. Since it does not exist in
            // database and SectionId is not going to be here, adding it here
            $subjSql = '(
              SELECT SUM("Score")

              FROM "UserAssessmentAssignmentResults" as usaasgmtr2
              WHERE usaasgmtr2."AssignmentId" = ' . $resentTest->AssignmentId . '
              AND usaasgmtr2."SectionId" IS NULL
              AND usaasgmtr2."UserId" = uassr."UserId"
          ) AS composite';

            $selectScore .= 'round(tempResult."composite") as composite, ';

            // Creating the subjects select
            foreach ($sectionIds as $subjectId => $sectionId) {
                $subjSql .= ',(
                    SELECT round(SUM("Score"),0)
                    FROM "UserAssessmentAssignmentResults" as usaasgmtr2
                    WHERE usaasgmtr2."AssignmentId" = ' . $resentTest->AssignmentId . '
                    AND usaasgmtr2."SectionId" IN (' . $sectionId . ')
                    AND usaasgmtr2."UserId" = uassr."UserId"
                ) AS ' . $subjectId;

                $whereScore .= 'tempResult."' . $subjectId . '" IS NOT NULL OR ';
                $selectScore .= 'tempResult."' . $subjectId . '", ';

                $subjColumns[] = '' . $subjectId;
            }
            $selectScore = trim($selectScore, ', ');
//        dd($selectScore);
            // Removing the last OR part from $whereScore
            $whereScore = trim($whereScore);
            $parts = explode('OR', $whereScore);
            unset($parts[count($parts) - 1]);

            $whereScore = implode(' OR ', $parts);

            $additionalJoins = '';
            $sorter = 'ORDER BY composite desc';

            $sql = 'SELECT

                ' . $selectScore . '
                 FROM
                    (
                        SELECT


                                ' . $subjSql . '

                          FROM     "UserAssessmentAssignmentResults" uassr
                              ' . $additionalJoins . '
                              INNER JOIN "users" as u ON u."id" = uassr."UserId"
                              LEFT JOIN "UserGroups" as ug ON ug."UserId" = u."id"
                          WHERE
                              uassr."AssignmentId" = ' . $resentTest->AssignmentId . '
                              AND u."id" in(' . $userIdsStr . ')
                          GROUP BY
                              u."id",
                              uassr."UserId"
                    ) AS tempResult ' . $sorter;
                $results = DB::select(DB::raw($sql));
                $output = array();
                foreach ($results as $key => $record) {
                    foreach ($record as $rkey => $value) {
                        if (!isset($output[$rkey])) {
                            $output[$rkey] = $value;
                        } else {
                            $output[$rkey] += $value;
                        }
                    }
                }
                $da[]=$output;
            }
            return $da;

        }else{
            return "NoTestsFound";
        }
    }


    public static function getLessonAssignments($filters = null) { 
        $query = DB::table('Assignments')
                ->leftJoin('ResourceAssignments', 'Assignments.Id', '=', 'ResourceAssignments.AssignmentId')
                ->leftJoin('ResourceTestTypes', function( $join ) {
                    $join->on('ResourceAssignments.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\ResourceTestType'"));
                    $join->on('ResourceAssignments.ResourceId', '=', 'ResourceTestTypes.Id');
                })
                ->leftJoin('Lessons', 'ResourceTestTypes.ResourceId', '=', 'Lessons.Id')
                ->leftJoin('ResourceStandardSystems as standSys', function ( $join) {
                    $join->on('standSys.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\Lesson'"));
                    $join->on('standSys.ResourceId', '=', 'Lessons.Id');
                })
                ->leftJoin('ResourceTestApplicability as testApp', function ( $join) {
                    $join->on('testApp.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\Lesson'"));
                    $join->on('testApp.ResourceId', '=', 'Lessons.Id');
                })
                ->leftJoin('ResourceCurriculumCategories as currCat', function ( $join) {
            $join->on('currCat.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\Lesson'"));
            $join->on('currCat.ResourceId', '=', 'Lessons.Id');
        });

        $query->select('Assignments.Id as AssignmentId', 'Assignments.Name as AssignmentName', 'Assignments.StartDate', 'Assignments.EndDate', 'ResourceAssignments.Id as ResourceAssignmentId', 'ResourceTestTypes.Id as ResourceTestTypeId', 'ResourceTestTypes.TestTypeId', 'Lessons.Name as LessonName', 'Lessons.Id as LessonId');
        $query->where('Assignments.Types', '=', 'lesson');
        $assigmentsList = $query->where('Assignments.deleted_at', '=', NULL)->groupBy('Lessons.Id', 'Assignments.Id', 'ResourceAssignments.Id', 'ResourceTestTypes.Id');

        //apply search filter
        if ($filters['searchTxt']) {
            $query->where(function( $q)use( $filters ) {
                $q->where('Lessons.Name', 'ILIKE', '%' . $filters['searchTxt'] . '%');
                $q->orWhere('Assignments.Name', 'ILIKE', '%' . $filters['searchTxt'] . '%');
            });
        }

        //apply status i.e. Active or Inactive

        if ($filters['filterStatusId'] == 1) {
            // for active                                       
            $query->where('Assignments.EndDate', '>=', date('Y,m,d'));
        } else if ($filters['filterStatusId'] == 2) {
            // for expired                    
            $query->where('Assignments.EndDate', '<', date('Y,m,d'));
        }


        if ($filters['filterTypeId'] != -1 && $filters['filterTypeId'] != 0) {
            $query->where('ResourceTestTypes.TestTypeId', '=', $filters['filterTypeId']);
        }

        //apply institute name i.e. ACT, SAT etc
        // if( $filters['instituteId']!=-1 && $filters['instituteId']!=0){
        //     $query->where( 'Institutions.Id', '=', $filters['instituteId'] );
        // }          
        //apply lesson name 
        if ($filters['lessonId'] != -1 && $filters['lessonId'] != 0) {
            $query->where('Lessons.Id', '=', $filters['lessonId']);
        }
        //apply lesson assignment name 
        if ($filters['lessonAssignmentId'] != -1 && $filters['lessonAssignmentId'] != 0) {
            $query->where('Assignments.Id', '=', $filters['lessonAssignmentId']);
        }

        if (isset($filters['standardSystem']) && !empty($filters['standardSystem'])) {
            $query->whereIn('standSys.StandardSystemId', $filters['standardSystem']);
        }

        if (isset($filters['testApplicability']) && !empty($filters['testApplicability'])) {
            $query->whereIn('testApp.TestApplicabilityId', $filters['testApplicability']);
        }

        if (isset($filters['curriculumCategory']) && !empty($filters['curriculumCategory'])) {
            $query->whereIn('currCat.CurriculumCategoryId', $filters['curriculumCategory']);
        }

        //sort the data by given field and sort order .i.e. Assignment.StartDate->ASC
        if ($filters['sortBy'] != '') {
            $query->orderby('Assignments.' . $filters['sortBy'], $filters['sortTo']);
        } else {
            $query->orderby('Assignments.Id', 'ASC');
        }

        // get the displayed items number and get the total number of records
        $toTake = 10 + $filters['toSkip'];
        // $toSkip = isset(  $filters['toSkip'] ) ? $filters['toSkip']: 0;
        // After two passes (which means when first ten records have been skipped)
        // We need to take the 18 records for each of the request
        // if ( $toSkip >= 20 ) {
        //     $toTake = 18;
        // }

        $assigmentsList = $query->take($toTake)->skip(0)->get();
        return $assigmentsList;
    }
    public static function getProgramSchoolSections($parmsT = null, $sortBy =  'asc',$skip='',$limit='') {
        $myPrograms = [];
        $programId = 0;
        if ((Auth::user()->ProfileType == E_STAFF || Auth::user()->ProfileType == TEACHER)) {
            
            if(Auth::user()->ProfileType == E_STAFF){
                $myPrograms = Program::getMyProgram();
            }else{
                $myPrograms = Program::getInternalTeacherPrograms();
            }    
            
            $myPrograms = array_keys($myPrograms);       
            
            if (empty($myPrograms)) {
              if (Session::has('programId')) {
                  $programId = Session::get('programId');
              }
              $myPrograms[] = $programId;
            }
        }
        if($parmsT==null){
          $parms['programId'] = $myPrograms;
          $parms['all'] = 'all';
        }else{
          $parms['programId'] = $parmsT;
          $parms['all'] = 'all';
        }
        $para = [];
        $para['sortBy'] = $sortBy;
        $para['skip'] = $skip;
        $para['limit'] = $limit;
//        dd($para);
        $programsSchoolSections = [];
        if(Auth::user()->ProfileType == E_STAFF){
          $para['all'] = 'all';
          foreach ($myPrograms as $programId) {
              $para['programId'] = $programId;
              $result = Program::getExternalUserProgramSections($para, 'Active');
              foreach ($result as $row) {
                $programsSchoolSections[$row->pssId] = $row->pssName;
              }
          }          
        }else{
          $result = Program::getProgramSchoolSections($parms, 'Active');
          foreach ($result as $row) {
            $programsSchoolSections[$row->pssId] = $row->pssName;
          }
        }
        
        return $programsSchoolSections;
    }

    /**
     * This method queries database for program roster teachers considering the program passed
     */
    public static function getProgramRosterTeachers($filters = array()) {
        $rosterTeachers = DB::table('Programs')
                ->join('ProgramRosters as programRosters', 'programRosters.ProgramId', '=', 'Programs.Id')
                ->join('users as users', function ($join) {
                    $join->on('users.id', '=', 'programRosters.UserId')
                    ->where('users.ProfileType', '=', 'External Staff')
                    ->whereNull('users.deleted_at')
                    ->where('users.Status', '=', 'Active');
                })
                ->leftjoin('Options as options', function ($join) {
                    $join->on('options.Id', '=', 'users.JobId')
                    ->where('options.Type', '=', 'JobTitles');
                })
                ->leftjoin('UserSubjects as userSubjects', 'userSubjects.UserId', '=', 'users.id')
                ->leftjoin('Subjects as subjects', 'userSubjects.SubjectId', '=', 'subjects.Id')
                ->whereRaw(DB::raw(' "Programs"."Id" IN ('.implode(',', $filters).')'))
                ->whereNull('Programs.deleted_at');

        $rosterTeachers->groupBy('users.id', 'users.FirstName', 'users.LastName', 'users.email', 'users.UserName', 'options.Display');
        $rosterTeachers->select(DB::raw("CONCAT(\"FirstName\", ' ', \"LastName\") AS name"), 'users.id as userId');
        //$rosterTeachers->lists('userId', 'name');
         $teachers = $rosterTeachers->get();
        return $teachers;
        
    }

    public static function getRecent_Grades_old($user_type = 'all', $userID = 0)
    {
      $res = [];

      //$schoolsectionusers = [];
      //$schoolsectionusers = '149568,149441,149636';

      $myschoolsections_info = Dashboard::getProgramSchoolSections();
      //$myschoolsections = array_keys($myschoolsections_info);

      foreach ($myschoolsections_info as $id => $name) {
        $schoolsections[] = $id.'_App-Modules-Programs-Models-ProgramSchoolSection';
      }
      

      $programSectionUsers = new ProgramSectionUser();
      $schoolsectionusers_arr = $programSectionUsers->fetchSectionUsersForReports($schoolsections);
      //dd($schoolsectionusers);
      $schoolsectionusers = implode(',', $schoolsectionusers_arr);

      $query = 'select "assign"."Title" as "assessmentTitle", 
case WHEN assa."AssignmentType" = \'Lesson\' or assa."AssignmentType" = \'Default\' THEN assa."Name" else pse."Name" end as "AssignementName", 
"assa"."Id" as "assignmentId", 
"assa"."StartDate" as "startDate", 
"assa"."EndDate" as "endDate", 
"form"."Display" as "type", 
(SELECT COUNT(*) FROM "AssessmentAssignmentUsers" as aau WHERE aau."GradeProgress" = \'Waiting\' AND aau."AssignmentId" = assa."Id" AND aau."Type" = \'User\' and "aau"."UserId" IN ('.$schoolsectionusers.')) as "WaitingCount", 
(SELECT COUNT(*) from "AssessmentAssignmentUsers" as asusr2 where asusr2."AssignmentId" = assa."Id" and "Type" = \'User\' and "asusr2"."UserId" IN ('.$schoolsectionusers.')) as "totalCount", 
(SELECT COUNT(*) from "AssessmentAssignmentUsers" as asusr3  where asusr3."AssignmentId" = assa."Id" and asusr3."Type" = \'User\' and ("Status" = \'Completed\' or "Status" = \'Complete\')  and "asusr3"."UserId" IN ('.$schoolsectionusers.')) as "testCompleted", 
(SELECT COUNT(*) from "AssessmentAssignmentUsers" as asusr4  where asusr4."AssignmentId" = assa."Id" and asusr4."Type" = \'User\' and asusr4."GradeStatus" =\'Complete\'  and "asusr4"."UserId" IN ('.$schoolsectionusers.')) as "gradeCompleted", 
(SELECT min(csj."Id") from "CsvCronjobs" as csj where "AssignmentId" = assa."Id" and "Status" in (\'complete\', \'in_progress\', \'not_started\')) as "importedGradeStatusss", 
(SELECT min("UserId") from "AssessmentAssignmentUsers" as asusr5 where asusr5."Type" = \'Grader\' and asusr5."AssignmentId" =assa."Id" and "asusr5"."UserId" IN ('.$schoolsectionusers.')) as "graderId", 
"assa"."GradeStatus" as "gradeStatus",
(SELECT ROUND(avg("Score")::numeric, 1) FROM "UserAssessmentAssignmentResults" as uaaur WHERE uaaur."AssignmentId" = assa."Id" and "uaaur"."UserId" IN ('.$schoolsectionusers.')) as "Average_Score"

from "AssessmentAssignment" as "assa" 
left join "ProgramSyllabusEvents" as "pse" on "assa"."ReferenceId" = "pse"."Id" 
left join "AssessmentAssignmentUsers" as "asusr" on "assa"."Id" = "asusr"."AssignmentId" and "asusr"."UserId" IN ('.$schoolsectionusers.')
inner join "Assessments" as "assign" on "assa"."assessmentId" = "assign"."Id" 
left join "Lessons" as "lesson" on "assign"."Id" = "lesson"."ExerciseId" 
left join "AssessmentAssignmentInstitutions" as "assAinst" on "assa"."Id" = "assAinst"."assignmentId" 
inner join "Options" as "form" on "assign"."CategoryId" = "form"."Id" 
left join "ProgramResources" as "pr" on "assign"."Id" = "pr"."ResourceId" and "pr"."Resource_type" = \'App\Modules\Assessment\Models\Assessment\'
where "assa"."deleted_at" is null 
and "assign"."deleted_at" is null 
and "pse"."deleted_at" is null 
and "form"."deleted_at" is null 
and (assa."StartDate" + assa."StartTime" <= date_trunc(\'minute\',now() AT TIME ZONE \'UTC\') 
and "assign"."deleted_at" is null 
and "assa"."deleted_at" is null 
and lesson."Id" is null OR asusr."GradeStatus" = \'Complete\') 
and "assa"."GradeStatus" in (\'Not Started\', \'In Progress\', \'Complete\')
and "asusr"."UserId" IN ('.$schoolsectionusers.')
group by "assa"."Id", "assign"."Title", "form"."Display", "pse"."Id" order by "assa"."StartDate" desc limit 10';
      $res = DB::select( DB::raw($query));
      return $res;
    }

    public static function getRecent_Grades($schoolsectionIds = [] )
    {
        $res = [];
        $schoolsections = [];
        $schoolsectionusers_arr = [];
        $schoolsectionusers = '';
        
        if(empty($schoolsectionIds) )
        {
          $myschoolsections_info = Dashboard::getProgramSchoolSections();
          foreach ($myschoolsections_info as $id => $name) {
            $schoolsections[] = $id.'_App-Modules-Programs-Models-ProgramSchoolSection';
          }
        }
        else
        {
          foreach ($schoolsectionIds as $id) {
            $schoolsections[] = $id.'_App-Modules-Programs-Models-ProgramSchoolSection';
          }
        }

        if(empty($schoolsections) )
        {
            return [];
        }

        $programSectionUsers = new ProgramSectionUser();
        $schoolsectionusers_arr = $programSectionUsers->fetchSectionUsersForReports($schoolsections);
        //dd($schoolsectionusers_arr);

        if(empty($schoolsectionusers_arr) )
        {
            return [];
        }
        
        $schoolsectionusers = implode(',', $schoolsectionusers_arr);

        $query = DB::table('AssessmentAssignment as assa')
                ->leftjoin('ProgramSyllabusEvents as pse', 'assa.ReferenceId','=','pse.Id')
                ->leftJoin('AssessmentAssignmentUsers as asusr',function($join) use ($schoolsectionusers){
                    $join->on('assa.Id', '=', 'asusr.AssignmentId');
                    $join->on('asusr.UserId', 'in',  DB::raw('('.$schoolsectionusers.')'));
                }) 
                ->join('Assessments as assign', 'assa.assessmentId', '=', 'assign.Id')
                ->leftjoin('Lessons as lesson', 'assign.Id', '=', 'lesson.ExerciseId')
                ->leftJoin('AssessmentAssignmentInstitutions as assAinst','assa.Id','=','assAinst.assignmentId')
                ->join('Options as form', 'assign.CategoryId', '=', 'form.Id')
                ->leftJoin('ProgramResources as pr',function($join){
                    $join->on('assign.Id', '=', 'pr.ResourceId');
                    $join->on('pr.Resource_type', '=', DB::raw("'App\Modules\Assessment\Models\Assessment'"));
                })->where('assa.deleted_at', '=', null)
                ->where('assign.deleted_at', '=', null)
                ->where('pse.deleted_at', '=', null)
                ->where('form.deleted_at', '=', null)
                ->whereIn('asusr.UserId', $schoolsectionusers_arr);

                $query->select(
                            // 'assign.Title as Title',
                        'assign.Title as assessmentTitle',
                        DB::raw('case WHEN assa."AssignmentType" = \'Lesson\' or assa."AssignmentType" = \'Default\' THEN assa."Name" else pse."Name" end as "AssignementName"'),
                        'assa.Id as assignmentId',
                        'assa.StartDate as startDate',
                        'assa.EndDate as endDate',
                        'form.Display as type',
                        DB::raw('(SELECT COUNT(*) FROM "AssessmentAssignmentUsers" as aau WHERE aau."GradeProgress" = \'Waiting\' AND aau."AssignmentId" = assa."Id" AND aau."Type" = \'User\' and "aau"."UserId" IN ('.$schoolsectionusers.') ) as "WaitingCount"'),
                        DB::raw('(SELECT COUNT(*) from "AssessmentAssignmentUsers" as asusr2 where asusr2."AssignmentId" = assa."Id" and "Type" = \'User\' and "asusr2"."UserId" IN ('.$schoolsectionusers.')) as "totalCount"'),
                        DB::raw('(SELECT COUNT(*) from "AssessmentAssignmentUsers" as asusr3  where asusr3."AssignmentId" = assa."Id" and asusr3."Type" = \'User\' and ("Status" = \'Completed\' or "Status" = \'Complete\') and "asusr3"."UserId" IN ('.$schoolsectionusers.')) as "testCompleted"'),
                        DB::raw('(SELECT COUNT(*) from "AssessmentAssignmentUsers" as asusr4  where asusr4."AssignmentId" = assa."Id" and asusr4."Type" = \'User\' and asusr4."GradeStatus" =\'Complete\' and "asusr4"."UserId" IN ('.$schoolsectionusers.')) as "gradeCompleted"'),
                        DB::raw('(SELECT min(csj."Id") from "CsvCronjobs" as csj where "AssignmentId" = assa."Id" and "Status" in (\'complete\', \'in_progress\', \'not_started\')) as "importedGradeStatusss"'),
                        DB::raw('(SELECT min("UserId") from "AssessmentAssignmentUsers" as asusr5 where asusr5."Type" = \'Grader\' and asusr5."AssignmentId" =assa."Id") as "graderId"'),
                        'assa.GradeStatus as gradeStatus',
                        DB::raw('(SELECT ROUND(avg("Score")::numeric, 1) FROM "UserAssessmentAssignmentResults" as uaaur WHERE uaaur."AssignmentId" = assa."Id" and "uaaur"."UserId" IN ('.$schoolsectionusers.')) as "Average_Score"')
                        )
                        ->groupBy(
                            'assa.Id',
                            'assign.Title',
                            'form.Display',
                             'pse.Id'
                            );

       $query->where(function($query){
            $query->whereRaw( 'assa."StartDate" + assa."StartTime" <= date_trunc(\'minute\',now() AT TIME ZONE \'UTC\')' );
            $query->whereNull('assign.deleted_at');
            $query->whereNull('assa.deleted_at');
            $query->whereRaw('lesson."Id" is null OR asusr."GradeStatus" = \'Complete\'');
        });

        
        /*if ( $view_Grading && $view_all_Grading==false ){
            $query->where('asusr.Type','Grader')->where('asusr.UserId',Auth::user()->id);
        } 

        if ( isset($filters['programId'] )&&!empty($filters['programId'] ) ){
            $query->where('pr.ProgramId',$filters['programId']);
         }

        if ( isset($filters['institutionId'])&&!empty($filters['institutionId']) ){
            $query->where('assAinst.institutionId','=',$filters['institutionId']);
        }

        if ( isset($filters['search'])&&!empty($filters['search']) ){
            $query->where(function( $query ) use ( $filters ) {
                $query->where('assign.Title','ILIKE', '%' . $filters['search'] . '%');
                $query->orWhereRaw('case when pse."Name" is null then assa."Name" else pse."Name" end ILIKE '.' \'%' . str_replace('\'', '\'\'', $filters['search']) . '%\'');
                $query->orWhere('assa.GradeStatus','ILIKE', '%' . $filters['search'] . '%');
                $query->orWhereRaw('to_char(assa."StartDate", \'MM/DD/YYYY\') ILIKE '.'\'%' . str_replace('\'', '\'\'', $filters['search'])  . '%\'');
            });
        }*/

        $query->whereIn('assa.GradeStatus', ['Not Started','In Progress', 'Complete']); 

        $query->orderBy('assa.StartDate','desc');
        

        $toTake = 10;
        $toSkip = 0;

        // dd($query->toSql());
        return $query->skip( $toSkip )->take( $toTake )->get();
    }

    public static function getInstitutionPrograms($institutionId = 0)
    {
        $myPrograms = [];
        $programId = 0;
        if($institutionId == 0)
        {          
          $userInstitution = Auth::user()->institutions()->first(['Id']);
          $institutionId = $userInstitution->Id;
        }
        //echo $institutionId;
        if ((Auth::user()->ProfileType == E_STAFF || Auth::user()->ProfileType == TEACHER)) {
            
            if(Auth::user()->ProfileType == E_STAFF){
                $myPrograms = Program::getMyProgram();
            }else{
                $myPrograms = Program::getInternalTeacherPrograms();
            }       
        }
        else
        {
          $programs = Program::join('Options as options', function($join) {
            $join->on('options.Id', '=', 'Programs.StatusId')
            ->on('options.Type', '=', DB::raw('\'ProgramsStatus\''));
          })
          ->where('Programs.InstitutionId', '=', $institutionId)
          ->where('options.Option', 'ilike', '%Active%');

          $myPrograms = $programs->select('Programs.Id', 'Programs.Name')->orderBy('Programs.Name')->lists('Programs.Name', 'Programs.Id');
        }
        //dd($myPrograms);
        return $myPrograms;
    }
    public static function getSyllabusEventByProgarmId($myProgramId = []) {
        if(empty($myProgramId))
        {
          return [];
        }
        $parms['eventType'] = 0;
        $parms['sortType'] = 'By Date';
        $parms['programId']  = $myProgramId; // array of program ids
        $parms['forSummaryPage'] = true;
        //$parms['getPastEvents'] = false;
        
        $query = Program::join('ProgramResources as pr', 'pr.ProgramId', '=', 'Programs.Id')
                ->join('Institutions as inst', 'inst.Id', '=', 'Programs.InstitutionId')
                ->join('ProgramSyllabusEvents as pse', 'pse.Id', '=', 'pr.ResourceId')
                ->leftjoin('Options as pseO', 'pseO.Id', '=', 'pse.SyllabusEventType')
                ->leftjoin('users as upipdp', 'upipdp.id', '=', 'pse.InstructorId')
                ->leftjoin('users as upaateacher', 'upaateacher.id', '=', 'pse.GraderId')
                ->leftjoin('TestSchedules as ts', 'ts.Id', '=', 'pse.TestScheduleId')
                ->leftjoin('Assessments as testasse', 'testasse.Id', '=', 'ts.TestId')
                ->leftjoin('Assessments as a', 'a.Id', '=', 'pse.TestId')
                ->leftjoin('Subsections as asubsec', 'asubsec.AssessmentId', '=', 'a.Id')
                ->leftjoin('ProgramSyllabusRoster as psr', 'psr.ProgramSyllabusEventId', '=', 'pse.Id')
                ->leftjoin('users as ur', 'ur.id', '=', 'psr.UserId')
                ->select(
                    DB::raw('COUNT(ur."id") AS userCount'), 
                    'Programs.TargetTestId', 
                    DB::raw('MIN("Programs"."Id") as "progId"'),
                    DB::raw('MIN(inst."Id") as "instId"'),
                    DB::raw('MIN(pse."ProgramAASectionId") as "progAaId"'),
                    'Programs.Name as pname', 
                    'pse.Id as pseId', 
                    'pse.Date as pseDate', 
                    'pse.Description as syllabusDescription', 
                    'pse.Name as pseName', 
                    'pse.StartTime as pseStime', 
                    'pse.EndTime as pseEtime', 
                    'pse.LessonPlan as pseLessonPlan', 
                    'pseO.Display as pgType', 
                    'upipdp.name as pgProtorInstructorProviderName', 
                    'upaateacher.name as pgGraderName', 
                    'a.Title as pgTestEventAssessmentName', 
                    'a.Id as pgTestEventAssessmentId', 
                    'a.PrintViewFile as printViewFile', 
                    'testasse.Title as pgTestEventScheduleAssessmentName', 
                    'testasse.Id as pgTestEventScheduleAssessmentId', 
                    'testasse.PrintViewFile as pgTestEventScheduleprintViewFile', 
                    'inst.Name as InstitutionName', 
                    DB::raw('min(asubsec."Id") As "subsectionId"'),
                    'a.CreatedBy as pgTestEventAssessmentOwner',
                    'testasse.CreatedBy as pgTestEventScheduleAssessmentOwner'
        );

        //$query->where('Programs.Id', '=', $parms['programId']);
        $query->whereIn('Programs.Id', $parms['programId']);
        $query->whereNull('pse.deleted_at');
        $query->where('pr.Resource_type', '=', 'App\Modules\Programs\Models\ProgramSyllabusEvent');

        if (isset($parms['eventType'])) {
            if ($parms['eventType'] != 0) {
                $query->where('pseO.Id', '=', $parms['eventType']);
            }
        }

        if (isset($parms['sortType'])) {
            if ($parms['sortType'] == 'By Event Type') {
                $query->orderBy('pseO.Display', 'ASC');
            } else if ($parms['sortType'] == 'By Date') {
                if (empty($parms['forSummaryPage'])) {
                    $query->orderBy('pse.Date', 'ASC');
                } else {
                    $query->orderByRaw(' case when "pse"."Date" is null then 1 else 2 end asc, "pse"."Date" asc, case when "pse"."Date" is null then "pse"."created_at" else "pse"."Date" end DESC');
                }
            } else if ($parms['sortType'] == 'By Title') {
                $query->orderBy('pse.Name', 'ASC');
            }
        }
        $applyPastEventCheck = false;
        if (!isset($parms['avoidPastEventCheck'])) {
            $applyPastEventCheck = true;
        } else if (isset($parms['avoidPastEventCheck'])) {
            if ($parms['avoidPastEventCheck'] == false) {
                $applyPastEventCheck = true;
            }
        }
        if ($applyPastEventCheck == true) {
            if (!isset($parms['getPastEvents'])) {
                $query->whereRaw("(CASE WHEN \"pse\".\"Date\" > '" . date('Y-m-d') . "' THEN \"pse\".\"Date\" > '" . date('Y-m-d') . "' ELSE \"pse\".\"Date\" = '" . date('Y-m-d') . "' AND \"pse\".\"EndTime\" >= '" . date('H:i:s') . "' END)");
            } else if (isset($parms['getPastEvents'])) {
                if ($parms['getPastEvents'] == 'false') {
                    $query->whereRaw("(CASE WHEN \"pse\".\"Date\" > '" . date('Y-m-d') . "' THEN \"pse\".\"Date\" > '" . date('Y-m-d') . "' ELSE \"pse\".\"Date\" = '" . date('Y-m-d') . "' AND \"pse\".\"EndTime\" >= '" . date('H:i:s') . "' END)");
                }
            }
        } else {
            $query->orderByRaw(' case when "pse"."Date" is null then 1 else 2 end asc, "pse"."Date" asc, case when "pse"."Date" is null then "pse"."created_at" else "pse"."Date" end DESC');
        }
        // $toTake = 6;

        $toTake = isset($parms['toTake']) ? $parms['toTake'] : 10;
        if (!isset($parms['toTake'])) {
            $toSkip = isset($parms['skip']) ? $parms['skip'] : 0;

            // After two passes (which means when first ten records have been skipped)
            // We need to take the 9 records for each of the request
            if ($toSkip >= 10) {
                $toTake = 9;
            }
        } else {
            $toSkip = 0;
        }

        $query->groupBy("Programs.TargetTestId", "pse.Id", "pseO.Display", "upipdp.name", "upaateacher.name", "a.Id", "testasse.Id", "Programs.Name", "inst.Id");


        $result = $query->take($toTake)->skip($toSkip)->get();
        // $result = $query->take($toTake)->skip($toSkip)->toSql();      

        return $result;
    }

    public static function getPrograms($ids = [])  {
      if(!empty($ids)) {
         $result = DB::table('Programs')->whereIn('Id',$ids)->where('deleted_at',null)->get();
         return $result;
      }else {
        return [];
      }
    }
 
    public static function getRoasterStudentsList($programIds = []) {
        if(!empty($programIds)) {
           $result  = User::select( "users.*","ProgramSyllabusRoster.ProgramSyllabusEventId as pivot_ProgramSyllabusEventId",
              "ProgramSyllabusRoster.UserId as pivot_UserId")
              ->join("ProgramSyllabusRoster","users.id" ,"=", "ProgramSyllabusRoster.UserId" )
              ->where("users.deleted_at",null )
              ->whereIn( "ProgramSyllabusRoster.ProgramSyllabusEventId",$programIds )
              ->get();
        } else {
          return [];
        }
    }
    public static function getFileAssignments($filters = null) {

        $query = DB::table('Assignments')
                ->leftJoin('ResourceAssignments', 'Assignments.Id', '=', 'ResourceAssignments.AssignmentId')
                ->leftJoin('Files', function($join) {
                    $join->on('ResourceAssignments.ResourceId', '=', 'Files.Id');
                    $join->on('ResourceAssignments.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                })
                ->leftJoin('ResourceTestTypes', function( $join ) {
                    $join->on('ResourceTestTypes.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                    $join->on('Files.Id', '=', 'ResourceTestTypes.ResourceId');
                })
                ->leftJoin('ResourceStandardSystems as standSys', function ( $join) {
                    $join->on('standSys.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                    $join->on('standSys.ResourceId', '=', 'Files.Id');
                })
                ->leftJoin('ResourceTestApplicability as testApp', function ( $join) {
                    $join->on('testApp.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
                    $join->on('testApp.ResourceId', '=', 'Files.Id');
                })
                ->leftJoin('ResourceCurriculumCategories as currCat', function ( $join) {
            $join->on('currCat.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
            $join->on('currCat.ResourceId', '=', 'Files.Id');
        });

        $query->select('Assignments.Id as AssignmentId', 'Assignments.Name as AssignmentName', 'Assignments.StartDate', 'Assignments.EndDate', 'Files.Name as FileName');

        $query->where('Assignments.Types', '=', 'file');
        $query->where('Assignments.deleted_at', '=', NULL)->groupBy('Assignments.Id', 'Files.Name');

        //apply search filter
        if ($filters) {
            if ($filters['searchTxt']) {
                $query->where(function( $q)use( $filters ) {
                    $q->where('Files.Name', 'ILIKE', '%' . $filters['searchTxt'] . '%');
                    $q->orWhere('Assignments.Name', 'ILIKE', '%' . $filters['searchTxt'] . '%');
                });
            }

            //apply status i.e. Active or Inactive
            if ($filters['filterStatusId'] != -1) {
                if ($filters['filterStatusId'] == 1) {
                    // for active                                       
                    $query->where('Assignments.EndDate', '>=', date('Y,m,d'));
                } else if ($filters['filterStatusId'] == 2) {
                    // for expired                    
                    $query->where('Assignments.EndDate', '<', date('Y,m,d'));
                }
            }

            //apply type file i.e. ACT, SAT etc
            if ($filters['filterTypeId'] != -1) {
                $query->where('ResourceTestTypes.TestTypeId', '=', $filters['filterTypeId']);
            }

            //  if( $filters['instituteId']!=-1 && $filters['instituteId']!=0){
            //     $query->where( 'Institutions.Id', '=', $filters['instituteId'] );
            // }  
            //apply lesson assignment name 
            if ($filters['lessonAssignmentId'] != -1 && $filters['lessonAssignmentId'] != 0) {
                $query->where('Assignments.Id', '=', $filters['lessonAssignmentId']);
            }
            if (isset($filters['standardSystem']) && !empty($filters['standardSystem'])) {
                $query->whereIn('standSys.StandardSystemId', $filters['standardSystem']);
            }

            if (isset($filters['testApplicability']) && !empty($filters['testApplicability'])) {
                $query->whereIn('testApp.TestApplicabilityId', $filters['testApplicability']);
            }

            if (isset($filters['curriculumCategory']) && !empty($filters['curriculumCategory'])) {
                $query->whereIn('currCat.CurriculumCategoryId', $filters['curriculumCategory']);
            }

            //sort the data by given field and sort order .i.e. Assignment.StartDate->ASC
            if ($filters['sortBy'] != '') {
                $query->orderby('Assignments.' . $filters['sortBy'], $filters['sortTo']);
            } else {
                $query->orderby('Assignments.Id', 'ASC');
            }
            // get the displayed items number and get the total number of records
            $toTake = 10 + $filters['toSkip'];
            $toSkip = isset($filters['toSkip']) ? $filters['toSkip'] : 0;

            // After two passes (which means when first ten records have been skipped)
            // We need to take the 18 records for each of the request
            if ($toSkip >= 20) {
                $toTake = 18;
            }

            $assigmentsList = $query->take($toTake)->skip($toSkip)->get();
        } else {
          $query->orderby('Assignments.Id','DESC');
          $query->groupBy('Assignments.Id');
            $assigmentsList = $query->take(10)->get();
        }


        return $assigmentsList;
    }

    public static function getRecentlyViewedFiles($userID = 0)
    {
        $results = DB::table('Files')
        ->join('Views_Track as vts', function ($join) use($userID) {
              $join->on('vts.Module_Type', '=', DB::raw("'Files'"));
              $join->on('vts.Module_Type_Id', '=', 'Files.Id');
              $join->on('vts.UserId', '=', DB::raw("'".$userID."'"));
          })
        ->select('Files.Id', 'Name')->orderby('vts.created_at', 'desc')->take(10)->lists('Name','Files.Id'); 
        
        //dd($results);
        return $results;

    }

    public static function getRecentlyViewedLessons($userID = 0)
    {
        $results = DB::table('Lessons')
        ->join('Views_Track as vts', function ($join)   use($userID){
              $join->on('vts.Module_Type', '=', DB::raw("'Lessons'"));
              $join->on('vts.Module_Type_Id', '=', 'Lessons.Id');
              $join->on('vts.UserId', '=', DB::raw("'".$userID."'"));
          })
        ->select('Lessons.Id', 'Name')->orderby('vts.created_at', 'desc')->take(10)->lists('Name','Lessons.Id'); 
        
        //dd($results);
        return $results;
      
    }

    public static function getRecentlyViewedQuestions($userID = 0)
    {
        $results = DB::table('Questions')
        ->join('Views_Track as vts', function ($join)   use($userID){
              $join->on('vts.Module_Type', '=', DB::raw("'Questions'"));
              $join->on('vts.Module_Type_Id', '=', 'Questions.Id');
              $join->on('vts.UserId', '=', DB::raw("'".$userID."'"));
          })
        ->select('Questions.Id', 'Title')->orderby('vts.created_at', 'desc')->take(10)->lists('Title','Questions.Id'); 
        
        //dd($results);
        return $results;      
    }

    public static function getRecentlyViewedAssessments($userID = 0)
    {
         $results = DB::table('Assessments')
        ->join('Views_Track as vts', function ($join)  use($userID) {
              $join->on('vts.Module_Type', '=', DB::raw("'Assessments'"));
              $join->on('vts.Module_Type_Id', '=', 'Assessments.Id');
              $join->on('vts.UserId', '=', DB::raw("'".$userID."'"));
          })
        ->select('Assessments.Id', 'Title')->orderby('vts.created_at', 'desc')->take(10)->lists('Title','Assessments.Id'); 
        
        //dd($results);
        return $results;        
    }

    public static function getRecentlyViewedPrograms($userID = 0)
    {
        $results = DB::table('Programs')
        ->join('Views_Track as vts', function ($join)   use($userID){
              $join->on('vts.Module_Type', '=', DB::raw("'Programs'"));
              $join->on('vts.Module_Type_Id', '=', 'Programs.Id');
              $join->on('vts.UserId', '=', DB::raw("'".$userID."'"));
          })
        ->select('Programs.Id', 'Name')->orderby('vts.created_at', 'desc')->take(10)->lists('Name','Programs.Id'); 
        
        //dd($results);
        return $results;         
    }

//     public static function getQuestionsAssigned() {
//         $query = DB::table("Questions as q")
//                 ->where('q.deleted_at', '=', null);

//         $query->join('Options as visibility', 'q.VisibilityId', '=', 'visibility.Id', 'left')
//                 ->join('users as u', 'q.AuthorId', '=', 'u.id', 'left');
            
//         $query->select('q.*', 'so.Display as status', DB::raw('(SELECT COUNT(*) FROM "QuestionPassages" AS qp where qp."QuestionId" = "q"."Id") as "CountPassages"'), DB::raw('(SELECT string_agg(o."Display" ,\', \')  FROM "Subjectables" sa JOIN "Options" o ON sa."SubjectId" = o."Id"
//                         WHERE sa."Subjectable_type" = \'App\Modules\Resources\Models\Question\' 
//                         AND sa."ResourceId" = "q"."Id")
//                         AS "Subject"'), DB::raw('(SELECT COUNT(*) FROM "Taggables" AS ta JOIN "MetaTag" AS mt ON mt."Id" = ta."TagId" WHERE ta."ResourceId" = q."Id" AND ta."Taggable_type" = \'App\Modules\Resources\Models\Question\') as "Tags"'), 
//                         DB::raw('(SELECT COUNT(DISTINCT(ss."Id")) FROM "ResourceStandardSystems" as rss JOIN "StandardSystems" ss on rss."StandardSystemId" = ss."Id" where not exists(select 1 from "StandardSystems" ss2 where ss2."ParentId" = ss."Id" ) and ss.deleted_at is null AND q."Id" = rss."ResourceId" and rss."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(cc."Id")) FROM "ResourceCurriculumCategories" as rcc JOIN "CurriculumCategories" cc on rcc."CurriculumCategoryId" = cc."Id" where not exists(select 1 from "CurriculumCategories" cc2 where cc2."ParentId" = cc."Id" ) and cc.deleted_at is null AND q."Id" = rcc."ResourceId" and rcc."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(ta."Id")) FROM "ResourceTestApplicability" as rta JOIN "TestApplicabilities" ta on rta."TestApplicabilityId" = ta."Id" where not exists(select 1 from "TestApplicabilities" ta2 where ta2."ParentId" = ta."Id" ) and ta.deleted_at is null AND q."Id" = rta."ResourceId" and rta."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "Standards"')//, 
//                         ,DB::raw('CAST (\'\' AS TEXT) AS "Assessments"')
//         );

//         $query->join('ProgramResources', function($join) {
//             $join->on('ProgramResources.ResourceId', '=', 'q.Id');
//             $join->on('ProgramResources.Resource_type', '=', DB::raw('\'App\Modules\Resources\Models\Question\''));
//         })->join('ProgramAuthorizedUsers as pau', function($join) {
//             $user_id = Auth::user()->id;
//             $join->on('pau.ProgramId', '=', 'ProgramResources.ProgramId');
//             $join->on('pau.UserId', '=', DB::raw($user_id));
//             $join->on('pau.Qbank', '=', DB::raw('\'t\''));
//         });      

//         // Permissions on Questions
//         $user = Auth::user();
//         $user_id = $user->id;
//         $profile_type = $user->ProfileType;
//         $questionsId = null;
//         $offset = 0;
//         $limit = '';
//         $query->where(function($query2) use ($user_id, $profile_type, $questionsId) {
//             // Visibility Private, User Type Internal AA, Question visible to Internal AA only
//             $query2->where(function($query3) use ($user_id, $profile_type) {
//                 $query3->whereRaw("'$profile_type' = 'AA Internal Staff'");
//             });
//             // Visibility Priveate, User Type AA Tutor,AA Teacher,External Staff: Question visible to Author only
//             $query2->orWhere(function($query3) use ($user_id, $profile_type, $questionsId) {
//                 $query3->where('visibility.Option', '=', 'Private');
//                 $query3->whereIn('u.ProfileType', ['AA Tutor', 'AA Teacher', 'External Staff']);
//                 $query3->whereRaw('"q"."AuthorId" = ' . $user_id);
//                 if(!empty($questionsId)) {
//                     $query->orWhereIn('q.Id', $questionsId);
//                 }
//             });
//             // Visibility Public, User Type Internal AA: Question visible to Every one if logged in user is not external staff
//             if ($profile_type != 'External Staff') {
//                 $query2->orWhere(function($query3) use ($user_id, $profile_type) {
//                     $query3->where('visibility.Option', '=', 'Public');
//                     $query3->whereIn('u.ProfileType', ['AA Internal Staff']);
//                 });
//             } else {
//                 // Show the public, active questions created by internal staff
//                 $query2->orWhere(function($query3) use ($user_id, $profile_type) {

//                     $qbankId = Option::getOptionId("Item Category", "Questions");      // get fixed form id

//                     $query3->where('visibility.Option', '=', 'Public');
//                     $query3->where('so.Display', '=', 'Active');
//                     $query3->where('q.ItemCategoryId', '=', $qbankId);
//                     $query3->whereIn('u.ProfileType', ['AA Internal Staff']);
//                 });
//             }

//             if($profile_type == TEACHER){
//                 $fixedFormId = Option::getOptionId("Item Category", "Fixed Form");// get fixed form id
//                 $query2->where('q.ItemCategoryId', '!=', $fixedFormId);
//             }


//             // Visibility Public, User Type 'AA Teacher', 'AA Tutor', 'External Staff': Question visible to like users in same department
//             $query2->orWhere(function($query3) use ($user_id, $profile_type) {
//                 $query3->where('visibility.Option', '=', 'Public');
//                 $query3->whereIn('u.ProfileType', ['AA Teacher', 'AA Tutor', 'External Staff']);
//                 $query3->whereRaw("u.\"ProfileType\" = '$profile_type'");
               
//             });
//         });

//         if (!empty($filters['skip'])) {
//             $limit = $filters['limit'];
//             $offset = isset($filters['skip']) ? $filters['skip'] : 0;
//         }

//         if (!empty($id)) {
//             $query->whereRaw("\"q\".\"Id\" = $id");
//         }

//         if ( isset($filters['sorter']) && !empty($filters['sorter']) ) {
//             foreach ($filters['sorter'] as $sortCol => $sortVal) {
//                 $query->orderBy( $sortCol, $sortVal );
//                 if(strtolower($sortCol)=="text"){
//                     $query->orderBy( "Title", $sortVal );
//                 }
//             }
//         }

//         $query->skip($offset)->take($limit);
//         $query->select('q.*', 'so.Display as status', DB::raw('(SELECT COUNT(*) FROM "QuestionPassages" AS qp where qp."QuestionId" = "q"."Id") as "CountPassages"'), DB::raw('(SELECT string_agg(o."Display" ,\', \')  FROM "Subjectables" sa JOIN "Options" o ON sa."SubjectId" = o."Id"
//                         WHERE sa."Subjectable_type" = \'App\Modules\Resources\Models\Question\' 
//                         AND sa."ResourceId" = "q"."Id")  AS "Subject"'), DB::raw('(SELECT COUNT(*) FROM "Taggables" AS ta JOIN "MetaTag" AS mt ON mt."Id" = ta."TagId" WHERE ta."ResourceId" = q."Id" AND ta."Taggable_type" = \'App\Modules\Resources\Models\Question\') as "Tags"'), 
//                         DB::raw('(SELECT COUNT(DISTINCT(ss."Id")) FROM "ResourceStandardSystems" as rss JOIN "StandardSystems" ss on rss."StandardSystemId" = ss."Id" where not exists(select 1 from "StandardSystems" ss2 where ss2."ParentId" = ss."Id" ) and ss.deleted_at is null AND q."Id" = rss."ResourceId" and rss."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(cc."Id")) FROM "ResourceCurriculumCategories" as rcc JOIN "CurriculumCategories" cc on rcc."CurriculumCategoryId" = cc."Id" where not exists(select 1 from "CurriculumCategories" cc2 where cc2."ParentId" = cc."Id" ) and cc.deleted_at is null AND q."Id" = rcc."ResourceId" and rcc."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(ta."Id")) FROM "ResourceTestApplicability" as rta JOIN "TestApplicabilities" ta on rta."TestApplicabilityId" = ta."Id" where not exists(select 1 from "TestApplicabilities" ta2 where ta2."ParentId" = ta."Id" ) and ta.deleted_at is null AND q."Id" = rta."ResourceId" and rta."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "Standards"'), 
//                         DB::raw('CAST (\'\' AS TEXT) AS "Assessments"')

//         );
//         $profileType = Auth::user()->ProfileType;
//         if ($profileType == 'External Staff') {
// //             echo $query2->union($query)->toSql(); die();
//             $result = $query->union($query)->get();
//         } else {
//             $result = $query->get();
//         }
//         dd($result);
//         return $result;

//     }
 public static function getProgramsAssigned($filters = array()) {
        $offset = 0;
        $limit = 10;
        $selectAttributes = ['Programs.Id', 'Programs.Name', DB::raw("string_agg(\"uowners\".\"name\", ', ') AS programowners"), 'op.Display as targetTest', 'inst.Name as SchoolName', 'status.Display as status', 'Programs.StartDate', 'Programs.EndDate'];
        $groupByAttributes = ['Programs.Id', 'Programs.Name', 'inst.Name', 'status.Display', 'Programs.StartDate', 'Programs.EndDate', 'op.Display'];
        $obj = DB::table('Programs')
                ->join('Options as status', 'Programs.StatusId', '=', 'status.Id', 'left')
                ->join('Institutions as inst', 'Programs.InstitutionId', '=', 'inst.Id')
                ->leftjoin('Taggables as tga', function($join) {
                    $join->on('Programs.Id', '=', 'tga.ResourceId');
                })
                ->leftjoin('MetaTag as mt', 'tga.TagId', '=', 'mt.Id')
                ->leftJoin('ProgramOwners as po', 'po.ProgramId', '=', 'Programs.Id')
                ->leftJoin('users as uowners', 'uowners.id', '=', 'po.UserId')
                ->leftJoin('Options as op', 'op.Id', '=', 'Programs.TargetTestId')
                ->whereNull('Programs.deleted_at');

        if (Auth::user()->ProfileType == 'Student' || Auth::user()->ProfileType == 'External Staff' || Auth::user()->ProfileType == 'AA Teacher') {
            $obj->where('Programs.EndDate', '>', date('Y-m-d'));
            $obj->leftjoin('ProgramRosters as prgmRosters', function($query) {
                $query->on('Programs.Id', '=', 'prgmRosters.ProgramId')
                        ->where('prgmRosters.UserId', '=', Auth::id());
            });
            $obj->leftjoin('ProgramAuthorizedUsers as authUsers', function($query) {
                $query->on('Programs.Id', '=', 'authUsers.ProgramId')
                        ->where('authUsers.UserId', '=', Auth::id());
            });

            $obj->leftjoin('ProgramResources as pr', function($query) {
                $query->on('Programs.Id', '=', 'pr.ProgramId')
                ->where('pr.Resource_type', '=', 'App\Modules\Programs\Models\ProgramSyllabusEvent');
            })->join('ProgramSyllabusEvents as pse', 'pse.Id', '=', 'pr.ResourceId');

            $obj->where(function ($query) {
                $query->where("prgmRosters.UserId", '=', Auth::id())
                        ->orWhere("authUsers.UserId", '=', Auth::id())
                        ->orWhere("pse.InstructorId", '=', Auth::id());
            });
            $selectAttributes = array_merge($selectAttributes, ['prgmRosters.UserId as prgmRosterId', 'authUsers.UserId as authUserId', 'authUsers.ViewProgramDetails as authViewProgram']);
            $groupByAttributes = array_merge($groupByAttributes, ['prgmRosters.UserId', 'authUsers.UserId', 'authUsers.ViewProgramDetails']);
        }

        if (!empty($filters['InstitutionId'])) {
            $obj->where('Programs.InstitutionId', $filters['InstitutionId']);
        }

        if (!empty($filters['StatusId'])) {
            $obj->where('Programs.StatusId', $filters['StatusId']);
        }

        if (!empty($filters['ProgramOwnersId']) && (!(count($filters['ProgramOwnersId']) == 1 && empty($filters['ProgramOwnersId'][0])))) {
          $obj->whereIn('uowners.id', $filters['ProgramOwnersId']);
        }

        
        if (!empty($filters['skip'])) {
            $limit = $filters['limit'];
            $offset = isset($filters['skip']) ? $filters['skip'] : 0;
        }
        if (!empty($filters['sortTo']) && !empty($filters['sortBy'])) {
            $obj->orderby($filters['sortTo'], $filters['sortBy']);
        } else {
            $obj->orderBy('Programs.Id', 'desc');
        }
        $obj->groupby($groupByAttributes);
        $obj->skip($offset)->take($limit);

        $obj->select($selectAttributes);
        $result = $obj->get();
        return $result;
    }

    public static function getProgramsYou($userId = null ) {
        $filters['ProgramOwnersId'] = [$userId];
        $programObj = new Program();
        $programs = Dashboard::getProgramsAssigned($filters);
        return $programs;
    }

    public static function getQuestionsAssigned($filters = [], $id = null, $userId = null ) {
        $offset = 0;
        $limit  = 0;
        $programIds = [];
        $questionsId = [];
        $user = Auth::user();
        $filters['assigned_to'] = 1;
        if( $userId == null) {
          $assignedTo = Auth::user()->id;
        } else {
           $assignedTo = $userId;
        }
        if ($user->ProfileType == 'External Staff') {
            $programIds = $user->programsRosters->lists('Id');
            if (count($programIds) > 0) {
               $questionsId = Program::getAssociatedItemAndItemGroups($programIds,'App\Modules\Resources\Models\Question', true, 'App\Modules\Programs\Models\ItemGroup','App\Modules\Resources\Models\Question',Option::QUESTION_MODULE);
            }
        }
        $query = DB::table("Questions as q")
                ->where('q.deleted_at', '=', null)->where('q.Title', '!=', '');

        $query->join('Options as visibility', 'q.VisibilityId', '=', 'visibility.Id', 'left')
                ->join('users as u', 'q.AuthorId', '=', 'u.id', 'left');



        if (!empty($filters['standardSystem'])) {
            $array = is_array($filters['standardSystem']) ? $filters['standardSystem'] : [$filters['standardSystem']];
            $query->whereExists(function($query) use($array) {
                $query->select(DB::raw(1))
                        ->from('ResourceStandardSystems as r')
                        ->whereRaw('r."ResourceId" = "q"."Id" and r."ResourceType" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('r.StandardSystemId', $array);
            });
        }

        if (!empty($filters['curriculumCategory'])) {
            $array = is_array($filters['curriculumCategory']) ? $filters['curriculumCategory'] : [$filters['curriculumCategory']];
            $query->whereExists(function($query) use($array) {
                $query->select(DB::raw(1))
                        ->from('ResourceCurriculumCategories as r')
                        ->whereRaw('r."ResourceId" = "q"."Id" and r."ResourceType" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('r.CurriculumCategoryId', $array);
            });
        }

        if (!empty($filters['testApplicability'])) {
            $array = is_array($filters['testApplicability']) ? $filters['testApplicability'] : [$filters['testApplicability']];
            $query->whereExists(function($query) use($array) {
                $query->select(DB::raw(1))
                        ->from('ResourceTestApplicability as r')
                        ->whereRaw('r."ResourceId" = "q"."Id" and r."ResourceType" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('r.TestApplicabilityId', $array);
            });
        }

        if (!empty($filters['subject'])) {
            $array = is_array($filters['subject']) ? $filters['subject'] : [$filters['subject']];
            $query->whereExists(function($query) use($array) {
                $query->select(DB::raw(1))
                        ->from('Subjectables as sa')
                        ->whereRaw('sa."ResourceId" = "q"."Id" and sa."Subjectable_type" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('sa.SubjectId', $array);
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->join('Options AS so', 'q.StatusId', '=', 'so.Id', 'left')
                    ->where('so.Id', $filters['status'])
                    ->where('so.Type', 'Question Status');
        } else {
            $query->join('Options AS so', 'q.StatusId', '=', 'so.Id', 'left')
                    ->where('so.Type', 'Question Status');
        }

        if (!empty($filters['author'])) {
            $query->where('q.AuthorId', $filters['author']);
        }
       
        if (!empty($filters['assigned_to'])) {
            $query->whereExists(function($query) use($assignedTo) {
                $query->select(DB::raw(1))
                        ->from('UserQuestions as uq')
                        ->whereRaw('uq."QuestionId" = "q"."Id"')
                        ->where('uq.UserId', $assignedTo);
            });
        }

        if (!empty($filters['question_type'])) {
            $query->join('Options AS qto', 'q.QuestionTypeId', '=', 'qto.Id', 'left')
                    ->where('qto.Id', $filters['question_type'])
                    ->where('qto.Type', 'QuestionTypes');
        } else {
            $query->join('Options AS qto', 'q.QuestionTypeId', '=', 'qto.Id', 'left')
                    ->where('qto.Type', 'QuestionTypes');
        }

        if (isset($filters['with_passage']) && $filters['with_passage'] == "true") {
            $query->whereExists(function($query) {
                $query->select(DB::raw(1))
                        ->from('QuestionPassages as qp')
                        ->join('Passages as p', 'p.Id', '=', 'qp.PassageId')
                        ->whereRaw('qp."QuestionId" = "q"."Id"');
            });
        }

        if (!empty($filters['passage'])) {
            $query->join('QuestionPassages as quest_p', 'q.Id', '=', 'quest_p.QuestionId');
            $query->where('quest_p.PassageId', '=', $filters['passage']);
        }

        /////////////////Query 2 Start
        $query2 = DB::table("Questions as q")
                ->where('q.deleted_at', '=', null);

        $query2->join('Options as visibility', 'q.VisibilityId', '=', 'visibility.Id', 'left')
                ->join('users as u', 'q.AuthorId', '=', 'u.id', 'left');

        if (!empty($filters['standardSystem'])) {
            $array = is_array($filters['standardSystem']) ? $filters['standardSystem'] : [$filters['standardSystem']];
            $query2->whereExists(function($query2) use($array) {
                $query2->select(DB::raw(1))
                        ->from('ResourceStandardSystems as r')
                        ->whereRaw('r."ResourceId" = "q"."Id" and r."ResourceType" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('r.StandardSystemId', $array);
            });
        }

        if (!empty($filters['curriculumCategory'])) {
            $array = is_array($filters['curriculumCategory']) ? $filters['curriculumCategory'] : [$filters['curriculumCategory']];
            $query2->whereExists(function($query2) use($array) {
                $query2->select(DB::raw(1))
                        ->from('ResourceCurriculumCategories as r')
                        ->whereRaw('r."ResourceId" = "q"."Id" and r."ResourceType" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('r.CurriculumCategoryId', $array);
            });
        }

        if (!empty($filters['testApplicability'])) {
            $array = is_array($filters['testApplicability']) ? $filters['testApplicability'] : [$filters['testApplicability']];
            $query2->whereExists(function($query2) use($array) {
                $query2->select(DB::raw(1))
                        ->from('ResourceTestApplicability as r')
                        ->whereRaw('r."ResourceId" = "q"."Id" and r."ResourceType" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('r.TestApplicabilityId', $array);
            });
        }

        if (!empty($filters['subject'])) {
            $array = is_array($filters['subject']) ? $filters['subject'] : [$filters['subject']];
            $query2->whereExists(function($query2) use($array) {
                $query2->select(DB::raw(1))
                        ->from('Subjectables as sa')
                        ->whereRaw('sa."ResourceId" = "q"."Id" and sa."Subjectable_type" = \'App\Modules\Resources\Models\Question\' ')
                        ->whereIn('sa.SubjectId', $array);
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query2->join('Options AS so', 'q.StatusId', '=', 'so.Id', 'left')
                    ->where('so.Id', $filters['status'])
                    ->where('so.Type', 'Question Status');
        } else {
            $query2->join('Options AS so', 'q.StatusId', '=', 'so.Id', 'left')
                    ->where('so.Type', 'Question Status');
        }

        if (!empty($filters['author'])) {
            $query2->where('q.AuthorId', $filters['author']);
        }
       
        if (!empty($filters['assigned_to'])) {
            $assignedTo = $filters['assigned_to'];
            $query2->whereExists(function($query2) use($assignedTo) {
                $query2->select(DB::raw(1))
                        ->from('UserQuestions as uq')
                        ->whereRaw('uq."QuestionId" = "q"."Id"')
                        ->where('uq.UserId', $assignedTo);
            });
        }

        if (!empty($filters['question_type'])) {
            $query2->join('Options AS qto', 'q.QuestionTypeId', '=', 'qto.Id', 'left')
                    ->where('qto.Id', $filters['question_type'])
                    ->where('qto.Type', 'QuestionTypes');
        } else {
            $query2->join('Options AS qto', 'q.QuestionTypeId', '=', 'qto.Id', 'left')
                    ->where('qto.Type', 'QuestionTypes');
        }

        if (isset($filters['with_passage']) && $filters['with_passage'] == "true") {
            $query2->whereExists(function($query2) {
                $query2->select(DB::raw(1))
                        ->from('QuestionPassages as qp')
                        ->join('Passages as p', 'p.Id', '=', 'qp.PassageId')
                        ->whereRaw('qp."QuestionId" = "q"."Id"');
            });
        }

        if (!empty($filters['passage'])) {
            $query2->join('QuestionPassages as quest_p', 'q.Id', '=', 'quest_p.QuestionId');
            $query2->where('quest_p.PassageId', '=', $filters['passage']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $search = str_replace( "'", "''", $search ); 
            $search = str_replace( '"', '""', $search );
            
            $query2->where(function($query2) use ($search) {
                // $query2->where('Text', 'ilike', '%' . $search . '%');
                $query2->where('Title', 'ilike', '%' . $search . '%');
                $query2->orWhereExists(function($query2) use($search) {
                    $query2->select(DB::raw(1))
                            ->from('ResourceStandardSystems as r')
                            ->join('StandardSystems as s', 's.Id', '=', 'r.StandardSystemId')
                            ->whereRaw('r."ResourceId" = "q"."Id" and r."ResourceType" = \'App\Modules\Resources\Models\Question\' ')
                            ->where('s.Name', 'ilike', '%' . $search . '%');
                });
                $query2->orWhereExists(function($query2) use($search) {
                    $query2->select(DB::raw(1))
                            ->from('MetaTag as t')
                            ->join('Taggables as ta', function($join) {
                                $join->on('ta.TagId', '=', 't.Id');
                                $join->on('ta.Taggable_type', '=', DB::raw('\'App\Modules\Resources\Models\Question\''));
                            })
                            ->whereRaw('ta."ResourceId" = "q"."Id"')
                            ->where('t.Tag', 'ilike', '%' . $search . '%');
                });
                $query2->orWhereExists(function($query2) use($search) {
                    $query2->select(DB::raw(1))
                            ->from('QuestionAnswers as qa')
                            ->whereRaw('qa."QuestionId" = "q"."Id"')
                            ->where('qa.Text', 'ilike', '%' . $search . '%')
                            ->where('qa.Explanation', 'ilike', '%' . $search . '%');
                });
            });
        }

//        $permission = EntrustFacade::can(['question.export']);
//        if (!$permission) {
//            $query2->join('Options AS vo', 'q.VisibilityId', '=', 'vo.Id', 'left')
//                    ->where('vo.Option', 'Public')
//                    ->where('vo.Type', 'Question Visibility');
//        }

        $query2->select('q.*', 'so.Display as status', DB::raw('(SELECT COUNT(*) FROM "QuestionPassages" AS qp where qp."QuestionId" = "q"."Id") as "CountPassages"'), DB::raw('(SELECT string_agg(o."Display" ,\', \')  FROM "Subjectables" sa JOIN "Options" o ON sa."SubjectId" = o."Id"
                        WHERE sa."Subjectable_type" = \'App\Modules\Resources\Models\Question\' 
                        AND sa."ResourceId" = "q"."Id")
                        AS "Subject"'), DB::raw('(SELECT COUNT(*) FROM "Taggables" AS ta JOIN "MetaTag" AS mt ON mt."Id" = ta."TagId" WHERE ta."ResourceId" = q."Id" AND ta."Taggable_type" = \'App\Modules\Resources\Models\Question\') as "Tags"'), 
                        DB::raw('(SELECT COUNT(DISTINCT(ss."Id")) FROM "ResourceStandardSystems" as rss JOIN "StandardSystems" ss on rss."StandardSystemId" = ss."Id" where not exists(select 1 from "StandardSystems" ss2 where ss2."ParentId" = ss."Id" ) and ss.deleted_at is null AND q."Id" = rss."ResourceId" and rss."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(cc."Id")) FROM "ResourceCurriculumCategories" as rcc JOIN "CurriculumCategories" cc on rcc."CurriculumCategoryId" = cc."Id" where not exists(select 1 from "CurriculumCategories" cc2 where cc2."ParentId" = cc."Id" ) and cc.deleted_at is null AND q."Id" = rcc."ResourceId" and rcc."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(ta."Id")) FROM "ResourceTestApplicability" as rta JOIN "TestApplicabilities" ta on rta."TestApplicabilityId" = ta."Id" where not exists(select 1 from "TestApplicabilities" ta2 where ta2."ParentId" = ta."Id" ) and ta.deleted_at is null AND q."Id" = rta."ResourceId" and rta."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "Standards"')//, 
//                        DB::raw('(SELECT COUNT(DISTINCT(cc."Id")) FROM "ResourceCurriculumCategories" as rcc JOIN "CurriculumCategories" cc on rcc."CurriculumCategoryId" = cc."Id" where not exists(select 1 from "CurriculumCategories" cc2 where cc2."ParentId" = cc."Id" ) and cc.deleted_at is null AND q."Id" = rcc."ResourceId" and rcc."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "CurriculumCategories"'), 
//                        DB::raw('(SELECT COUNT(DISTINCT(ta."Id")) FROM "ResourceTestApplicability" as rta JOIN "TestApplicabilities" ta on rta."TestApplicabilityId" = ta."Id" where not exists(select 1 from "TestApplicabilities" ta2 where ta2."ParentId" = ta."Id" ) and ta.deleted_at is null AND q."Id" = rta."ResourceId" and rta."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "TestApplicabilities"')
                        ,DB::raw('CAST (\'\' AS TEXT) AS "Assessments"')
        );

        $query2->join('ProgramResources', function($join) {
            $join->on('ProgramResources.ResourceId', '=', 'q.Id');
            $join->on('ProgramResources.Resource_type', '=', DB::raw('\'App\Modules\Resources\Models\Question\''));
        })->join('ProgramAuthorizedUsers as pau', function($join) {
            $user_id = Auth::user()->id;
            $join->on('pau.ProgramId', '=', 'ProgramResources.ProgramId');
            $join->on('pau.UserId', '=', DB::raw($user_id));
            $join->on('pau.Qbank', '=', DB::raw('\'t\''));
        });
        /////////////////Query 2 End
        // Permissions on Questions
        $user = Auth::user();
        $user_id = $user->id;
        $profile_type = $user->ProfileType;
        $query->where(function($query) use ($user_id, $profile_type, $questionsId) {
            // Visibility Private, User Type Internal AA, Question visible to Internal AA only
            $query->where(function($query) use ($user_id, $profile_type) {
                //$query->where('visibility.Option', '=', 'Private');
                //$query->where('u.ProfileType', '=', 'AA Internal Staff');
                $query->whereRaw("'$profile_type' = 'AA Internal Staff'");
            });
            // Visibility Priveate, User Type AA Tutor,AA Teacher,External Staff: Question visible to Author only
            $query->orWhere(function($query) use ($user_id, $profile_type, $questionsId) {
                $query->where('visibility.Option', '=', 'Private');
                $query->whereIn('u.ProfileType', ['AA Tutor', 'AA Teacher', 'External Staff']);
                $query->whereRaw('"q"."AuthorId" = ' . $user_id);
                if(!empty($questionsId)) {
                    $query->orWhereIn('q.Id', $questionsId);
                }
            });
            // Visibility Public, User Type Internal AA: Question visible to Every one if logged in user is not external staff
            if ($profile_type != 'External Staff') {
                $query->orWhere(function($query) use ($user_id, $profile_type) {
                    $query->where('visibility.Option', '=', 'Public');
                    $query->whereIn('u.ProfileType', ['AA Internal Staff']);
                });
            } else {
                // Show the public, active questions created by internal staff
                $query->orWhere(function($query) use ($user_id, $profile_type) {

                    $qbankId = Option::getOptionId("Item Category", "Questions");      // get fixed form id

                    $query->where('visibility.Option', '=', 'Public');
                    $query->where('so.Display', '=', 'Active');
                    $query->where('q.ItemCategoryId', '=', $qbankId);
                    $query->whereIn('u.ProfileType', ['AA Internal Staff']);
                });
            }

            if($profile_type == TEACHER){
                $fixedFormId = Option::getOptionId("Item Category", "Fixed Form");// get fixed form id
                $query->where('q.ItemCategoryId', '!=', $fixedFormId);
            }


            // Visibility Public, User Type 'AA Teacher', 'AA Tutor', 'External Staff': Question visible to like users in same department
            $query->orWhere(function($query) use ($user_id, $profile_type) {
                $query->where('visibility.Option', '=', 'Public');
                $query->whereIn('u.ProfileType', ['AA Teacher', 'AA Tutor', 'External Staff']);
                $query->whereRaw("u.\"ProfileType\" = '$profile_type'");
                // $query->where(function($query) use ($user_id, $profile_type) {
                //     $query->whereExists(function($query) use($user_id, $profile_type) {
                //         $query->select(DB::raw(1))
                //                 ->from('UserDepartments as ud')
                //                 ->join('UserDepartments as ud2', 'ud.DepartmentId', '=', 'ud2.DepartmentId')
                //                 ->whereRaw('ud."UserId" = "q"."AuthorId"')
                //                 ->whereRaw("ud2.\"UserId\" = $user_id");
                //     });
                //     $query->orWhereExists(function($query) use($user_id, $profile_type) {
                //         $query->select(DB::raw(1))
                //                 ->from('DepartmentTeachers as idt')
                //                 ->join('DepartmentTeachers as idt2', 'idt.DepartmentId', '=', 'idt2.DepartmentId')
                //                 ->whereRaw('idt."TutorId" = "q"."AuthorId"')
                //                 ->whereRaw("idt2.\"TutorId\" = $user_id");
                //     });
                //     $query->orWhereRaw("\"q\".\"AuthorId\" = $user_id ");
                // });
            });
        });

        if (!empty($filters['skip'])) {
            $limit = $filters['limit'];
            $offset = isset($filters['skip']) ? $filters['skip'] : 0;
        }

        if (!empty($id)) {
            $query->whereRaw("\"q\".\"Id\" = $id");
        }

        //$counts = $query->count();
        //$counts = 0;

        
        // if (!empty($filters['sortTo']) && !empty($filters['sortBy'])) {
        //     $query->orderby($filters['sortTo'], $filters['sortBy']);
        // } else {
        //     $query->orderby('Title', 'asc');
        // }

        if ( isset($filters['sorter']) && !empty($filters['sorter']) ) {
            foreach ($filters['sorter'] as $sortCol => $sortVal) {
                $query->orderBy( $sortCol, $sortVal );
                if(strtolower($sortCol)=="text"){
                    $query->orderBy( "Title", $sortVal );
                }
            }
        }

        $query->skip($offset)->take($limit);
        $query->select('q.*', 'so.Display as status', DB::raw('(SELECT COUNT(*) FROM "QuestionPassages" AS qp where qp."QuestionId" = "q"."Id") as "CountPassages"'), DB::raw('(SELECT string_agg(o."Display" ,\', \')  FROM "Subjectables" sa JOIN "Options" o ON sa."SubjectId" = o."Id"
                        WHERE sa."Subjectable_type" = \'App\Modules\Resources\Models\Question\' 
                        AND sa."ResourceId" = "q"."Id")  AS "Subject"'), DB::raw('(SELECT COUNT(*) FROM "Taggables" AS ta JOIN "MetaTag" AS mt ON mt."Id" = ta."TagId" WHERE ta."ResourceId" = q."Id" AND ta."Taggable_type" = \'App\Modules\Resources\Models\Question\') as "Tags"'), 
//AS "Subject"'), DB::raw('(SELECT COUNT(*) FROM "Taggables" AS ta JOIN "MetaTag" AS mt ON mt."Id" = ta."TagId" WHERE ta."ResourceId" = q."Id" AND ta."Taggable_type" = \'App\Modules\Resources\Models\Question\') as "Tags"'), 

                        DB::raw('(SELECT COUNT(DISTINCT(ss."Id")) FROM "ResourceStandardSystems" as rss JOIN "StandardSystems" ss on rss."StandardSystemId" = ss."Id" where not exists(select 1 from "StandardSystems" ss2 where ss2."ParentId" = ss."Id" ) and ss.deleted_at is null AND q."Id" = rss."ResourceId" and rss."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(cc."Id")) FROM "ResourceCurriculumCategories" as rcc JOIN "CurriculumCategories" cc on rcc."CurriculumCategoryId" = cc."Id" where not exists(select 1 from "CurriculumCategories" cc2 where cc2."ParentId" = cc."Id" ) and cc.deleted_at is null AND q."Id" = rcc."ResourceId" and rcc."ResourceType" = \'App\Modules\Resources\Models\Question\' ) + (SELECT COUNT(DISTINCT(ta."Id")) FROM "ResourceTestApplicability" as rta JOIN "TestApplicabilities" ta on rta."TestApplicabilityId" = ta."Id" where not exists(select 1 from "TestApplicabilities" ta2 where ta2."ParentId" = ta."Id" ) and ta.deleted_at is null AND q."Id" = rta."ResourceId" and rta."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "Standards"'), 
//                        DB::raw('(SELECT COUNT(DISTINCT(cc."Id")) FROM "ResourceCurriculumCategories" as rcc JOIN "CurriculumCategories" cc on rcc."CurriculumCategoryId" = cc."Id" where not exists(select 1 from "CurriculumCategories" cc2 where cc2."ParentId" = cc."Id" ) and cc.deleted_at is null AND q."Id" = rcc."ResourceId" and rcc."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "CurriculumCategories"'), 
//                        DB::raw('(SELECT COUNT(DISTINCT(ta."Id")) FROM "ResourceTestApplicability" as rta JOIN "TestApplicabilities" ta on rta."TestApplicabilityId" = ta."Id" where not exists(select 1 from "TestApplicabilities" ta2 where ta2."ParentId" = ta."Id" ) and ta.deleted_at is null AND q."Id" = rta."ResourceId" and rta."ResourceType" = \'App\Modules\Resources\Models\Question\' ) as "TestApplicabilities"'), 

                        DB::raw('CAST (\'\' AS TEXT) AS "Assessments"')

        );
        $profileType = Auth::user()->ProfileType;
        if ($profileType == 'External Staff') {
//             echo $query2->union($query)->toSql(); die();
            $result = $query2->union($query)->get();
        } else {
            $result = $query->get();
        }
        
        return $result;
    }

    public static function getFilesAssignments($filter){
       $query = DB::table('users as u')
          ->join('Students as s', 'u.id','=','s.UserId' )
          ->join('AssignmentStudents as testApp', function($join){
              $join->on('testApp.UserId', '=', 'u.id');
             })
          ->join('Assignments as a','a.Id','=', 'testApp.AssignmentId')
          ->join('ResourceAssignments as ra', function($join){
              $join->on('a.Id', '=', 'ra.AssignmentId');
//              $join->on('ra.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\ResourceTestType'"));
            })
          ->join('Files as f',function($join){
              $join->on('ra.ResourceId', '=', 'f.Id');
              $join->on('ra.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
            })
          ->leftJoin('ResourceTestTypes as rt ',function($join){
              $join->on('rt.ResourceType', '=', DB::raw("'App\Modules\Resources\Models\File'"));
              $join->on('rt.ResourceId', '=', 'f.Id');
             })
          // ->join('Files as f',function($join){
          //     $join->on('rt.ResourceId', '=', 'f.Id');
          //   })
          ->leftJoin('Options as testType', 'rt.TestTypeId', '=', 'testType.Id')
          ->leftJoin('Options as subj', 'rt.SubjectId', '=', 'subj.Id')
          ->leftJoin('Options as fileType', 'f.FileTypeId', '=', 'fileType.Id') 
          ->select(
              'f.Id as file_id',
              'f.path as file_path',
              'ra.AssignmentId as assignment_id' ,  
              'f.Name as file_name',
              'f.Format as file_format',
              DB::raw("string_agg(DISTINCT \"subj\".\"Display\", ', ') as \"subject\""),
              "a.EndDate as expiration_date",
              "fileType.Display as file_type"
              );
        $query->where('a.EndDate','>=',date('Y-m-d'));
        $query->whereNotNull('a.EndDate');
        
        if(isset($filter['testTypeId']) && !empty($filter['testTypeId'])){
          $query->where('testType.Id','=',$filter['testTypeId']);   
        }

        if (isset($filter['subjectId']) && !empty( $filter['subjectId'])) {
            $query->where('subj.Id', '=', $filter['subjectId']);
        }

        if(isset($filter['search']) && !empty( $filter['search'])){
            $search = $filter['search'];
            $query->where(function($query) use($search){
              $query->where('f.Name', 'ILIKE', '%' . $search . '%');
              $query->orWhere('f.Format', 'ILIKE', '%' . $search . '%');   
            });
            $query->orWhere('fileType.Display', 'ILIKE', '%' . $search . '%');
            $query->orWhere('subj.Display', 'ILIKE', '%' . $search . '%');
            $query->orWhereRaw('to_char(a."EndDate", \'YYYY-MM-DD\') ILIKE '.'\'%' . $search . '%\'');
        }

        $query->whereNull('a.deleted_at');
        
        if (!empty($filter['sortBy'])) {
            if (isset($filter['sortBy']['Name'])){
                $query->orderBy('f.Name',$filter['sortBy']['Name']);
            } elseif(isset($filter['sortBy']['Type'])){ 
                $query->orderBy('fileType.Display',$filter['sortBy']['Type']);
            } elseif(isset($filter['sortBy']['Format'])){
               $query->orderBy('f.Format',$filter['sortBy']['Format']);
            } elseif(isset($filter['sortBy']['Subject'])){
              $query->orderBy('subject',$filter['sortBy']['Subject']);
            } else{
              $query->orderBy('a.EndDate',$filter['sortBy']['EndDate']);
            }
        }

                // Each column that is in the select, has to be in the group by
        $query->groupBy(
            'f.Id',
            'f.path',
            'ra.AssignmentId',
            'f.Name',
            'f.Format',
            'a.EndDate',
            'fileType.Display'
        );
        // if(isset($filter['sortBy']['Subject'])){
        //           $query->groupBy(
        //             'subj.Display'
        //           );
        // }
        $userID = isset( $filter['userId'] ) ? ($filter['userId']) : [Auth::user()->id];
        $query->whereIn('u.id', $userID)->whereNull('f.deleted_at');

        /**
         * For first two scrolls 20 results will be returned for each scroll
         * After the first two scrolls, 18 results will be returned for each of the request
         */
        $toTake = 10;
        $toSkip = isset( $filter['skip'] ) ? (int)($filter['skip']) : 0;
        // After two passes (which means when first ten records have been skipped)
        // We need to take the 18 records for each of the request
        if ( $toSkip >= 20 ) {
            $toTake = 18;
        }
         // die($query->take( $toTake )->skip( $toSkip )->toSql());
        return $query->skip( $toSkip )->take( $toTake )->get();

    }

    public static function getProgramRosterStudents($filters = [])
    {
      $rosterStudents = DB::table('Programs')
                        ->join('ProgramRosters as programRosters', 'programRosters.ProgramId', '=', 'Programs.Id')
                        ->join('users as users', function ($join) {
                            $join->on('users.id', '=', 'programRosters.UserId')
                            ->where('users.ProfileType', '=', 'Student')
                            ->whereNull('users.deleted_at')
                            ->where('users.Status', '=', 'Active');
                        })
                        ->leftjoin('Students as stds', 'stds.UserId', '=', 'users.id')
                        ->leftjoin('Options as optns', 'optns.Id', '=', 'stds.GradeId')
                        ->where(function($query){
                            $query->where('stds.IsGraduated','=','No')
                                ->orWhereNull('stds.IsGraduated');
                        })
                        ->whereIn('Programs.Id', $filters['programId']);

      if (isset($filters['IdsToIgnore'])) {
          $rosterStudents->whereNotIn('users.id', $filters['IdsToIgnore']);
      }
      if (!empty($filters['sortTo']) && !empty($filters['sortBy'])) {
          $rosterStudents->orderby($filters['sortTo'], $filters['sortBy']);
      } else {
          $rosterStudents->orderBy('users.name');
      }

      $rosterStudents->groupBy('users.id', 'users.FirstName', 'users.LastName', 'users.email', 'users.UserName', 'optns.Display');
      $rosterStudents->select([DB::raw("CONCAT(\"FirstName\", ' ', \"LastName\") AS fullName"), 'users.id', 'users.UserName as userName', 'users.email as email', 'optns.Display as grade']);
      //$students = $rosterStudents->get();
      if (isset($filters['onlyIds'])) {
        $students = $rosterStudents->lists("users.id");
      }
      else
      {
        $students = $rosterStudents->get();
      }  
      return $students;
    }
    public static function getSchoolSectionRosterStudents($schoolsectionIds = [])
    {
      $schoolsections = []; 
      foreach ($schoolsectionIds as $id) {
        $schoolsections[] = $id.'_App-Modules-Programs-Models-ProgramSchoolSection';
      }

      $programSectionUsers = new ProgramSectionUser();
      $students = $programSectionUsers->fetchSectionUsersForReports($schoolsections); 
      return $students;
    }

    public static function getAssessmentYou($userId = null ) {
        $filters['Assigned-To'] = $userId;
        $assessmentList = Dashboard::getAssessmentAssigned($filters);

        return $assessmentList;
    }

    public static function getAssessmentAssigned($filters = array()){
      $offset = 0;
        $limit = 10;
        $obj = DB::table('Assessments')
                ->join('Options as status','Assessments.StatusId','=','status.Id', 'left')
                ->join('Options as c','Assessments.CategoryId','=','c.Id', 'left')
                ->join('Options as testType','Assessments.TestTypeId','=','testType.Id', 'left')
                ->join('Subsections as section','Assessments.Id','=','section.AssessmentId')
                ->leftjoin('users as assignee','Assessments.Assignee','=', 'assignee.id')
                ->leftjoin('users as createdby','Assessments.CreatedBy','=','createdby.id')
                ->leftjoin('AssessmentAssignment as aa','aa.assessmentId','=', 'Assessments.Id')
                ->leftjoin('Taggables as tga', function($join) {
                    $join->on('Assessments.Id', '=', 'tga.ResourceId');
                    $join->on('tga.Taggable_type', '=', DB::raw('\'App\Modules\Assessment\Models\Assessment\''));
                })
                ->leftjoin('MetaTag as mt', 'tga.TagId', '=', 'mt.Id')
                ->where('Assessments.deleted_at',null);
        if(!empty($filters['standardSystems'])){
            $array = explode(',', $filters['standardSystems']);
            $obj->whereExists(function($query) use($array)
            {
                $query->select(DB::raw(1))
                    ->from('ResourceStandardSystems as r')
                    ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\' and r."ResourceId" = "Assessments"."Id" ')
                    ->whereIn('r.StandardSystemId', $array)
                  ;
            });
        }
        if(!empty($filters['curriculumCategory'])){
            $array = explode(',', $filters['curriculumCategory']);
            $obj->whereExists(function($query) use($array)
            {
                $query->select(DB::raw(1))
                    ->from('ResourceCurriculumCategories as r')
                    ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\'  and r."ResourceId" = "Assessments"."Id" ')
                    ->whereIn('r.CurriculumCategoryId', $array);
            });
        }
        if(!empty($filters['testApplicability'])){
            $array = explode(',', $filters['testApplicability']);
            $obj->whereExists(function($query) use($array)
            {
                $query->select(DB::raw(1))
                    ->from('ResourceTestApplicability as r')
                    ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\'  and r."ResourceId" = "Assessments"."Id" ')
                    ->whereIn('r.TestApplicabilityId', $array);
                });
            }

        if(!empty($filters['Assigned-To'])){
                $obj->where('Assignee', $filters['Assigned-To']);
        }

        if(!empty($filters['Author'])){
            if($filters['Author'] == 'institutions'){
                $obj->join('users', 'users.id', '=', 'Assessments.CreatedBy')
                    ->where('users.ProfileType', '=' , db::raw('\'External Staff\''));
            }
            else{
                $obj->where('CreatedBy', $filters['Author']);
            }
            
        }
        
        if(!in_array(Auth::user()->ProfileType,  array('AA Internal Staff', 'AA Tutor')) ){
                $obj->where('c.Option', '!=', 'Fixed Form');
        }

        if(!empty($filters['category'])){
            $category = $filters['category'];
            $obj->where('CategoryId', $filters['category']);
        }
        if(!empty($filters['testType'])){
            $obj->where('TestTypeId', $filters['testType']);
        }

        $isSubjectSelected = false;
         if (!empty($filters['select_subjects'])) {
            if ($filters['select_subjects'] != '0') {
                $obj->join('Subsections as subsections', 'Assessments.Id', '=', 'subsections.AssessmentId')
                    ->join('Options as optSubjects','subsections.SubjectId','=','optSubjects.Id')
                    ->where('subsections.SubjectId', $filters['select_subjects']);
                $isSubjectSelected = true;
            }
        }
        else{
            $obj->join('Subsections as subsections1', 'Assessments.Id', '=', 'subsections1.AssessmentId')
                ->join('Options as optSubjects','subsections1.SubjectId','=','optSubjects.Id');
             $isSubjectSelected = true;
        }
        if (!empty($filters['testSubject'])) {
            if ($isSubjectSelected == false) {
                $obj->join('Subsections as subsections', 'Assessments.Id', '=', 'subsections.AssessmentId')
                    ->join('Options as optSubjects','subsections.SubjectId','=','optSubjects.Id')
                    ->where('subsections.SubjectId', $filters['testSubject']);
                    $isSubjectSelected = true;
                } else {
                $obj->join('Subsections as subsections2', 'Assessments.Id', '=', 'subsections2.AssessmentId');
                $obj->where('subsections2.SubjectId', $filters['testSubject']);
            }
        }

        if(!empty($filters['status'])){
            $obj->where('StatusId', $filters['status']);
        }
//        echo $filters['search']; die();
        
        if(!empty($filters['search'])){
            if ($isSubjectSelected == false) {
                    $obj->join('Subsections as subsections', 'Assessments.Id', '=', 'subsections.AssessmentId')
                        ->join('Options as optSubjects','subsections.SubjectId','=','optSubjects.Id')
                        ->where('subsections.SubjectId', $filters['testSubject']);
                        $isSubjectSelected = true;
                }
            
            $obj->where(function($query) use ($filters){
                $query->where('status.Display', 'ilike', '%'.$filters['search'].'%' );
                $query->orWhere('c.Display', 'ilike', '%'.$filters['search'].'%');
                $query->orWhere('Assessments.Title', 'ilike', '%'.$filters['search'].'%');
                $query->orWhere(function ( $q ) use ( $filters ) {
                    $q->where('mt.Tag', 'ilike', '%' . $filters['search'] . '%');
                });
                $query->orWhereExists(function($query) use($filters)
                {
                    $query->select(DB::raw(1))
                        ->from('ResourceStandardSystems as r')
                        ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\'  and r."ResourceId" = "Assessments"."Id"  and r."ResourceId" = "Assessments"."Id" ')
                        ->join('StandardSystems AS ss', 'ss.Id', '=', 'r.StandardSystemId')
                        ->where('ss.Name','ilike', '%'.$filters['search'].'%');
                });
                
                $query->orWhereExists(function($query) use($filters)
                {
                    $query->select(DB::raw(1))
                        ->from('ResourceCurriculumCategories as r')
                        ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\'  and r."ResourceId" = "Assessments"."Id" ')
                        ->join('CurriculumCategories as cc','cc.Id','=','r.CurriculumCategoryId')
                        ->where('cc.Name','ilike', '%'.$filters['search'].'%');                
                });
                
                $query->orWhereExists(function($query) use($filters)
                {
                $query->select(DB::raw(1))
                    ->from('ResourceTestApplicability as r')
                    ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\'  and r."ResourceId" = "Assessments"."Id" ')
                    ->join('TestApplicabilities AS ta', 'ta.Id', '=', 'r.TestApplicabilityId')
                    ->where('ta.Name','ilike', '%'.$filters['search'].'%');

                });

                $query->orWhere('assignee.name', 'ilike', '%'.$filters['search'].'%');
                $query->orWhere('createdby.name', 'ilike', '%'.$filters['search'].'%');
                $query->orWhere('testType.Display', 'ilike', '%'.$filters['search'].'%');
                $query->orWhere('optSubjects.Display', 'ilike', '%'.$filters['search'].'%');
            });
        }

        if(!empty($filters['skip'])){
            $limit = $filters['limit'];
            $offset = isset($filters['skip']) ? $filters['skip'] : 0;
        }
        if(!empty($filters['sortTo']) && !empty($filters['sortBy'])){
            if($filters['sortTo']=='Title')
                $filters['sortTo'] = 'title';
            
            $obj->orderby($filters['sortTo'],$filters['sortBy']);
        }else{
            $obj->orderBy('title');
        }
        //Permissions
        $obj->where(function($query){
                $userId = Auth::user()->id;
                $profileType = Auth::user()->ProfileType;
                $query->where('status.Option', '!=', 'Draft' );
                $query->orWhere('Assessments.CreatedBy', '=', $userId );
                $query->orWhereIn(DB::raw('\''.$profileType.'\''), ['AA Internal Staff', 'AA Tutor', 'AA Teacher']);
        });
        $obj->skip($offset)->take($limit);
        $obj->groupby('Assessments.Id','status.Display','c.Display','testType.Display');
        $obj->select([
                    DB::raw("string_agg(DISTINCT \"optSubjects\".\"Display\", ', ') as subjects"),
                    DB::raw("COUNT( \"aa\".\"assessmentId\") as assessmentCount"),
                    'Assessments.Id', 
                    'Assessments.PrintViewFile', 
                    'status.Display as status', 
                    'c.Display as category', 
                    'Assessments.Title as title', 
                    'testType.Display as testType',
                    DB::raw('min(section."Id") as "sectionId"'),
                    DB::raw('count(distinct mt."Id") as "tagCount"')
                ]);
//                      echo $obj->toSql(); die();  
////////////////// 2nd Validation Start
        
         $obj2 = DB::table('Assessments')
                ->join('Options as status','Assessments.StatusId','=','status.Id', 'left')
                ->join('Options as c','Assessments.CategoryId','=','c.Id', 'left')
                ->join('Options as testType','Assessments.TestTypeId','=','testType.Id', 'left')
                ->join('Subsections as section','Assessments.Id','=','section.AssessmentId')
                ->leftjoin('AssessmentAssignment as aa','aa.assessmentId','=', 'Assessments.Id')
                ->leftjoin('Taggables as tga', function($join) {
                    $join->on('Assessments.Id', '=', 'tga.ResourceId');
                    $join->on('tga.Taggable_type', '=', DB::raw('\'App\Modules\Assessment\Models\Assessment\''));                    
                })
                ->leftjoin('MetaTag as mt', 'tga.TagId', '=', 'mt.Id')
                ->where('Assessments.deleted_at',null);
        if(!empty($filters['standardSystems'])){
            $array = explode(',', $filters['standardSystems']);
            $obj2->whereExists(function($query) use($array)
            {
                $query->select(DB::raw(1))
                    ->from('ResourceStandardSystems as r')
                    ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\' ')
                    ->whereIn('r.StandardSystemId', $array)
                  ;
            });
        }
        if(!empty($filters['curriculumCategory'])){
            $array = explode(',', $filters['curriculumCategory']);
            $obj2->whereExists(function($query) use($array)
            {
                $query->select(DB::raw(1))
                    ->from('ResourceCurriculumCategories as r')
                    ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\' ')
                    ->whereIn('r.CurriculumCategoryId', $array);
            });
        }
        if(!empty($filters['testApplicability'])){
            $array = explode(',', $filters['testApplicability']);
            $obj2->whereExists(function($query) use($array)
            {
                $query->select(DB::raw(1))
                    ->from('ResourceTestApplicability as r')
                    ->whereRaw('r."ResourceType" = \'App\Modules\Assessment\Models\Assessment\' ')
                    ->whereIn('r.TestApplicabilityId', $array);
                });
            }

        if(!empty($filters['Assigned-To'])){
                $obj2->where('Assignee', $filters['Assigned-To']);
        }
//echo $filters['Author']; die();
        if(!empty($filters['Author'])){
            if(is_int($filters['Author'])){
                $obj2->where('CreatedBy', $filters['Author']);
            }
            elseif($filters['Author'] == 'institutions'){
                $obj2->join('users', 'users.id', '=', 'Assessments.CreatedBy')
                    ->where('users.ProfileType', '=' , db::raw('\'External Staff\''));
            }
            
        }

        if(!empty($filters['category'])){
            $category = $filters['category'];
            $obj2->where('CategoryId', $filters['category']);
        }
        if(!empty($filters['testType'])){
            $obj2->where('TestTypeId', $filters['testType']);
        }

        $isSubjectSelected = false;
         if (!empty($filters['select_subjects'])) {
            if ($filters['select_subjects'] != '0') {
                $obj2->join('Subsections as subsections', 'Assessments.Id', '=', 'subsections.AssessmentId')
                    ->join('Options as optSubjects','subsections.SubjectId','=','optSubjects.Id')
                    ->where('subsections.SubjectId', $filters['select_subjects']);
                $isSubjectSelected = true;
            }
        }
        else{
            $obj2->join('Subsections as subsections1', 'Assessments.Id', '=', 'subsections1.AssessmentId')
                ->join('Options as optSubjects','subsections1.SubjectId','=','optSubjects.Id');
        }
        if (!empty($filters['testSubject'])) {
            if ($isSubjectSelected == false) {
                $obj2->join('Subsections as subsections', 'Assessments.Id', '=', 'subsections.AssessmentId')
                    ->where('subsections.SubjectId', $filters['testSubject']);
                } else {
                $obj2->orWhere('subsections.SubjectId', $filters['testSubject']);
            }
        }

        if(!empty($filters['status'])){
            $obj2->where('StatusId', $filters['status']);
        }
//        echo $filters['search']; die();
        
        if(!empty($filters['search'])){
            $obj2->where(function($query) use ($filters){
                $query->where('status.Display', 'ilike', '%'.$filters['search'].'%' );
                $query->orWhere('c.Display', 'ilike', '%'.$filters['search'].'%');
                $query->orWhere('Assessments.Title', 'ilike', '%'.$filters['search'].'%');                
                $query->orWhere(function ( $q ) use ( $filters ) {
                    $q->where('mt.Tag', 'ilike', '%' . $filters['search'] . '%');
                });
            });
        }

        
        
        $obj2->groupby('Assessments.Id','status.Display','c.Display','testType.Display');
        $obj2->select([
                    DB::raw("string_agg(DISTINCT \"optSubjects\".\"Display\", ', ') as subjects"),
                    DB::raw("COUNT( \"aa\".\"assessmentId\") as assessmentCount"),
                    'Assessments.Id', 
                    'Assessments.PrintViewFile', 
                    'status.Display as status', 
                    'c.Display as category', 
                    'Assessments.Title as title', 
                    'testType.Display as testType',
                    DB::raw('min(section."Id") as "sectionId"'),
                    DB::raw('count(distinct mt."Id") as "tagCount"')            
                ]);
        
                    $obj2->join('ProgramResources', function($join){
                            $join->on('ProgramResources.ResourceId','=','Assessments.Id');
                            $join->on('ProgramResources.Resource_type', '=', DB::raw('\'App\Modules\Assessment\Models\Assessment\''));
                        })
                        ->join('ProgramAuthorizedUsers as pau', function($join) {
                            $user_id = Auth::user()->id ;
                            $join->on('pau.ProgramId','=','ProgramResources.ProgramId');
                            $join->on('pau.UserId','=',DB::raw($user_id));
                            $join->on('pau.Assessment','=',DB::raw('\'t\''));
                        });
        $profile_type = Auth::user()->ProfileType;
        if($profile_type == 'External Staff'){
            $result = $obj2->union($obj)->get(); 
        }else{
            $result = $obj->get();
        }
////////////////// 2nd Validation End

        return $result;
    }
}




