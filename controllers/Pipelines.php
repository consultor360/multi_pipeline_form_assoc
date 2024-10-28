<?php
// Caminho: /public_html/modules/multi_pipeline/controllers/Pipelines.php

defined('BASEPATH') or exit('No direct script access allowed');

class Pipelines extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Multi_pipeline_model');
        $this->load->model('Lead_model');
        $this->load->model('multi_pipeline_model');
    }

    public function index()
    {
        // Busca todos os pipelines da tabela tblmulti_pipeline_pipelines
        $pipelines = $this->db->get('tblmulti_pipeline_pipelines')->result();
        
        // Prepara os dados para a view
        $data['pipelines'] = $pipelines;
        $data['title'] = _l('pipelines'); // Mantém o título existente
        
        // Carrega a view com os dados
        $this->load->view('pipelines/view', $data);
    }
    
    public function view($id)
    {
        if (!has_permission('multi_pipeline', '', 'view')) {
            access_denied('multi_pipeline');
        }

        $pipeline = $this->multi_pipeline_model->get($id);
        if (!$pipeline) {
            show_404();
        }

        // Obter os leads específicos para este pipeline
        $data['leads'] = $this->multi_pipeline_model->get_leads($id);
        $data['multi_pipeline_pipelines'] = $this->multi_pipeline_model->get_pipelines();

        $this->load->view('multi_pipeline/pipelines/list', $data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = $this->multi_pipeline_model->add_pipeline($data);
            if ($id) {
                set_alert('success', _l('pipeline_added_successfully'));
                redirect(admin_url('multi_pipeline/pipelines'));
            }
        }
        $data['title'] = _l('new_pipeline');
        $this->load->view('multi_pipeline/pipelines/create', $data);
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = $this->input->post();
    
            // Remover staff_ids e role_ids do array $data, pois eles não fazem parte da tabela tblmulti_pipeline_pipelines
            unset($data['staff_ids'], $data['role_ids']);
    
            // Atualiza o pipeline (dados gerais, sem staff_ids e role_ids)
            $success = $this->multi_pipeline_model->update_pipeline($id, $data);
    
            // Variável de controle para alterações de atribuições
            $assignmentsUpdated = false;
    
            // Atualiza as atribuições de membros e funções (remover e reatribuir)
            $staff_ids = $this->input->post('staff_ids');
            if ($staff_ids !== null && is_array($staff_ids)) {
                $this->multi_pipeline_model->update_pipeline_assignments($id, $staff_ids, 'staff');
                $assignmentsUpdated = true;
            }
    
            $role_ids = $this->input->post('role_ids');
            if ($role_ids !== null && is_array($role_ids)) {
                $this->multi_pipeline_model->update_pipeline_assignments($id, $role_ids, 'role');
                $assignmentsUpdated = true;
            }
    
            // Verifica se houve sucesso na atualização de dados gerais ou nas atribuições
            if ($success || $success === 0 || $assignmentsUpdated) {
                set_alert('success', _l('pipeline_updated_successfully'));
                redirect(admin_url('multi_pipeline/pipelines'));
            } else {
                set_alert('danger', _l('pipeline_update_failed'));
            }
        }
    
        // Carrega os dados do pipeline
        $data['pipeline'] = $this->multi_pipeline_model->get_pipeline($id);
        if (!$data['pipeline']) {
            show_404();
        }
    
        // Carrega as atribuições do pipeline
        $data['pipeline_assignments'] = $this->multi_pipeline_model->get_pipeline_assignments($id);
        $data['title'] = _l('edit_pipeline');
    
        // Carrega a view de edição do pipeline
        $this->load->view('multi_pipeline/pipelines/edit', $data);
    }
         

    public function delete($id)
    {
        if ($this->multi_pipeline_model->delete_pipeline($id)) {
            set_alert('success', _l('pipeline_deleted_successfully'));
        } else {
            set_alert('warning', _l('problem_deleting_pipeline'));
        }
        redirect(admin_url('multi_pipeline/pipelines'));
    }
    
    public function edits($id)
{
    if (!has_permission('multi_pipeline', '', 'edit')) {
        access_denied('multi_pipeline');
    }

    if ($this->input->post()) {
        $data = $this->input->post();
        $success = $this->multi_pipeline_model->update_pipeline($id, $data);
        if ($success) {
            set_alert('success', _l('updated_successfully', _l('pipeline')));
            redirect(admin_url('multi_pipeline/pipelines'));
        }
    }

    $pipeline = $this->multi_pipeline_model->get_pipelines($id);
    if (!$pipeline) {
        show_404();
    }

    $data['pipeline'] = $pipeline;
    $data['title'] = _l('edit_pipeline');
    
    $this->load->view('multi_pipeline/pipelines/edit', $data);
}
    
}