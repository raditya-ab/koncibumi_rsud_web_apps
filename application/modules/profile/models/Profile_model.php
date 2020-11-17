<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {

  	function get_profile_data($member_id){
		$qry_all = "SELECT m.*,mg.name,m.username FROM members as m 
		INNER JOIN member_group as mm ON (mm.member_id = m.id)
		INNER JOIN master_group as mg ON (mm.group_id = mg.id )
        WHERE 1 AND m.id = ? ";
		$run_all = $this->db->query($qry_all, array($member_id));
		$res_all = $run_all->result_array();
		return $res_all;

    }
    
}

?>