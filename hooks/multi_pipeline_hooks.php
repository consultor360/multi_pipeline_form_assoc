<?php
// Caminho: /public_html/modules/multi_pipeline/hooks/multi_pipeline_hooks.php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Multi Pipeline Module Hooks
 */

/**
 * Register necessary hooks for the Multi Pipeline module
 */
hooks()->add_action('admin_init', 'multi_pipeline_module_init_menu_items');
hooks()->add_action('leads_status_changed', 'multi_pipeline_update_lead_status');
hooks()->add_action('lead_created', 'multi_pipeline_handle_web_form_lead');


/**
 * Initialize Multi Pipeline menu items
 *
 * @return void
 */
function multi_pipeline_module_init_menu_items()
{
    $CI = &get_instance();
    
    if (has_permission('multi_pipeline', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('multi_pipeline', [
            'name'     => _l('multi_pipeline'),
            'href'     => admin_url('multi_pipeline'),
            'position' => 30,
            'icon'     => 'fa fa-sitemap',
        ]);
    }
}

$config['hooks'] = array(
    // ...
    'post_module_install' => array(
        // ...
        'multi_pipeline:multi_pipeline_activation_hook'
    )
);

/**
 * Update lead status in Multi Pipeline when changed in Perfex CRM
 *
 * @param int $lead_id
 * @param array $data
 * @return void
 */
function multi_pipeline_update_lead_status($lead_id, $data)
{
    $CI = &get_instance();
    $CI->load->model('multi_pipeline_model');
    $CI->multi_pipeline_model->update_lead_status($lead_id, $data['status']);
}

/**
 * Assign new lead to appropriate pipeline based on form ID
 *
 * @param int $lead_id
 * @return void
 */
function multi_pipeline_assign_lead_to_pipeline($lead_id)
{
    $CI = &get_instance();
    $CI->load->model('multi_pipeline_model');
    $CI->multi_pipeline_model->assign_lead_to_pipeline($lead_id);
}

function multi_pipeline_handle_web_form_lead($data) {
    $CI =& get_instance();
    $CI->load->model('multi_pipeline/multi_pipeline_model');

    // Extrai o lead_id se o parâmetro é um array contendo o lead_id
    if (is_array($data) && isset($data['lead_id'])) {
        $lead_id = intval($data['lead_id']);
    } elseif (is_numeric($data)) {
        $lead_id = intval($data);  // Caso seja apenas o ID
    } else {
        log_message('error', "Formato de entrada inválido para lead_id: " . json_encode($data));
        return;
    }

    // Limpa qualquer cache para garantir a recuperação de dados atualizados
    $CI->db->flush_cache();

    // Obtém os dados do lead recém criado diretamente do banco de dados
    $lead = $CI->db->get_where('tblleads', ['id' => $lead_id])->row();
    if (!$lead) {
        log_message('error', "Lead ID $lead_id não encontrado.");
        return;
    }

    log_message('info', "Lead encontrado: ID {$lead->id}");

    // Verifica se o lead foi criado por um formulário web
    if (isset($lead->from_form_id) && intval($lead->from_form_id) > 0) {
        log_message('info', "from_form_id detectado: " . $lead->from_form_id);
        
        // Busca a associação do formulário
        $form_association = $CI->db->select('*')
            ->from('tblmulti_pipeline_form_associations')
            ->where('form_id', $lead->from_form_id)
            ->get()->row();
        
        if ($form_association) {
            log_message('info', "Associação de formulário encontrada para form_id: {$lead->from_form_id}");
            
            // Dados para atualização
            $update_data = [
                'pipeline_id' => $form_association->pipeline_id,
                'stage_id'    => $form_association->stage_id
            ];
            
            // Executa a atualização
            $CI->db->where('id', $lead_id);
            if ($CI->db->update('tblleads', $update_data)) {
                log_message('info', "Lead ID $lead_id atualizado com pipeline ID: {$form_association->pipeline_id} e stage ID: {$form_association->stage_id}");
                
                // Registra atividade do lead
                $CI->load->model('leads_model');
                $CI->leads_model->log_lead_activity(
                    $lead_id,
                    sprintf(
                        'Lead adicionado ao pipeline ID: %s, estágio ID: %s via formulário web',
                        $form_association->pipeline_id,
                        $form_association->stage_id
                    )
                );
            } else {
                log_message('error', "Erro ao atualizar o lead ID $lead_id com pipeline e estágio.");
            }
        } else {
            log_message('info', "Nenhuma associação de formulário encontrada para form_id: " . $lead->from_form_id);
        }
    } else {
        log_message('info', "Lead ID $lead_id não possui from_form_id válido.");
    }
}
