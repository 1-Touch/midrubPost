<section class="teams-page">
    <div class="container-fluid">
        <?php
        if (team_members_total() >= plan_feature('teams')) {
            ?>
        <div class="row">
            <div class="col-xl-12">
                <div class="reached-plan-limit">
                    <div class="row">
                        <div class="col-xl-9">
                            <i class="icon-info"></i>
                            <?php echo $this->lang->line('reached_maximum_number_allowed_members'); ?>
                        </div>
                        <div class="col-xl-3 text-right">
                            <a href="<?php echo site_url('user/plans') ?>" class="btn"><i class="icon-basket"></i> <?php echo $this->lang->line('our_plans'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-xl-3">
                <div class="col-xl-12 theme-box">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xl-10">
                                    <h3>
                                        <i class="icon-user-follow"></i>
                                        <?php echo $this->lang->line('new_team_member'); ?>
                                    </h3>
                                </div>
                                <div class="col-xl-2 text-right">
                                    <button type="button" class="team-member-roles" data-toggle="modal" data-target="#member-roles-popup">
                                        <i class="icon-user-following"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php echo form_open('user/teams', array('class' => 'new-member', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                            <div class="list-group">
                                <div class="list-group-item" id="input-fields">
                                    <div class="form-group">
                                        <input class="form-control username" type="text" placeholder="<?php echo $this->lang->line('enter_username'); ?>" value="m_" required>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control email" type="text" placeholder="<?php echo $this->lang->line('member_email'); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control member-role" required>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control member-status">
                                            <option disabled><?php echo $this->lang->line('member_status'); ?></option>
                                            <option value="0"><?php echo $this->lang->line('active'); ?></option>
                                            <option value="1"><?php echo $this->lang->line('inactive'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control member-about" placeholder="<?php echo $this->lang->line('about_member'); ?>"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control password" type="password" placeholder="<?php echo $this->lang->line('enter_password'); ?>" required>
                                    </div>
                                    <div class="form-group buttons-control">
                                        <button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> <?php echo $this->lang->line('save_member'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="col-xl-12">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-members-tab" data-toggle="tab" href="#nav-members" role="tab" aria-controls="nav-members" aria-selected="true">
                                <?php echo $this->lang->line('members'); ?>
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-members" role="tabpanel" aria-labelledby="nav-members">
                            <div class="container-fluid">
                                <div class="list row team-member-list">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="team-member-info" tabindex="-1" role="dialog" aria-labelledby="team-member-info" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-member-info-tab" data-toggle="tab" href="#nav-member-info" role="tab" aria-controls="nav-member-info" aria-selected="true">
                            <?php echo $this->lang->line('info'); ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-member-settings-tab" data-toggle="tab" href="#nav-member-settings" role="tab" aria-controls="nav-member-settings" aria-selected="false">
                            <?php echo $this->lang->line('settings'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-member-info" role="tabpanel" aria-labelledby="nav-member-info">
                        <ul class="list-group ">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-xl-8">
                                        <?php echo $this->lang->line('member_id'); ?>
                                    </div>
                                    <div class="col-xl-4 member-id">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ">
                                <div class="row">
                                    <div class="col-xl-8">
                                        <?php echo $this->lang->line('last_access'); ?>
                                    </div>
                                    <div class="col-xl-4 last-access">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ">
                                <div class="row">
                                    <div class="col-xl-8">
                                        <?php echo $this->lang->line('joined_on'); ?>
                                    </div>
                                    <div class="col-xl-4 joined-on">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-xl-8">
                                        <?php echo $this->lang->line('member_status'); ?>
                                    </div>
                                    <div class="col-xl-4 member-status">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ">
                                <div class="row">
                                    <div class="col-xl-8">
                                        <?php echo $this->lang->line('member_role'); ?>
                                    </div>
                                    <div class="col-xl-4 member-role">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ">
                                <div class="row">
                                    <div class="col-xl-5">
                                        <?php echo $this->lang->line('about_member'); ?>
                                    </div>
                                    <div class="col-xl-7 about-member">
                                        <p>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="nav-member-settings" role="tabpanel" aria-labelledby="nav-member-settings">
                        <?php echo form_open('user/teams', array('class' => 'update-member-data')) ?>
                        <div class="list-group">
                            <div class="list-group-item" id="input-fields">
                                <div class="form-group">
                                    <input class="form-control username" type="text" placeholder="<?php echo $this->lang->line('enter_username'); ?>" value="" disabled>
                                </div>
                                <div class="form-group">
                                    <input class="form-control email" type="text" placeholder="<?php echo $this->lang->line('member_email'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <select class="form-control member-role">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control member-status">
                                        <option disabled><?php echo $this->lang->line('member_status'); ?></option>
                                        <option value="0"><?php echo $this->lang->line('active'); ?></option>
                                        <option value="1"><?php echo $this->lang->line('inactive'); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control member-about" placeholder="<?php echo $this->lang->line('about_member'); ?>"></textarea>
                                </div>
                                <div class="form-group">
                                    <input class="form-control password" type="password" placeholder="<?php echo $this->lang->line('enter_password'); ?>">
                                </div>
                                <div class="form-group buttons-control">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-sm-6 col-xs-6">
                                            <button type="button" class="btn btn-danger pull-left delete-member"><i class="icon-trash"></i> <?php echo $this->lang->line('delete_member'); ?></button>
                                            <p class="pull-left confirm"><?php echo $this->lang->line('are_you_sure'); ?> <a href="#" class="delete-member-account yes"><?php echo $this->lang->line('yes'); ?></a><a href="#" class="no"><?php echo $this->lang->line('no'); ?></a></p>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-sm-6 col-xs-6 text-right">
                                            <button type="submit" class="btn btn-success pull-right"><i class="far fa-save"></i> <?php echo $this->lang->line('update_member'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="member-roles-popup" tabindex="-1" role="dialog" aria-labelledby="member-roles-popup" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-member-roles-tab" data-toggle="tab" href="#nav-member-roles" role="tab" aria-controls="nav-member-roles" aria-selected="true">
                            <?php echo $this->lang->line('roles'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-member-roles" role="tabpanel" aria-labelledby="nav-member-roles">
                        <?php echo form_open('user/teams', array('class' => 'create-new-role', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control team-new-role" placeholder="<?php echo $this->lang->line('enter_the_role_name'); ?>" aria-describedby="basic-addon2" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success pull-right">
                                    <i class="far fa-save"></i>
                                    <?php echo $this->lang->line('save_role'); ?>
                                </button>
                            </div>
                        </div>
                        <?php echo form_close() ?>
                        <div class="accordion" id="accordionExample">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <ul class="pagination" data-type="team-roles">

                </ul>
            </div>
        </div>
    </div>
</div>