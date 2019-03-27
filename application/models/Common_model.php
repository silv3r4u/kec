<?php
class Common_model extends CI_Model{
    function send($registers,$module,$title,$message) {
        foreach($registers as $regs){
            if($regs->gcm_code!="")
                $registatoin_ids[] = $regs->gcm_code;
        }
        define('API_ACCESS_KEY', 'AAAAL47RT2o:APA91bHOBH5R0chDURkfoQH7UZ_UtVwwA8g1xjqYzNhGO6yKvJMKKJAn-JBOLT4uWv_Pd-fLRnLofQUw53SeB3W3AwhOfuun3KCSxFx8IraJauxvT1GLV_dC3G5z3QW6JJ2ywlp0Hqpo');


// prep the bundle
        $notification = array
        (
            'title' => $title,
            'body' => $message,
            'icon' => 'logo',
            'sound' => 'default',
            'tag' => 'tag',
            'color' => '#ffffff'

        );

        $data1 = array
        (
            'message' => 'message body',
            'click_action' => "PUSH_INTENT",
            "module" => $module,

        );

        $fields = array
        (
            'registration_ids' => $registatoin_ids,
            'notification' => $notification,
            'data' => $data1,
            'priority' => 'high'

        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

    }
    function data_insert($table,$insert_array){
        $this->db->insert($table,$insert_array);
        $id = $this->db->insert_id();
        if($table == "event" || $table == "`event`"){
            $q = $this->db->query("Select * from event where event_id = '".$id."' limit 1");
            $row = $q->row();
            $registers = $this->get_students_gcm_code($row->school_id);
            //$this->send($registers,"event",$row->event_title,$row->event_description);
            //$this->send_gcm_message($registers,$row->event_title, $row->event_description, base_url("uploads/eventphoto/".$row->event_image),"event");
            $this->send($registers,"event","New Event",$row->holiday_title);
        }
        if($table == "exam" || $table == "`exam`"){
            $q = $this->db->query("Select * from exam where exam_id = '".$id."' limit 1");
            $row = $q->row();
            $registers = $this->get_students_gcm_code($row->school_id);
            $this->send_gcm_message($registers,$row->exam_title, $row->exam_note, "","exam");
        }
        if($table == "holiday" || $table == "`holiday`"){
            $q = $this->db->query("Select * from holiday where holiday_id = '".$id."' limit 1");
            $row = $q->row();
            $registers = $this->get_students_gcm_code($row->school_id);
            //$this->send_gcm_message($registers,$row->holiday_title, "Date : ".$row->holiday_date, "","holiday");
            $this->send($registers,"holiday","New Holiday",$row->holiday_title);
        }
        if($table == "notice_board" || $table == "`notice_board`"){
            $q = $this->db->query("Select * from notice_board where notice_id = '".$id."' limit 1");
            $row = $q->row();
            $registers = $this->get_students_gcm_code($row->school_id);
            //$this->send_gcm_message($registers,$row->notice_type, $row->notice_description, "","notice");
            $this->send($registers,"notice",$row->notice_type,$row->notice_description);
        }
        if($table == "school_student_chat" || $table == "`school_student_chat`"){
            $q = $this->db->query("Select * from school_student_chat where chat_id = '".$id."' limit 1");
            $row = $q->row();
            $registers = $this->get_students_gcm_code("",$row->student_id);
            $this->send_gcm_message($registers,$row->subject, $row->message, "","concern");
        }
        
        return $id;
    }
    function get_students_gcm_code($school_id="", $student_id=""){
        $filter = "";
        if($school_id != ""){
            $filter .=" and school_id = '".$school_id."' ";
        }
        if($student_id != ""){
            $filter .=" and student_id = '".$student_id."' ";
        }
        $q = $this->db->query("Select gcm_code from student_detail where 1 and gcm_code != '' ".$filter);
        return $results = $q->result();
        
        
    }
    function data_update($table,$set_array,$condition){
        $this->db->update($table,$set_array,$condition);
        return $this->db->affected_rows();
    }
    function data_remove($table,$condition){
        $this->db->delete($table,$condition);
    }
    function send_gcm_message($registers,$subject,$message,$image="",$module=""){
                $this->load->helper('gcm_helper');
                $gcm = new GCM();
                $registatoin_ids = array();
                $message = array("module"=>$module,"message" => $message,"title"=>$subject,"image"=>$image,'created_at'=>date('Y-m-d G:i:s'));
              
                 foreach($registers as $regs){
                     if($regs->gcm_code!="")
                         $registatoin_ids[] = $regs->gcm_code;
                 }
                 if(count($registatoin_ids) > 1000){
                  $chunk_array = array_chunk($registatoin_ids,1000);
                  foreach($chunk_array as $chunk){
                   $result = $gcm->send_notification($chunk, $message);
                  }
                 }else{
                   $result = $gcm->send_notification($registatoin_ids, $message,$this->config->item("GOOGLE_API_KEY"));
                }      
    }
}
?>