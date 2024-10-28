<?php
// Caminho: /public_html/modules/multi_pipeline/controllers/Api.php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller // Mudança para estender Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->model('Lead_model');
        $this->load->library('form_validation');
    }

    public function add_lead()
    {
        // Autenticação
        $token = $this->input->get_request_header('Authorization');
        if (!$this->Api_model->is_valid_token($token)) {
            $this->output->set_status_header(403);  // Retorno correto para autenticação inválida
            echo json_encode(['status' => 'error', 'message' => 'Autenticação inválida', 'code' => 403]);
            return;
        }
    
        // Validação de dados
        $this->form_validation->set_rules('name', 'Nome', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('pipeline_id', 'Pipeline ID', 'required|integer');
        $this->form_validation->set_rules('stage_id', 'Stage ID', 'required|integer');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');
        $this->form_validation->set_rules('source', 'Fonte', 'required|trim');
    
        // Validação dos campos opcionais
        $optional_fields = ['title', 'company', 'description', 'country', 'zip', 'city', 'state', 'address', 'assigned', 'phonenumber', 'website', 'is_public', 'lead_value'];
        foreach ($optional_fields as $field) {
            $this->form_validation->set_rules($field, ucfirst($field), 'trim');
        }
    
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => validation_errors(), 'code' => 400]);
            return;
        }
    
        // Preparar dados do lead
        $lead_data = [];
        $all_fields = array_merge(['name', 'email', 'pipeline_id', 'stage_id', 'status', 'source'], $optional_fields);
        foreach ($all_fields as $field) {
            $value = $this->input->post($field);
            if ($value !== false) {
                $lead_data[$field] = $value;
            }
        }
    
        // Adicionar lead usando a nova função do modelo
        $result = $this->Lead_model->add_lead_api($lead_data);
    
        // Definir o código de status HTTP com base no resultado
        $this->output->set_status_header($result['code']);
    
        // Responder com o resultado em formato JSON
        echo json_encode([
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message'],
            'lead_id' => isset($result['lead_id']) ? $result['lead_id'] : null
        ]);
    }

    public function add_token()
    {
    
        // Capturar os dados do formulário
        $data = $this->input->post();
    
        // Adicionar o user_id do usuário atual
        $data['user_id'] = get_staff_user_id();
    
        // Validação dos dados
        $this->form_validation->set_rules('name', 'Nome do Token', 'required|trim');
    
        if ($this->form_validation->run() == FALSE) {
            // Se a validação falhar, redirecionar de volta com erro
            set_alert('danger', _l('form_validation_error'));
            redirect(admin_url('multi_pipeline/api/manage_tokens'));
        } else {
            // Se a validação passar, adicionar o token
            $token_id = $this->Api_model->add_token($data);
    
            if ($token_id) {
                set_alert('success', _l('token_added_successfully'));
            } else {
                set_alert('danger', _l('error_adding_token'));
            }
    
            redirect(admin_url('multi_pipeline/api/manage_tokens'));
        }
    }

    public function manage_tokens() {
        $data['tokens'] = $this->Api_model->get_tokens();
        $this->load->view('manage_tokens', $data);
    }
}