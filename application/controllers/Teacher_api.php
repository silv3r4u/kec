<?php
error_reporting(E_ALL . E_STRICT);
ini_set('display_errors', 1);

defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
    }


    public function login()
    {
        //$_POST = $_REQUEST;
        $this->form_validation->set_rules('teacher_username', 'User Name', 'trim|required');
        $this->form_validation->set_rules('teacher_password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data["responce"] = false;
            $data["error"] = $this->form_validation->error_string();
        } else {
            $quer = "Select * from `teacher_detail` where `username`='" . $this->input->post("teacher_username") . "' and password='" . md5($this->input->post("teacher_password")) . "' Limit 1";
            $q = $this->db->query($quer);
            $student = $q->row();
            if (!empty($student)) {
                if (false /*$student->student_status == "0"*/) {
                    $data["responce"] = false;
                    $data["error"] = 'Your account currently inactive';
                } else {

                    $data["data"] = $student;
                    $data["responce"] = true;
                }
            } else {
                $data["responce"] = false;
                $data["error"] = 'Teacher not found';
            }

        }
        //$data["error"] = $_POST;
        echo json_encode($data);

    }

    public function get_standard()
    {
        $this->form_validation->set_rules('teacher_id', 'ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data["responce"] = false;
            $data["error"] = $this->form_validation->error_string();
        } else {

            $quer = "Select * from `teacher_detail` where `teacher_id`='" . $this->input->post("teacher_id") . "' Limit 1";
            $q = $this->db->query($quer);
            $student = $q->row();
            if (!empty($student)) {
                if (false /*$student->student_status == "0"*/) {
                    $data["responce"] = false;
                    $data["error"] = 'Your account currently inactive';
                } else {
                    $main = $student->teaching;
                    $one = explode(";", $main);
                    $standa = array();
                    foreach ($one as $it) {
                        $u = explode("|", $it);
                        if (!(in_array($u[0], $standa))) {
                            array_push($standa, $u[0]);
                        }
                    }
                    $data["data"] = $standa;
                    $data["responce"] = true;
                }
            } else {
                $data["responce"] = false;
                $data["error"] = 'Teacher not found';
            }


        }
        echo json_encode($data);
    }

    public function isTaken()
    {
        $this->form_validation->set_rules('teacher_id', 'ID', 'trim|required');
        $this->form_validation->set_rules('standard', 'Standard', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');
        $this->form_validation->set_rules('month', 'Month', 'trim|required');
        $this->form_validation->set_rules('day', 'Day', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data["responce"] = false;
            $data["error"] = $this->form_validation->error_string();
        } else {
            $dats = implode("-", array($this->input->post("year"), $this->input->post("month"), $this->input->post("day")));
            $suid = "1";
            $qugee = "Select school_id from teacher_detail where teacher_id='" . $this->input->post("teacher_id") . "'";
            $qfdgldliful = $this->db->query($qugee);
            $schoolid = ($qfdgldliful->row_array());
            $schoolid = ($schoolid["school_id"]);

            $standard = $this->input->post("standard");
            $tid = $this->input->post("teacher_id");

            $quff = "Select standard_id from standard where school_id='" . $schoolid . "' and standard_title='" . $standard . "'";
            $qfgd = $this->db->query($quff);
            $r1 = ($qfgd->row_array());
            $stta = $r1["standard_id"];
            $query_ch = "Select COUNT(*) from `attendence` WHERE standard_id='" . $stta . "' and school_id='" . $schoolid . "' and attendence_date='" . $dats . "' and subject_id='" . $suid . "' and teacher_id='" . $tid . "' ";
            $quccc = $this->db->query($query_ch);
            $no = ($quccc->result_array()[0]["COUNT(*)"]);
            if (((int)$no) > 0) {
                $data["responce"] = false;
                $data["error"] = 'The Attendance is Already Taken';
                $data["done"] = true;
            } else {
                $data["done"] = false;
            }
            echo json_encode($data);


        }
    }

    public function get_attendance_form()
    {
        $this->form_validation->set_rules('teacher_id', 'ID', 'trim|required');
        $this->form_validation->set_rules('standard', 'Standard', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');
        $this->form_validation->set_rules('month', 'Month', 'trim|required');
        $this->form_validation->set_rules('day', 'Day', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data["responce"] = false;
            $data["error"] = $this->form_validation->error_string();
        } else {
            $dats = implode("-", array($this->input->post("year"), $this->input->post("month"), $this->input->post("day")));

            $suid = "1";
            $qugee = "Select school_id from teacher_detail where teacher_id='" . $this->input->post("teacher_id") . "'";
            $qfdgldliful = $this->db->query($qugee);
            $schoolid = ($qfdgldliful->row_array());
            $schoolid = ($schoolid["school_id"]);

            $standard = $this->input->post("standard");

            $tid = $this->input->post("teacher_id");
            $quff = "Select standard_id from standard where school_id='" . $schoolid . "' and standard_title='" . $standard . "'";
            $qfgd = $this->db->query($quff);
            $r1 = ($qfgd->row_array());
            $stta = $r1["standard_id"];

            $querdd = "Select student_name,student_id from `student_detail`  where school_id='" . $schoolid . "' and student_standard='" . $stta . "' ";
            $qdd = $this->db->query($querdd);
            $student = $qdd->result_array();
            if (!empty($student)) {
                if (false /*$student->student_status == "0"*/) {
                    $data["responce"] = false;
                    $data["error"] = 'Your account currently inactive';
                } else {
                    $data["none"] = false;
                    $data["data"] = $student;
                    $data["responce"] = true;
                }
            } else {
                $data["responce"] = false;
                $data["error"] = 'No Data';
                $data['none'] = true;
            }

        }

        echo json_encode($data);
    }


    function put_attendance_feed()
    {

        $this->form_validation->set_rules('teacher_id', 'ID', 'trim|required');
        $this->form_validation->set_rules('standard', 'Standard', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');
        $this->form_validation->set_rules('month', 'Month', 'trim|required');
        $this->form_validation->set_rules('day', 'Day', 'trim|required');
        $this->form_validation->set_rules('content', 'Attendance Data', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data22["responce"] = false;
            $data22["error"] = $this->form_validation->error_string();
        } else {
            $dats = implode("-", array($this->input->post("year"), $this->input->post("month"), $this->input->post("day")));


            $finaldt = strval(date("F j, Y", strtotime($dats)));
            $suid = "1";
            $qugee = "Select school_id from teacher_detail where teacher_id='" . $this->input->post("teacher_id") . "'";
            $qfdgldliful = $this->db->query($qugee);
            $schoolid = ($qfdgldliful->row_array());
            $schoolid = ($schoolid["school_id"]);

            $standard = $this->input->post("standard");

            $tid = $this->input->post("teacher_id");
            $quff = "Select standard_id from standard where school_id='" . $schoolid . "' and standard_title='" . $standard . "'";
            $qfgd = $this->db->query($quff);
            $r1 = ($qfgd->row_array());
            $stta = $r1["standard_id"];

            $data1 = json_decode($this->input->post("content"), true);
            $fcmids_present = array();
            $fcmids_absent = array();


            foreach ($data1 as $row) {
                try {
                    $q = "insert into attendence (school_id,standard_id,student_id,attendence_date,attended,attendence_reason,subject_id,teacher_id) VALUES ( '" . $schoolid . "','" . $stta . "','" . $row["id"] . "','" . $dats . "','" . $row["attended"] . "','" . $row["reason"] . "','" . $suid . "','" . $tid . "'
) ;";

                    $this->db->query($q);
                    if (($this->db->error())["code"] != 0) {
                        throw new Exception('p');
                    }

                    $data22["responce"] = true;
                    $data22["data"] = true;

                    /*FCM***/

                    $quety_m = "select gcm_code from student_detail where student_id='" . $row['id'] . "'";
                    $resmc = $this->db->query($quety_m);
                    $code = $resmc->row_array();
                    $code = $code["gcm_code"];
                    if ($code != "") {
                        if ($row["attended"] == "1") {
                            array_push($fcmids_present, $code);
                        } else {
                            array_push($fcmids_absent, $code);
                        }
                    }
                } catch (Exception $e) {
                    if ($e->getMessage() == "p") {
                        $data22["responce"] = false;
                        $data22["error"] = 'Sorry,One of the Attendence Records is already present';
                    } else {
                        $data22["responce"] = false;
                        $data22["error"] = 'Error Inserting During Execution or Attendance already present , Update in that case';
                    }
                }

            }


            $message = 'You are marked as Present on ' . $finaldt;
            $message1 = 'You are marked as Absent on ' . $finaldt;


// API access key from Google API's Console
            define('API_ACCESS_KEY', 'AAAAL47RT2o:APA91bHOBH5R0chDURkfoQH7UZ_UtVwwA8g1xjqYzNhGO6yKvJMKKJAn-JBOLT4uWv_Pd-fLRnLofQUw53SeB3W3AwhOfuun3KCSxFx8IraJauxvT1GLV_dC3G5z3QW6JJ2ywlp0Hqpo');
            $registrationIds = $fcmids_present; //$id is string not array

// prep the bundle
            $notification = array
            (
                'title' => "Attendance Update",
                'body' => $message,
                'icon' => 'logo',
                'sound' => 'default',
                'tag' => 'tag',
                'color' => '#ffffff'

            );

            $data = array
            (
                'message' => 'message body',
                'click_action' => "PUSH_INTENT",
                "module" => "attendance",
                "date" => $dats

            );

            $fields = array
            (
                'registration_ids' => $registrationIds,
                'notification' => $notification,
                'data' => $data,
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


            $registrationIds = $fcmids_absent; //$id is string not array
// prep the bundle
            $notification = array
            (
                'title' => "Attendance Update",
                'body' => $message1,
                'icon' => 'logo',
                'sound' => 'default',
                'tag' => 'tag',
                'color' => '#ffffff'

            );

            $data = array
            (
                'message' => 'message body',
                'click_action' => "PUSH_INTENT",
                "module" => "attendance",
                "date" => $dats
            );

            $fields = array
            (
                'registration_ids' => $registrationIds,
                'notification' => $notification,
                'data' => $data,
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
            $result1 = curl_exec($ch);
            curl_close($ch);
            $data22["fcm1"]=$result;
            echo json_encode($data22);


        }
    }


    public function view_attendance()
    {
        $this->form_validation->set_rules('teacher_id', 'ID', 'trim|required');
        $this->form_validation->set_rules('standard', 'Standard', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');
        $this->form_validation->set_rules('month', 'Month', 'trim|required');
        $this->form_validation->set_rules('day', 'Day', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data["responce"] = false;
            $data["error"] = $this->form_validation->error_string();
        } else {
            $dats = implode("-", array($this->input->post("year"), $this->input->post("month"), $this->input->post("day")));

            $suid = "1";
            $qugee = "Select school_id from teacher_detail where teacher_id='" . $this->input->post("teacher_id") . "'";
            $qfdgldliful = $this->db->query($qugee);
            $schoolid = ($qfdgldliful->row_array());
            $schoolid = ($schoolid["school_id"]);

            $standard = $this->input->post("standard");

            $tid = $this->input->post("teacher_id");
            $quff = "Select standard_id from standard where school_id='" . $schoolid . "' and standard_title='" . $standard . "'";
            $qfgd = $this->db->query($quff);
            $r1 = ($qfgd->row_array());
            $stta = $r1["standard_id"];

            $quer = "Select * from `attendence` JOIN `student_detail` ON attendence.student_id=student_detail.student_id where `teacher_id`='" . $this->input->post("teacher_id") . "'and attendence.school_id='" . $schoolid . "' and standard_id='" . $stta . "' and subject_id='" . $suid . "' and attendence_date='" . $dats . "' ";
            $q = $this->db->query($quer);
            $student = $q->result_array();
            if (!empty($student)) {
                if (false /*$student->student_status == "0"*/) {
                    $data["responce"] = false;
                    $data["error"] = 'Your account currently inactive';
                } else {
                    $data["none"] = false;
                    $data["data"] = $student;
                    $data["responce"] = true;
                }
            } else {
                $data["responce"] = false;
                $data["error"] = 'No Data';
                $data['none'] = true;
            }
        }
        echo json_encode($data);
    }


    public function updateAt()
    {
        $this->form_validation->set_rules('teacher_id', 'ID', 'trim|required');
        $this->form_validation->set_rules('standard', 'Standard', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');
        $this->form_validation->set_rules('month', 'Month', 'trim|required');
        $this->form_validation->set_rules('day', 'Day', 'trim|required');
        $this->form_validation->set_rules('content', 'Attendance Data', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data["responce"] = false;
            $data["error"] = $this->form_validation->error_string();
        } else {
            $dats = implode("-", array($this->input->post("year"), $this->input->post("month"), $this->input->post("day")));

            $suid = "1";
            $qugee = "Select school_id from teacher_detail where teacher_id='" . $this->input->post("teacher_id") . "'";
            $qfdgldliful = $this->db->query($qugee);
            $schoolid = ($qfdgldliful->row_array());
            $schoolid = ($schoolid["school_id"]);

            $standard = $this->input->post("standard");

            $tid = $this->input->post("teacher_id");
            $quff = "Select standard_id from standard where school_id='" . $schoolid . "' and standard_title='" . $standard . "'";
            $qfgd = $this->db->query($quff);
            $r1 = ($qfgd->row_array());
            $stta = $r1["standard_id"];


            $tid = $this->input->post("teacher_id");
            $data1 = json_decode($this->input->post("content"), true);
            foreach ($data1 as $row) {
                try {
                    $this->db->update("attendence", array(
                            "attended" => $row["attended"],
                            "attendence_reason" => $row["reason"]
                        )
                        , array("student_id" => $row['id'], "attendence_id" => $row['aid']));
                    if (($this->db->error())["code"] != 0) {
                        throw new Exception('p');
                    }
                    $data["responce"] = true;
                    $data["data"] = true;
                } catch (Exception $e) {
                    if ($e->getMessage() == "p") {
                        $data["responce"] = false;
                        $data["error"] = 'Sorry,One of the Attendence Records is not present';
                    } else {
                        $data["responce"] = false;
                        $data["error"] = 'Error Inserting During Execution Contact Admin';
                    }
                }
            }
        }
        echo json_encode($data);

    }

    public function uploadWork()
    {
        $subdt = "";
        try {
            $subdt = $this->input->post("date");
        } catch (Exception $e) {

        }
        $teacher_id = $this->input->post("teacher_id");
        $qugee = "Select school_id,teacher_name from teacher_detail where teacher_id='" . $this->input->post("teacher_id") . "'";
        $qfdgldliful = $this->db->query($qugee);
        $schoolid = ($qfdgldliful->row_array());
        $tname=$schoolid["teacher_name"];
        $schoolid = ($schoolid["school_id"]);

        $standard = $this->input->post("standard");
        $quff = "Select standard_id from standard where school_id='" . $schoolid . "' and standard_title='" . $standard . "'";
        $qfgd = $this->db->query($quff);
        $r1 = ($qfgd->row_array());
        $stta = $r1["standard_id"];

        $type = $this->input->post("type");
        $ftype = $this->input->post("upload_type");
        $title = $this->input->post("title");
        $desc = $this->input->post("desc");

        if ($ftype == "pdf") {
            /*** Pdf Upload  ***/
            $uploads_dir = "uploads/book_pdf";
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }
            $tmp_name = $_FILES["name"]["tmp_name"];
            $name = "teacherid-" . $teacher_id . "_" . strval(date("m.d.y_H:i:s")) . "_" . $_FILES["name"]["name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
            $url = $name;

            /*** End Pdf Upload **/
        } else {
            $uploads_dir = "uploads/temp";
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }
            $tmp_name = $_FILES["name"]["tmp_name"];
            $name = "teacherid-" . $teacher_id . "_" . strval(date("m.d.y_H:i:s")) . "_" . $_FILES["name"]["name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
            $url = $name;
            $main = "$uploads_dir/$name";
            include("fpdf.php");
            $pdf = new FPDF();
            $pdf->AddPage();
            $uploads_dir = "uploads/book_pdf";
            $pdf->Image($main);
            $pdf->Output("F", "uploads/book_pdf/$name.pdf");
            $url = "uploads/book_pdf/$name.pdf";
        }
        $this->db->insert("book", array("submission_date" => $subdt, "book_title" => $title, "book_author" =>$tname, "book_type" => $type,
            "book_description" => $desc, "school_id" => $schoolid, "book_standard" => $stta, "book_file" => $url));
        $get_id=$this->db->query("Select * from book where book_file='$url'");
        $get=$get_id->row_array();
        $bid=$get["book_id"];
        echo json_encode(array("done" => true, "file" => $url, "standard" => $stta, "date" => $subdt,"id"=>$bid));
        /***FCM CODE ***/
        $fcmqq = "Select gcm_code from student_detail WHERE school_id='" . $schoolid . "' and student_standard='" . $stta . "'";
        $rtfd = $this->db->query($fcmqq);
        $rt8 = $rtfd->result_array();
        $ids = array();
        foreach ($rt8 as $row) {
            array_push($ids, $row["gcm_code"]);
        }


// prep the bundle
        $notification = array
        (
            'title' => "Document Uploaded",
            'body' => "New $type uploaded",
            'icon' => 'logo',
            'sound' => 'default',
            'tag' => 'tag',
            'color' => '#ffffff'

        );

        $data = array
        (
            'message' => 'message body',
            'click_action' => "PUSH_INTENT",
            "module" => "homework",
            "id" => $bid
        );

        $fields = array
        (
            'registration_ids' => $ids,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high'

        );

// API access key from Google API's Console
        define('API_ACCESS_KEY', 'AAAAL47RT2o:APA91bHOBH5R0chDURkfoQH7UZ_UtVwwA8g1xjqYzNhGO6yKvJMKKJAn-JBOLT4uWv_Pd-fLRnLofQUw53SeB3W3AwhOfuun3KCSxFx8IraJauxvT1GLV_dC3G5z3QW6JJ2ywlp0Hqpo');
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


        /***FCM END***/


    }

    public function getinfo()
    {
        $teacherid = $this->input->post("teacher_id");
        $query = "select * from teacher_detail where teacher_id='" . $teacherid . "'";
        $r = $this->db->query($query);
        $rr = $r->row_array();
        echo json_encode(array("responce" => true, "data" => $rr));
    }

    public function putinfo()
    {
        $info = "";
        $teacherid = $this->input->post("teacher_id");
        if ($this->input->post("image") == "yes") {
            $uploads_dir = "uploads/teacherphoto";
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }
            $tmp_name = $_FILES["file"]["tmp_name"];
            $name = "teacherid-" . $teacherid . "_" . strval(date("m.d.y_H:i:s")) . "_" . $_FILES["file"]["name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
            $url = $name;
            $info = $name;
            $this->db->update("teacher_detail", array("teacher_image" => $name), array("teacher_id" => $teacherid));
        }

        $this->db->update("teacher_detail", array("teacher_address" => $this->input->post("addr"),
            "teacher_phone" => $this->input->post("phone"), "teacher_exp" => $this->input->post("exp"), "teacher_education" => $this->input->post("education"),
            "teacher_email" => $this->input->post("email")
        ), array("teacher_id" => $teacherid));


        echo json_encode(array("responce" => true, "info" => $info));
    }

    public function getConcerns()
    {
        $teacherid = $this->input->post("teacher_id");
        $q = $this->db->query("Select * from school_student_chat WHERE teacher_id='$teacherid'");
        $ans = $q->result_array();
        echo json_encode(array("responce" => true, "data" => $ans));
    }

    public function put_reply()
    {
        $date = strval(date("d-M-Y"));
        $concern_id = $this->input->post("concern_id");
        $reply = $this->input->post("reply");
        $q = $this->db->query("Select * from school_student_chat WHERE chat_id='$concern_id'");
        $q1 = $q->row_array();
        if ($q1["reply"] == "") {
            $this->db->update("school_student_chat", array("reply" => $reply, "replied_date" => $date), array("chat_id" => $concern_id));
            echo json_encode(array("responce" => true));
            /* FCM */
            $new_query = $this->db->query("Select student_id from school_student_chat where chat_id='$concern_id'");
            $answ = $new_query->row_array();
            $sid = $answ["student_id"];

            $fcmqq = "Select gcm_code from student_detail WHERE student_id='$sid'";
            $rtfd = $this->db->query($fcmqq);
            $rt8 = $rtfd->row_array();
            $ids = array();
            if ($rt8["gcm_code"] != "") {
                array_push($ids, $rt8["gcm_code"]);
            }
// prep the bundle
            $notification = array
            (
                'title' => "Query Reply",
                'body' => "Your query was replied by your teacher",
                'icon' => 'logo',
                'sound' => 'default',
                'tag' => 'tag',
                'color' => '#ffffff'

            );

            $data = array
            (
                'message' => 'message body',
                'click_action' => "PUSH_INTENT",
                "module" => "reply",
                "concern_id" => $concern_id
            );

            $fields = array
            (
                'registration_ids' => $ids,
                'notification' => $notification,
                'data' => $data,
                'priority' => 'high'

            );

// API access key from Google API's Console
            define('API_ACCESS_KEY', 'AAAAL47RT2o:APA91bHOBH5R0chDURkfoQH7UZ_UtVwwA8g1xjqYzNhGO6yKvJMKKJAn-JBOLT4uWv_Pd-fLRnLofQUw53SeB3W3AwhOfuun3KCSxFx8IraJauxvT1GLV_dC3G5z3QW6JJ2ywlp0Hqpo');
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


            /* FCM */
        } else {
            echo json_encode(array("responce" => false));
        }
    }

    public function getConcern()
    {
        $tid = $this->input->post("tid");
        $qte = $this->db->query("Select teacher_name from teacher_detail WHERE teacher_id='$tid'");
        $name = ($qte->row_array())["teacher_name"];
        $teacherid = $this->input->post("concern_id");
        $q = $this->db->query("Select * from school_student_chat WHERE chat_id='$teacherid'");
        $ans = $q->row_array();
        $studentid = $ans["student_id"];
        $q1 = $this->db->query("Select * from student_detail WHERE student_id='$studentid'");
        $ans1 = $q1->row_array();
        $student_name = $ans1["student_name"];
        $rollno = $ans1["student_roll_no"];
        $standid = $ans1["student_standard"];
        $q3 = $this->db->query("Select * from standard where standard_id='$standid'");
        $answ = $q3->row_array();
        $standard = $answ["standard_title"];
        echo json_encode(array("responce" => true, "data" => $ans, "name" => $student_name, "standard" => $standard, "roll_no" => $rollno, "tname" => $name));
    }

    public function register_fcm()
    {
        $tid = $this->input->post("teacher_id");
        $t = $this->input->post("token");
        $d = $this->input->post("device");
        if ($d == "android") {
            $this->db->update("teacher_detail", array("gcm_code" => $t), array("teacher_id" => $tid));
            echo json_encode(array("responce" => true));
        }
    }

    /**
     * @return object
     */
    public function password()
    {
        $new=$this->input->post("password");
        $tid=$this->input->post("teacher_id");
        $this->db->update("teacher_detail",array("password"=>md5($new)),array("teacher_id"=>$tid));
        echo json_encode(array("responce"=>true));
    }
}

?>