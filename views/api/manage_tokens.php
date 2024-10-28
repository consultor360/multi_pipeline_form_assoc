<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
// Caminho: /public_html/modules/multi_pipeline/views/api/manage_tokens.php

<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h2 class="mb-4"><?php echo _l('manage_api_tokens'); ?></h2>
                        <button type="button" class="btn btn-primary mb-4 float-right" data-toggle="modal" data-target="#addTokenModal">
                            <?php echo _l('add_token'); ?>
                        </button>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('id'); ?></th>
                                        <th><?php echo _l('user'); ?></th>
                                        <th><?php echo _l('name'); ?></th>
                                        <th><?php echo _l('token'); ?></th>
                                        <th><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($tokens) && count($tokens) > 0): ?>
                                        <?php foreach ($tokens as $token): ?>
                                            <tr>
                                                <td><?php echo $token->id; ?></td>
                                                <td><?php echo isset($token->firstname) && isset($token->lastname) ? $token->firstname . ' ' . $token->lastname : 'N/A'; ?></td> <!-- Ajuste aqui -->
                                                <td><?php echo $token->name; ?></td>
                                                <td><?php echo $token->token; ?></td>
                                                <td>
                                                    <a href="<?php echo admin_url('api/edit_token/' . $token->id); ?>" class="btn btn-warning btn-icon">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('api/delete_token/' . $token->id); ?>" class="btn btn-danger btn-icon" onclick="return confirm('<?php echo _l('are_you_sure'); ?>');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center"><?php echo _l('no_tokens_found'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Adicionar Token -->
<div class="modal fade" id="addTokenModal" tabindex="-1" role="dialog" aria-labelledby="addTokenModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTokenModalLabel"><?php echo _l('add_token'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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

<?php init_tail(); ?>