<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
// Caminho: /public_html/modules/multi_pipeline/views/api/add_token.php

<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h2 class="mb-4"><?php echo _l('add_api_token'); ?></h2>
                        <?php echo form_open('api/add_token', array('class'=>'form-horizontal')); ?>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label"><?php echo _l('name'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <?php echo form_open('multi_pipeline/api/add_token'); ?>
<div class="form-group">
    <label for="name"><?php echo _l('token_name'); ?></label>
    <input type="text" class="form-control" id="name" name="name" required>
</div>
<?php echo form_hidden('user_id', get_staff_user_id()); ?>
<button type="submit" class="btn btn-primary"><?php echo _l('add_token'); ?></button>
<?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>