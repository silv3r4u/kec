<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
     public function __construct()
     {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                
                header('Content-type: text/json');
     }
 
 /* student login */ 
 
public function login(){
     //$_POST = $_REQUEST;
     
        $this->load->library('form_validation');
        $this->form_validation->set_rules('student_user_name', 'User Name', 'trim|required');
        $this->form_validation->set_rules('student_password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
  		{
  		              $data["responce"] = false;
        			  $data["error"] = $this->form_validation->error_string();
  		}
  		else
  		{        
	               $q = $this->db->query("Select student_id,school_id,student_user_name,student_status,student_unique_no,student_name,student_birthdate,student_roll_no,student_standard,student_address,student_city,student_phone,student_parent_phone,student_email,student_photo,student_branch,student_semester,student_division,student_batch from `student_detail` where `student_user_name`='".$this->input->post("student_user_name")."' and student_password='".md5($this->input->post("student_password"))."' Limit 1");
                   $student = $q->row();             
                   if (!empty($student))
                   {
                        if($student->student_status == "0")
                        {
                            $data["responce"] = false;
                            $data["error"] = 'Your account currently inactive';
                        }
                        else
                        {
                           
                            $data["data"] = $student;
                            $data["responce"] = true;             
                        }
                    }
                    
                   else
                   {
                            $data["responce"] = false;
                            $data["error"] = 'Student not found';
                   }
                
       }
       //$data["error"] = $_POST;
       echo json_encode($data); 
          
    }

/* get student profile */

    public function get_student_profile(){
                $data = array(); 
            if($_REQUEST["student_id"]!=""){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                 $q = $this->db->query("select student_detail.*, school_detail.school_name, school_detail.school_address, standard.standard_title from `student_detail` 
                 INNER JOIN school_detail ON school_detail.user_id = student_detail.school_id 
                 INNER JOIN standard ON standard.standard_id = student_detail.student_standard 
                 where student_detail.student_id = ".$_REQUEST["student_id"]);
                $data["responce"] = true;
                $data["data"] = $q->row();
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
    }    
    
    /* get school profile */

    public function get_school_profile(){
                $data = array(); 
            if($_REQUEST["student_id"]!=""){
               
                $q = $this->db->query("select school_detail.* from `school_detail` 
                                    INNER JOIN student_detail ON student_detail.school_id = school_detail.school_id 
                                    where student_detail.student_id = " .$_REQUEST["student_id"]);
               $row = $q->row();
               if(!empty($row)){
                $data["responce"] = true;
                $data["data"] = $row ;
                }else{
                $data["responce"] = false;  
                $data["error"] = " Not School Information Found";
                    
                }
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
    }    
    /* get school Devoir */

    public function get_school_devoir(){
                $data = array(); 
            if($_REQUEST["student_id"]!=""){
               
                $q = $this->db->query("select standard.* from `standard` INNER JOIN student_detail ON student_detail.student_standard = standard.standard_id where student_detail.student_id = " .$_REQUEST["student_id"]);
               
                $row = $q->row();
               if(!empty($row)){
                $data["responce"] = true;
                $data["data"] = $row ;
                }else{
                $data["responce"] = false;  
                $data["error"] = " Devoir.tn Link Not Found";
                    
                }
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Standard id is required";
            }
            echo json_encode($data);
    }    
 
 /* get student growth */

    public function get_student_growth(){
                $data = array(); 
            if($_REQUEST["student_id"]!=""){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                 $q = $this->db->query("select * from `student_growth` where student_id = ".$_REQUEST["student_id"]);
                $data["responce"] = true;
                $data["data"] = $q->result();
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
    }
    
 /* get school event */

    public function get_school_event(){
                $data = array(); 
            if($_REQUEST["school_id"]!=""){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                 $q = $this->db->query("select * from `event` where school_id = ".$_REQUEST["school_id"]." order by on_date DESC");
                $data["responce"] = true;
                $data["data"] = $q->result();
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : School id is required";
            }
            echo json_encode($data);
    }
        
 /* get school notice board */

    public function get_school_noticeboard(){
                $data = array(); 
            if($_REQUEST["school_id"]!=""){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                 $q = $this->db->query("select * from `notice_board` where  notice_status=1 and school_id = ".$_REQUEST["school_id"]." order by on_date DESC");
                $data["responce"] = true;
                $data["data"] = $q->result();
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : School id is required";
            }
            echo json_encode($data);
    }

    public function get_school_teacher(){
                $data = array(); 
            if($_REQUEST["school_id"]!=""){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                 $q = $this->db->query("select * from `teacher_detail` where  school_id = '".$_REQUEST["school_id"]."'");
                $data["responce"] = true;
                $data["data"] = $q->result();
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : School id is required";
            }
            echo json_encode($data);
    }
    
    public function get_student_attendence(){
            if($_REQUEST["student_id"]!="" && $_REQUEST["month"]){
                    $str_date = $_REQUEST["year"]."-".$_REQUEST["month"]."-01";
                    $start_date = date("Y-m-d",strtotime($str_date));
                    $end_date = date("Y-m-t",strtotime($str_date));
                    $sql = "Select * from attendence where attendence_date >= '".$start_date."' and attendence_date <= '".$end_date."' and student_id = '".$_REQUEST["student_id"]."'";
                    $q = $this->db->query($sql);
                $data["responce"] = true;
                $data["data"] = $q->result();
                    
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : School id is required";
            }
            echo json_encode($data);
    }

    public function get_holidays(){
            if($_REQUEST["school_id"]!="" && $_REQUEST["month"]){
                    $str_date = $_REQUEST["year"]."-".$_REQUEST["month"]."-01";
                    $start_date = date("Y-m-d",strtotime($str_date));
                    $end_date = date("Y-m-t",strtotime($str_date));
                    $sql = "Select * from holiday where holiday_date >= '".$start_date."' and holiday_date <= '".$end_date."' and school_id = '".$_REQUEST["school_id"]."'";
                    $q = $this->db->query($sql);
                $data["responce"] = true;
                $data["data"] = $q->result();
                    
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : School id is required";
            }
            echo json_encode($data);
    }

     
    public function get_exams(){
                $data = array(); 
            if($_REQUEST["standard_id"]!="" && $_REQUEST["school_id"]){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                 $q = $this->db->query("select * from `exam` where exam_standard = '".$_REQUEST["standard_id"]."' and school_id='".$_REQUEST["school_id"]."' order by exam_date DESC");
                $data["responce"] = true;
                $data["data"] = $q->result();
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
    }
    public function get_results(){
            $data = array(); 
            if($_REQUEST["standard_id"]!="" && $_REQUEST["school_id"]){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                $q = $this->db->query("select DISTINCT `exam`.* from `exam` 
                inner join exam_result on exam_result.exam_id = exam.exam_id
                where exam_standard = '".$_REQUEST["standard_id"]."' and school_id='".$_REQUEST["school_id"]."' order by exam_date DESC");
                $results = $q->result();
                $data["responce"] = true;
                $data["data"] = $results;
                
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
    }  
    public function get_result_report(){
        
        $data = array(); 
            if($_REQUEST["exam_id"]!="" && $_REQUEST["student_id"]){
                
                //$q = $this->db->query("select * from `student_detail` INNER JOIN categories ON job.category_id = categories.id where job.user_id = ".$_REQUEST["user_id"]);
                $q = $this->db->query("select exam_result.* from `exam_result` 
                where exam_id = '".$_REQUEST["exam_id"]."' and student_id='".$_REQUEST["student_id"]."' ");
                $results = $q->result();
                $data["responce"] = true;
                $data["data"] = $results;
                
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
    }
    
    public function get_enquiry(){
       
        $sql = "Select * from school_student_chat where school_id = '".$this->input->post("school_id")."' and student_id = '".$this->input->post("student_id")."'  order by on_date DESC";
        
        $q = $this->db->query($sql);
        $data["responce"] = true;
        $data["data"] = $q->result();             
        echo json_encode($data);           
    }
    public function send_enquiry(){
     //$_POST = $_REQUEST;
     
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('school_id', 'School ID', 'trim|required');
        $this->form_validation->set_rules('student_id', 'Student Id', 'trim|required');
        $this->form_validation->set_rules('teacher_id', 'Teacher Id', 'trim|required');
        if ($this->form_validation->run() == FALSE)
  		{
  		              $data["responce"] = false;
        			  $data["error"] = $this->form_validation->error_string();
        			  echo json_encode($data);
  		}
  		else {
            $tid = $this->input->post("teacher_id");
            $this->db->insert("school_student_chat", array("student_id" => $this->input->post("student_id"),
                "school_id" => $this->input->post("school_id"), "teacher_id" => $tid,
                "message" => $this->input->post("message"),
                "subject" => $this->input->post("subject")));

            $chat_id = $this->db->insert_id();

            $q = $this->db->query("Select * from school_student_chat where chat_id = '" . $chat_id . "' limit 1");
            $data["responce"] = true;
            $data["data"] = $q->row();
            echo json_encode($data);
            $q1=$this->db->query("Select gcm_code from teacher_detail WHERE teacher_id='$tid'");
            $q2=$q1->row_array();
            $q3=$q2["gcm_code"];
            $ids=array();
            if ($q3!="") {array_push($ids,$q3); }
            /* FCM CODE*/
            $notification = array
            (
                'title'     => "New Query ",
                'body'      => "A new query was raised by your student",
                'icon'      => 'logo',
                'sound'     => 'default',
                'tag'       => 'tag',
                'color'     => '#ffffff'

            );

            $data = array
            (
                'message' => 'message body',
                'click_action' => "PUSH_INTENT",
                "module"=>"teacher_concern",
                "concern_id"=>strval($chat_id)
            );

            $fields = array
            (
                'registration_ids'  => $ids,
                'notification'      => $notification,
                'data'              => $data,
                'priority'          => 'high'

            );

// API access key from Google API's Console
            define('API_ACCESS_KEY', 'AAAAL47RT2o:APA91bHOBH5R0chDURkfoQH7UZ_UtVwwA8g1xjqYzNhGO6yKvJMKKJAn-JBOLT4uWv_Pd-fLRnLofQUw53SeB3W3AwhOfuun3KCSxFx8IraJauxvT1GLV_dC3G5z3QW6JJ2ywlp0Hqpo');
            $headers = array
            (
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );

            /* FCM CODE*/
        }
       //$data["error"] = $_POST;

          
    }

/* get top 10 student by standard */
    public function get_top_student(){
            $data = array(); 
            if($_REQUEST["standard_id"]!="" && $_REQUEST["school_id"]){
                
                $q = $this->db->query("select DISTINCT `top_student`.*, `student_detail`.student_name, `student_detail`.student_photo, `student_detail`.student_roll_no, `standard`.standard_title from `top_student` 
		inner join student_detail on student_detail.student_id = top_student.student_id
		inner join standard on standard.standard_id = top_student.standard_id
                 where top_student.standard_id = '".$_REQUEST["standard_id"]."' and top_student.school_id='".$_REQUEST["school_id"]."' order by top_student.student_rank ASC");
                $results = $q->result();
                $data["responce"] = true;
                $data["data"] = $results;
                
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Standrd id and school id is required";
            }
            echo json_encode($data);
    }
    
    public function register_gcm(){
         
            $email = $this->input->post("email");
                  $regid = $this->input->post("regId");
                  //$imei = $this->input->post("imei");
                    
                    $add = array(
                            "email"=>$email,
                             "gcm_code"=>$regid
                            );
                $this->db->update("student_detail",array("gcm_code"=>$regid),array("student_id"=>$email));
                
                $gcm = $this->db->query($query);
                $data["responce"] = true;
                
             
    echo json_encode($data);
    }
    
    public function timetable(){
      /* $data = array(); 
            if(isset($_REQUEST["standard_id"])!="" && $_REQUEST["school_id"]){ 
                
                 $q = $this->db->query("select timetable.*,standard.standard_title,teacher_detail.teacher_name,days.day_name from timetable
                                inner join standard on standard.standard_id = timetable.standard_id
                                 inner join teacher_detail on teacher_detail.teacher_id = timetable.teacher_id
                                 inner join days on days.id = timetable.day_id
                                 where timetable.standard_id = '".$_REQUEST["standard_id"]."' and timetable.school_id='".$_REQUEST["school_id"]."' order by start_time ASC");
                
                
                $results = $q->result();
                $data["responce"] = true;
                $data["data"] = $results;
                
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
    }*/
    
     $data = array(); 
            if(isset($_REQUEST["standard_id"])!="" && $_REQUEST["school_id"] && $_REQUEST["day_id"]){ 
                
                 $q = $this->db->query("select timetable.*,standard.standard_title,teacher_detail.teacher_name,days.day_name from timetable
                                inner join standard on standard.standard_id = timetable.standard_id
                                 inner join teacher_detail on teacher_detail.teacher_id = timetable.teacher_id
                                 inner join days on days.id = timetable.day_id
                                 where timetable.standard_id = '".$_REQUEST["standard_id"]."' and timetable.school_id='".$_REQUEST["school_id"]."' and timetable.day_id= '".$_REQUEST["day_id"]."' order by start_time ASC");
                
                
                $results = $q->result();
                $data["responce"] = true;
                $data["data"] = $results;
                
            }else{
                $data["responce"] = false;  
                $data["error"] = "Error! : Student id is required";
            }
            echo json_encode($data);
        }
        
         public function get_subject_list(){ 
             $data = array(); 
                    if(isset($_REQUEST["standard_id"])!="" && $_REQUEST["school_id"] && $_REQUEST["student_id"]!=""){ 
                        
                         $q = $this->db->query("select subject.*,standard.standard_title,quiz_res.quiz_total_right_ans,sub_count.total_qes from subject
                                        inner join standard on standard.standard_id = subject.subject_standard
                                        inner join (select COUNT(ques_id) as total_qes,subject_id from question GROUP BY subject_id) as sub_count on sub_count.subject_id = subject.subject_id
                                         left outer join (select quiz_subject_id,quiz_student_id,quiz_total_right_ans from quiz_result where quiz_student_id = '".$_REQUEST["student_id"]."') as quiz_res on quiz_res.quiz_subject_id = subject.subject_id
                                         where subject.subject_standard = '".$_REQUEST["standard_id"]."' and subject.school_id='".$_REQUEST["school_id"]."'");
                        
                        
                        $results = $q->result();
                        $data["responce"] = true;
                        $data["data"] = $results;
                        
                    }else{
                        $data["responce"] = false;  
                        $data["error"] = "Error! : Student id is required";
                    }
                    echo json_encode($data);
                }
                
         public function get_question_by_subject(){ 
                    $data = array(); 
                    if($_REQUEST["subject_id"]!=""){ 
                        
                         $q = $this->db->query("select question.* from question       
                                         where question.subject_id = '".$_REQUEST["subject_id"]."'");
                        
                        
                        $results = $q->result();
                        $data["responce"] = true;
                        $data["data"] = $results;
                        
                    }else{
                        $data["responce"] = false;  
                        $data["error"] = "Error! : feild is required";
                    }
                    echo json_encode($data);
                }
                
                
        public function set_quiz_result(){
         //$_POST = $_REQUEST;
         
            $this->load->library('form_validation');
            $this->form_validation->set_rules('quiz_student_id', 'User Name', 'trim|required');
            $this->form_validation->set_rules('quiz_school_id', 'Password', 'trim|required');
             $this->form_validation->set_rules('quiz_subject_id', 'subject id', 'trim|required');
            $this->form_validation->set_rules('quiz_student_standard', 'student standard', 'trim|required'); 
            $this->form_validation->set_rules('quiz_total_right_ans', 'right answer', 'trim|required');
            $this->form_validation->set_rules('quiz_student_time', 'student time', 'trim|required');
            $this->form_validation->set_rules('data', 'data', 'trim|required');
            if ($this->form_validation->run() == FALSE) 
      		{
      		              $data["responce"] = false;
            			  $data["error"] = $this->form_validation->error_string();
      		}else
  		    {        
	               $this->db->insert("quiz_result",array("quiz_student_id"=>$this->input->post("quiz_student_id"),
                   "quiz_school_id"=>$this->input->post("quiz_school_id"),
                   "quiz_subject_id"=>$this->input->post("quiz_subject_id"),
                   "quiz_student_standard"=>$this->input->post("quiz_student_standard"), 
                   "quiz_total_right_ans"=>$this->input->post("quiz_total_right_ans"),
                   "quiz_student_time"=>$this->input->post("quiz_student_time")));
                   
                       $attempt_quiz_result_id = $this->db->insert_id();
                     $student_id = $this->input->post("quiz_student_id");
                    $data_post = $this->input->post("data");
                    $data_array = json_decode($data_post);
                    
                    foreach($data_array as $dt){
                         
                        
                        $array = array("attempt_quiz_result_id"=>$attempt_quiz_result_id, 
                        "attempt_qus_id"=>$dt->attempt_qus_id,
                        "attempt_student_id"=>$student_id,
                        "attempt_ans"=>$dt->attempt_ans,
                        "attempt_r_ans"=>$dt->attempt_r_ans
                        );
                        $this->db->insert("attempt_test",$array);
                         
                    }
                    
                   
                   $data["responce"] = true;
                   $data["data"] = "Your quiz exam answer has been submitted successfully";             
                   
                
       }
       //$data["error"] = $_POST;
       echo json_encode($data); 
        }
        
    public function get_quiz_report(){        
        $data = array(); 
           if($_REQUEST["student_id"]!="" && $_REQUEST["subject_id"]!=""){ 
                        
              /*   $q = $this->db->query("select quiz_result.*,attempt_test.attempt_r_ans,attempt_test.attempt_ans from quiz_result
                                inner join attempt_test on attempt_test.attempt_quiz_result_id = quiz_result.quiz_subject_id
                                 left outer join (select question,quiz_student_id,quiz_total_right_ans from quiz_result where quiz_student_id = '".$_REQUEST["student_id"]."') as quiz_res on quiz_res.quiz_subject_id = subject.subject_id  
                                  where quiz_result.quiz_student_standard = '".$_REQUEST["standard_id"]."' and quiz_result.quiz_school_id='".$_REQUEST["school_id"]."'"); */
                
                       $q = $this->db->query("select Distinct question.*,attempt.attempt_ans from question
                                        left outer join (select attempt_qus_id,attempt_ans,attempt_r_ans from attempt_test where attempt_student_id = '".$_REQUEST["student_id"]."') as attempt on attempt.attempt_qus_id = question.ques_id 
                                          where question.subject_id = '".$_REQUEST["subject_id"]."' ");
                        
                        $results = $q->result();
                        $data["responce"] = true;
                        $data["data"] = $results;
                        
                    }else{
                        $data["responce"] = false;  
                        $data["error"] = "Error! : Feild is required";
                    }
            echo json_encode($data);
    }
    
     public function notification_list(){ 
                    $data = array(); 
                    if($_REQUEST["school_id"]!=""){ 
                        
                         
                        $q = $this->db->query("select * from notification where school_id= '".$_REQUEST["school_id"]."'");
                        $results = $q->result();
                        
                        $data["responce"] = true;
                        $data["data"] = $results;
                        
                    }else{
                        $data["responce"] = false;  
                        $data["error"] = "Error! : feild is required";
                    }
                    echo json_encode($data);
                }
                
   	public function register_fcm(){
            $data = array();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('student_id', 'Student ID', 'trim|required');
            $this->form_validation->set_rules('token', 'Token', 'trim|required');
            $this->form_validation->set_rules('device', 'Device', 'trim|required');
            if ($this->form_validation->run() == FALSE) 
        {
                $data["responce"] = false;
               $data["error"] = $this->form_validation->error_string();
                                
        }else
            {   
                $device = $this->input->post("device");
                $token = $this->input->post("token");
                $student_id = $this->input->post("student_id");
                
                $field = "";
                if($device=="android"){
                    $field = "gcm_code";
                }else if($device=="ios"){
                    $field = "ios_token";
                }
                if($field!=""){
                    $this->db->query("update student_detail set ".$field." = '".$token."' where student_id = '".$student_id."'");
                    $data["responce"] = true;    
                }else{
                    $data["responce"] = false;
                    $data["error"] = "Device type is not set";
                }
                
                
            }
            echo json_encode($data);
    }

    public function get_book_by_standard(){        
        $data = array(); 
           if($_REQUEST["standard_id"]!="" && $_REQUEST["school_id"]!=""){  
                      
                        $q = $this->db->query("select * from book 
        where on_date  >= DATE_SUB(NOW(),INTERVAL 3 MONTH) and  book_standard = '".$_REQUEST["standard_id"]."' and school_id='".$_REQUEST["school_id"]."' order by on_date DESC");
                        
                        $results = $q->result();
                        $data["responce"] = true;
                        $data["data"] = $results;
                        
                    }else{
                        $data["responce"] = false;  
                        $data["error"] = "Error! : Feild is required";
                    }
            echo json_encode($data);
    }
     public function list_student_fees_by_student(){        
        $data = array(); 
           if($_REQUEST["student_id"]!="" && $_REQUEST["school_id"]!=""){  
                        
                        $q = $this->db->query("select student_fees.*,standard.standard_title,student_detail.student_name,student_detail.student_address,student_detail.student_city,student_detail.student_roll_no,student_detail.student_branch,student_detail.student_phone,fee_types.title,fee_types.year from student_fees 
         inner join student_detail on student_detail.student_id = student_fees.student_id
          inner join fee_types on fee_types.id = student_fees.fee_types 
           inner join standard on standard.standard_id = student_fees.standard_id 
        where student_fees.student_id = '".$_REQUEST["student_id"]."' and student_fees.school_id='".$_REQUEST["school_id"]."'");
                        
                        $results = $q->result();
                        $data["responce"] = true;
                        $data["data"] = $results;
                        
                    }else{
                        $data["responce"] = false;  
                        $data["error"] = "Error! : Feild is required";
                    }
            echo json_encode($data);
    }

    public function getTeachers() {
        $student_id=$this->input->post("student_id");
        $q=$this->db->query("Select * from student_detail WHERE student_id='$student_id'");
        $q=$q->row_array();
        $id=$q["student_standard"];
        $schoolid=$q["school_id"];
        $qe=$this->db->query("Select standard_title from standard WHERE standard_id='$id' and school_id='$schoolid'");
        $rt=$qe->row_array();
        $rt=$rt["standard_title"];
        $query="select teacher_id,teacher_name from teacher_detail where teaching like '%$rt%' ";

        $q2=$this->db->query($query);
        $q2=$q2->result_array();
        echo json_encode(array("responce"=>true,"data"=>$q2)) ;
    }

    public function getBook() {
        $id=$this->input->post("id");
        $q=$this->db->query("Select * from book where book_id='$id'");
        $q1=$q->row_array();
        echo json_encode(array("responce"=>true,"data"=>$q1));
    }

    public function password() {
        $id=$this->input->post("student_id");
        $new=$this->input->post("password");
        $this->db->update("student_detail",array("student_password"=>md5($new),"student_orgpassword"=>$new),array("student_id"=>$id));
        echo json_encode(array("responce"=>true));
    }
}
?>