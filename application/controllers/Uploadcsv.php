<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadcsv extends CI_Controller {

public function __construct()
{
    parent::__construct();
    $this->load->helper('url');                    
    $this->load->model('Welcome_model','welcome');
}

public function index()
{
    
    $this->data['view_data']= $this->welcome->view_data();
  $this->load->view('uploadcsv/excelimport', $this->data, FALSE);
}

public function importbulkemail(){
    $this->load->view('excelimport');
}

public function import(){
 if(isset($_POST["import"]))
  {
      $filename=$_FILES["file"]["tmp_name"];
      if($_FILES["file"]["size"] > 0)
        {
          $file = fopen($filename, "r");
           while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)
           {
                  $data = array(
					  'school_id' => $importdata[0],
					  'student_user_name' => $importdata[1],
					  'student_status' => $importdata[2],
					  'student_password' => $importdata[3],
					  'student_orgpassword' => $importdata[4],
                      'student_unique_no' =>$importdata[5],
					  'student_name' =>$importdata[6],
					  'student_birthdate' =>$importdata[7],	
					  'student_roll_no' => $importdata[8],
					  'student_standard' => $importdata[9],
					  'student_address' => $importdata[10],
					  'student_city' => $importdata[11],
					  'student_phone' => $importdata[12],
					  'student_parent_phone' => $importdata[13],
					  'student_enr_no' => $importdata[14],
					  'student_email' => $importdata[15],
					  'student_branch' => $importdata[16],
					  'student_semester' => $importdata[17],
					  'student_division' => $importdata[18],
                      );
           $insert = $this->welcome->insertCSV($data);
           }                    
          fclose($file);
$this->session->set_flashdata('message', 'Data are imported successfully..');
redirect('student/list_student');
        }else{
$this->session->set_flashdata('message', 'Something went wrong..');
redirect('uploadcsv/index');
    }
  }
}

}