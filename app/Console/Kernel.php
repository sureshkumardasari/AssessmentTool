<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();

		/**update assignment status**/				 
		$schedule->call(function () {
            	
            try {
        		$obj = new AssignmentUser();
        		$obj->updateAssignmentUserStatus();
            } 
            catch (Exception $e) {
                echo ($e->getMessage());
                sendEmail('Left Out Assignment Sections Cron - Failed', $e->getMessage(), 'srevu@appstekcorp.com');
            }  

        })->when(function(){
        	$subsectionTimeType = 'Minutes';
        	$subsectionUserStatuses = AssignmentUser::join('assignment', 'assignment.id','=','assignment_user.assignment_id')
            ->join('assessment', function($query){ 
                $query->on('assessment.id','=','assignment.assessment_id')
                ->where('neverexpires','=','0')
                ->where('totaltime','>','0');
            })
            ->where('isgraded', false)->whereNotNull('starttime')->where('starttime','>', 0)->limit(5)->get();

            if(count($subsectionUserStatuses) > 0){
            	$leftOutSection = false;
                foreach($subsectionUserStatuses as $subsectionUserStatus){
                    //getting section start time
                    $sectionStartTime = strtotime($subsectionUserStatus->starttime);
                    //getting current time
                    $currentTime = time();
                    //getting time passed
                    $timePassed = $currentTime - $sectionStartTime;
                    //getting subsection total time
                    $subsectionTotalTime = $subsectionUserStatus->totaltime;
                    if($subsectionTimeType == 'Minutes'){
                        $subsectionTotalTime = $subsectionTotalTime * 60;
                    }
                    else{
                        if($subsectionTimeType == 'Hours'){
                            $subsectionTotalTime = $subsectionTotalTime * 60 * 60;
                        }
                    }
                    if($timePassed >= 0 && $timePassed > $subsectionTotalTime){
                        $assessmentAssignmentUser = AssignmentUser::where('assignment_id', '=', $subsectionUserStatus->assignment_id)
                                  ->where('user_id', $subsectionUserStatus->user_id)->first();
                        $assessmentAssignmentUser->gradeprogress = 'processed';
                        $assessmentAssignmentUser->save();
                        
                        $leftOutSection = true;
                    }
                }

                if($leftOutSection){
                    echo "Processing...\n";
                    return true;
                }
                else{
                    echo "No Left out Assignment Sections\n";
                    return false;
                }
            }else{
                echo "No Ungraded Assignment\n";
                return false;
            }
            //end when of cron
        });
        /**update assignment status**/				 
	}

}
