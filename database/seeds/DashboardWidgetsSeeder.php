<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DashboardWidgetsSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

        DB::table('dashboard_widgets')->delete();

        $columns = $this->tableKeys();
        $fields  = $this->tableRows();

        foreach($fields as $row) {

            $values = [];
            $rowContent = explode(',', $row);

            for ($i = 0; $i < 23; $i++) {
                $values[$columns[$i]] = $rowContent[$i];
            }

            \App\Modules\dashboard\Models\DashboardWidgets::create([
                'is_fusion_chart' => $values['is_fusion_chart'],
                'widget_div_id' => $values['widget_div_id'],
                'widget_template' => $values['widget_template'],
                'widget_type' => $values['widget_type'],
                'widget_headline' => $values['widget_headline'],
                'widget_text' => $values['widget_text'],
                'user_type' => $values['user_type'],
                'width'  => $values['width'],
                'height'  => $values['height'],
                'class' => $values['class'],
                'color_1'  => $values['color_1'],
                'color_2' => $values['color_2'],
                'color_3' => $values['color_3'],
                'color_4' => $values['color_4'],
                'color_5' => $values['color_5'],
                'params' => $values['params'],
                'is_three_axis'  => $values['is_three_axis'],
                'axis_x_title' => $values['axis_x_title'],
                'axis_y_title' => $values['axis_y_title'],
                'axis_y1_title' => $values['axis_y1_title'],
                'has_button' => $values['has_button'],
                'button_text' => $values['button_text'],
                'button_link' => $values['button_link']
            ]);
        }

    }

    private function tableKeys() {
        $keys = [
            'is_fusion_chart',
            'widget_div_id',
            'widget_template', //partial template to use
            'widget_type', //fusion charts type
            'widget_headline',
            'widget_text',
            'user_type',
            'width',
            'height',
            'class',
            'color_1',
            'color_2',
            'color_3',
            'color_4',
            'color_5',
            'params',
            'is_three_axis',
            'axis_x_title',
            'axis_y_title',
            'axis_y1_title',
            'has_button',
            'button_text',
            'button_link'
        ];

        return $keys;
    }

    private function tableRows() {
        $rows = [
            'true,most-recent-act-test,chart,Column2D,Most Recent ACT Test,NULL,all,574,300,NULL,#CB7263,#EDD131,#E5AA28,#B5D168,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,TRUE,View Reports,/reports',
            'true,act-history,graph3axis,MultiAxisLine,ACT History,NULL,all,574,300,NULL,#CB7264,#EDD132,#E5AA29,#B5D169,#4D7C9B,NULL,TRUE,NULL,NULL,NULL,TRUE,View Reports,/reports',
            'false,act-plans,plans,plans,ACT Plans,NULL,all,574,300,NULL,#CB7265,#EDD133,#E5AA30,#B5D170,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'true,most-recent-act-growth,compchart,Column2D,Most Recent ACT Growth,NULL,all,574,300,NULL,#CB7266,#EDD134,#E5AA31,#B5D171,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,TRUE,View Reports,/reports',
            'false,assignments,table,table,Assignments,NULL,all,574,300,NULL,#CB7267,#EDD134,#E5AA31,#B5D171,#4D7C9B,"Name|Due Date",FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,upcoming-events,table,table,Upcoming Events,NULL,all,574,300,NULL,#CB7268,#EDD135,#E5AA32,#B5D172,#4D7C9B,"Name|Due Date",FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'true,most-recent-sat-test,chart,Column2D,Most Recent SAT Test,NULL,all,574,300,NULL,#CB7269,#EDD136,#E5AA33,#B5D173,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,TRUE,View Reports,/reports',
            'true,sat-history,graph3axis,MultiAxisLine,SAT History,NULL,all,574,300,NULL,#CB7270,#EDD137,#E5AA34,#B5D174,#4D7C9B,NULL,TRUE,NULL,NULL,NULL,TRUE,View Reports,/reports',
            'false,sat-plans,plans,plans,SAT Plans,NULL,all,574,300,NULL,#CB7271,#EDD138,#E5AA35,#B5D175,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'true,sat-growth,compchart,Column2D,Most Recent SAT Growth,NULL,all,574,300,NULL,#CB7272,#EDD139,#E5AA36,#B5D176,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,TRUE,View Reports,/reports',
            'false,recently-viewed-files,list,list,Recently Viewed Files,NULL,all,574,300,NULL,#CB7273,#EDD140,#E5AA37,#B5D177,#4D7C9B,Name,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,recently-viewed-lessons,list,list,Recently Viewed Lessons,NULL,all,574,300,NULL,#CB7274,#EDD141,#E5AA38,#B5D178,#4D7C9B,Name,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,programs-assigned,table,table,Programs Assigned to You,NULL,admin,574,300,NULL,#CB7275,#EDD142,#E5AA39,#B5D179,#4D7C9B,"Name|Role",FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,recently-viewed-programs,list,list,Recently Viewed Programs,NULL,admin,574,300,NULL,#CB7276,#EDD143,#E5AA40,#B5D180,#4D7C9B,Name,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,recently-viewed-questions,list,list,Recently Viewed Questions,NULL,admin,574,300,NULL,#CB7277,#EDD144,#E5AA41,#B5D181,#4D7C9B,Name,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,create-program,create,create,Create a Program,Lorem ipsum dolor sit amet…,admin,574,300,NULL,#CB7278,#EDD145,#E5AA42,#B5D182,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,TRUE,Create Program,/programs/create',
            'false,create-assessment,create,create,Create an Assessment,Lorem ipsum dolor sit amet…,admin,574,300,NULL,#CB7279,#EDD146,#E5AA43,#B5D183,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,TRUE,Create Assessment,/assessment/add/general',
            'false,create-question,create,create,Create a Question,lorem ipsum dolor sit amet…,admin,574,300,NULL,#CB7280,#EDD147,#E5AA44,#B5D184,#4D7C9B,NULL,FALSE,NULL,NULL,NULL,TRUE,Create Question,/resources/qbank/add/question',
            'false,review-assessments,list,list,Assessments to Review ,NULL,admin,574,300,NULL,#CB7281,#EDD148,#E5AA45,#B5D185,#4D7C9B,Name,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,recent-used-assessments,list,list,Recently Viewed Assessments,NULL,admin,574,300,NULL,#CB7282,#EDD149,#E5AA46,#B5D186,#4D7C9B,Name,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,roster,table,table,Roster,NULL,admin,574,300,NULL,#CB7283,#EDD150,#E5AA47,#B5D187,#4D7C9B,"Name|Test Score|Growth",FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,assessments-yours,table,table,Assessments Assigned to You,NULL,admin,574,300,NULL,#CB7284,#EDD151,#E5AA48,#B5D188,#4D7C9B,"Name|Role",FALSE,NULL,NULL,NULL,FALSE,NULL,NULL',
            'false,lessons,list,list,Lessons,NULL,admin,574,300,NULL,NULL,NULL,NULL,NULL,NULL,Name,FALSE,NULL,NULL,NULL,FALSE,NULL,NULL'
        ];

        return $rows;
    }
}