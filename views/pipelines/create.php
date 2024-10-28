<?php 
// Caminho: /public_html/modules/multi_pipeline/views/pipelines/create.php

defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo _l('create_pipeline'); ?>
                        </h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(admin_url('multi_pipeline/create_pipeline')); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('name', 'pipeline_name'); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_textarea('description', 'pipeline_description'); ?>
                            </div>
                        </div>

                        <!-- Adicionando os campos de seleção de membros e funções -->
                        <?php
                        // Carregar membros da equipe e funções
                        $staff_and_roles = $this->multi_pipeline_model->get_staff_and_roles();
                        ?>

                        <div class="form-group">
                        <div class="col-md-6">
                            <label for="staff_ids">Atribuir Pipeline a Usuários</label>
                            <select name="staff_ids[]" class="form-control selectpicker" multiple data-live-search="true">
                                <?php foreach ($staff_and_roles['staff'] as $staff): ?>
                                    <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                        <div class="col-md-6">
                            <label for="role_ids">Atribuir Pipeline a Funções</label>
                            <select name="role_ids[]" class="form-control selectpicker" multiple data-live-search="true">
                                <?php foreach ($staff_and_roles['roles'] as $role): ?>
                                    <option value="<?php echo $role['roleid']; ?>"><?php echo $role['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
    <div class="col-md-12" style="margin-top: 20px;"> <!-- Adicionado margin-top aqui -->
        <button type="submit" class="btn btn-primary pull-right">
            <?php echo _l('submit'); ?>
        </button>
    </div>
</div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
$(function() {
    appValidateForm($('form'), {
        name: 'required',
    });
});
</script>
<script>
$(function() {
    $('.selectpicker').selectpicker();
});
</script>