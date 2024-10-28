<?php

// Caminho: public_html/modules/multi_pipeline/config/multi_pipeline.php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Multi Pipeline Module Configuration
 */

// Database tables
$config['multi_pipeline_pipelines_table'] = db_prefix() . 'multi_pipeline_pipelines';
$config['multi_pipeline_stages_table'] = db_prefix() . 'multi_pipeline_stages';
$config['multi_pipeline_leads_table'] = db_prefix() . 'multi_pipeline_leads';
$config['multi_pipeline_lead_stages_table'] = db_prefix() . 'lead_stages';
$config['multi_pipeline_lead_activities_table'] = db_prefix() . 'multi_pipeline_lead_activities';
$config['multi_pipeline_lead_notes_table'] = db_prefix() . 'multi_pipeline_lead_notes';
$config['multi_pipeline_pipeline_lead_table'] = db_prefix() . 'pipeline_lead';
$config['multi_pipeline_stage_transitions_table'] = db_prefix() . 'multi_pipeline_stage_transitions';

// Module settings
$config['multi_pipeline_default_pipeline'] = 1;
$config['multi_pipeline_max_pipelines'] = 10;
$config['multi_pipeline_enable_import'] = true;
$config['multi_pipeline_enable_export'] = true;

$route['admin/multi_pipeline/view_pipeline'] = 'Pipelines/view_pipeline';
$route['admin/multi_pipeline'] = 'multi_pipeline/multi_pipeline/index';
$route['admin/multi_pipeline/(:any)'] = 'multi_pipeline/multi_pipeline/$1';
$route['admin/api/add_token'] = 'multi_pipeline/api/add_token';
$autoload['model'] = array('Api_model', 'Lead_model');
// config/multi_pipeline.php
$config['api'] = [
    'enabled' => TRUE,
];
$route['multi_pipeline/api/add_lead'] = 'multi_pipeline/api/add_lead';

// End of file multi_pipeline.php