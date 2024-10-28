<?php
// Caminho: /public_html/modules/multi_pipeline/models/Api_model.php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{
    public function is_valid_token($token)
    {
        // Adiciona uma verificação para tokens válidos e ativos
        $this->db->where('token', $token);
        $query = $this->db->get('tblmulti_pipeline_api_tokens');
    
        return $query->num_rows() > 0;
    }
    

    public function get_all_tokens()
    {
        $this->db->select('tblmulti_pipeline_api_tokens.*, tblstaff.firstname, tblstaff.lastname');
        $this->db->from('tblmulti_pipeline_api_tokens');
        $this->db->join('tblstaff', 'tblstaff.staffid = tblmulti_pipeline_api_tokens.user_id', 'left');
        $query = $this->db->get();
        return $query->result();
    }

public function add_token($data)
{
    $this->db->insert('tblmulti_pipeline_api_tokens', [
        'name' => $data['name'],
        'token' => app_generate_hash(),
        'user_id' => $data['user_id'],
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return $this->db->insert_id();
}

public function get_tokens() {
    $this->db->select('*');
    $this->db->from('tblmulti_pipeline_api_tokens');
    $query = $this->db->get();
    return $query->result();
}

public function save_token($token, $name) {
    $data = array(
        'token' => $token,
        'name' => $name
    );
    $this->db->insert('tblmulti_pipeline_api_tokens', $data);
    return $this->db->affected_rows() > 0;
}
}