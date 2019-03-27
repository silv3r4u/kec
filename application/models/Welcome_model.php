<?php
class Welcome_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertCSV($data)
            {
                $this->db->insert('student_detail', $data);
                return TRUE;
            }



    public function view_data(){
        $query=$this->db->query("SELECT im.*
                                 FROM student_detail im 
                                 ORDER BY im.student_id DESC
                                 limit 10");
        return $query->result_array();
    }

}