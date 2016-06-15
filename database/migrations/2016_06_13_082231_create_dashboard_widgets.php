<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardWidgets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dashboard_widgets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('is_fusion_chart')->default(false);
			$table->string('widget_type');
			$table->string('widget_template');
			$table->string('widget_div_id')->unique();
			$table->string('widget_headline');
			$table->longText('widget_text')->nullable();
			$table->string('user_type')->default('all');
			$table->string('width')->default('574');
			$table->string('height')->default('300');
			$table->string('class')->nullable();
			$table->string('color_1')->nullable();
			$table->string('color_2')->nullable();
			$table->string('color_3')->nullable();
			$table->string('color_4')->nullable();
			$table->string('color_5')->nullable();
			$table->string('params')->nullable();
			$table->boolean('is_three_axis')->default(false);
			$table->string('axis_x_title')->nullable();
			$table->string('axis_y_title')->nullable();
			$table->string('axis_y1_title')->nullable();
			$table->boolean('has_button')->default(false);
			$table->string('button_text')->nullable();
			$table->string('button_link')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dashboard_widgets');
	}

}
