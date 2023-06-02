<?php
class block_moodleblock extends block_base {
    public function init() {
        $this->title = get_string('moodleblock', 'block_moodleblock');
    }

     public function applicable_formats() {
        return array('site-index' => true, 'course-view-*' => true);
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        
        global $OUTPUT,$COURSE,$DB,$USER,$CFG;

        if (!is_null($this->content)) {
            return $this->content;
        }


        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text   = '';
        $contextid = $DB->get_field('context','id',array('instanceid' => $COURSE->id, 'contextlevel' => 50));
        $existid = $DB->get_field('role_assignments','id',array('contextid' => $contextid, 'roleid' => 5, 'userid' => $USER->id));

        if(!is_siteadmin() && $existid > 0){
            $cmmodules = $DB->get_records('course_modules',array('course' => $COURSE->id));
            if($cmmodules){
    	        $table = new html_table();
    	        $table->head =  array(get_string('cmid','block_moodleblock'),get_string('activityname','block_moodleblock'),get_string('dateofcreation','block_moodleblock'),get_string('completionstatus','block_moodleblock'));
    	        $data =  array();
    	        foreach ($cmmodules as $key => $cmmodule) {
    	 			$row = array();
    	 			$completionstate = $DB->get_record('course_modules_completion',array('coursemoduleid' =>  $cmmodule->id,'userid' => $USER->id,'completionstate' => 1));
    	 			$modulename = $DB->get_field('modules','name',array('id' => $cmmodule->module));
    	 			$activityname = $DB->get_record($modulename,array('id' => $cmmodule->instance));
    	 			$row[] = $cmmodule->id;
                    $row[] = html_writer::link(new moodle_url('/mod/'.$modulename.'/view.php?id='.$cmmodule->id), $activityname->name, array('target'=>"_blank")); 
    	 			$row[] = $activityname->timemodified ? date('d-m-Y',$activityname->timemodified) : 'NA';
    	 			$row[] = $completionstate ? get_string('completed','block_moodleblock') : '--';
    	 			$data[] =  $row;
    	        }
    	        $table->data = $data;
    	        $this->content->text = html_writer::table($table);
    	    }
        }
        return $this->content;
    }

}