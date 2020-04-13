<section class="section storage-page">
    <div class="row">
        <div class="col-xl-12">
            <div>
                <div class="row row-eq-height">
                    <div class="col-xl-3">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item upload-files-area">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="btn-group upload-button">
                                            <button type="button" class="btn btn-success ads-select-account" data-toggle="modal" data-target="#file-upload-box">
                                                <i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line('upload_files'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="plan-storage">
                                <?php

                                // Get user storage
                                $user_storage = get_user_option('user_storage', $this->user_id);

                                if (!$user_storage) {
                                    $user_storage = 0;
                                }

                                $plan_storage = 0;

                                $plan_st = plan_feature('storage');

                                if ($plan_st) {

                                    $plan_storage = $plan_st;
                                }

                                // Get percentage
                                $free_space = number_format((100 - (($plan_storage - $user_storage) / $plan_storage) * 100));

                                // Get processbar color
                                if ($free_space < 90) {

                                    $color = ' bg-success';
                                } else {

                                    $color = ' bg-danger';
                                }

                                ?>
                                <div class="row">
                                    <div class="col-4">
                                        <?php
                                        echo $this->lang->line('storage');
                                        ?>
                                    </div>
                                    <div class="col-8 text-right">
                                        <?php

                                        if ($user_storage > 0) {
                                            $user_storage = calculate_size($user_storage);
                                        }

                                        echo $user_storage . '/' . calculate_size($plan_storage);
                                        ?>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar<?php echo $color; ?>" role="progressbar" style="width: <?php echo $free_space; ?>%" aria-valuenow="<?php echo $free_space; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xl-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="file-manager" role="tabpanel">
                                <div class="row page-titles">
                                    <div class="col-xl-6 clean">
                                        <div class="checkbox-option-select">
                                            <input id="storage-select-all-medias" name="storage-select-all-medias" type="checkbox">
                                            <label for="storage-select-all-medias"></label>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-magic-wand"></i> <?php echo $this->lang->line('actions'); ?>
                                            </button>
                                            <div class="dropdown-menu stream-media-actions" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" data-id="1">
                                                    <i class="icon-trash"></i>
                                                    <?php echo $this->lang->line('delete'); ?>
                                                </a>
                                                <a class="dropdown-item" href="#" data-id="2">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                    <?php echo $this->lang->line('add_to_category'); ?>
                                                </a>
                                                <a class="dropdown-item hidden-action" href="#" data-id="3">
                                                    <i class="fas fa-ban"></i>
                                                    <?php echo $this->lang->line('remove_from_category'); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-grip-horizontal"></i> <?php echo $this->lang->line('categories'); ?>
                                            </button>
                                            <div class="dropdown-menu stream-media-categories" aria-labelledby="dropdownMenuButton">
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary show-all-categories" type="button">
                                                <i class="icon-organization"></i> <?php echo $this->lang->line('all_categories'); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 text-right clean">
                                        <div class="btn-group" role="group" aria-label="Buttons Group">
                                            <button type="button" class="btn btn-secondary back-button">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary next-button">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row storage-category-selected">
                                    <div class="col-6">
                                        <p>
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button type="button" class="btn btn-dark stream-delete-category">
                                            <i class="icon-trash"></i>
                                            <?php echo $this->lang->line('delete_category'); ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="row storage-all-media-files">

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
<div class="modal fade" id="storage-create-new-category" tabindex="-1" role="dialog" aria-labelledby="storage-create-new-category" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/storage', ['class' => 'storage-create-new-category', 'data-csrf' => $this->security->get_csrf_token_name()]) ?>
            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo $this->lang->line('create_category'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="storage-category-form-input">
                            <input type="text" class="form-control storage-category-name" placeholder="<?php echo $this->lang->line('enter_category_name'); ?>" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div>
                    <button class="btn storage-category-save" type="submit">
                        <i class="far fa-save"></i>
                        <?php echo $this->lang->line('save'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="storage-add-to-category" tabindex="-1" role="dialog" aria-labelledby="storage-add-to-category" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/storage', ['class' => 'storage-add-to-category', 'data-csrf' => $this->security->get_csrf_token_name()]) ?>
            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo $this->lang->line('add_to_category'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="storage-category-form-input">
                            <select class="form-control storage-select-category">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div>
                    <button class="btn storage-category-save" type="submit">
                        <i class="far fa-save"></i>
                        <?php echo $this->lang->line('save'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="file-upload-box" tabindex="-1" role="dialog" aria-labelledby="file-upload-box" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('file_upload'); ?> <span>( <span class="user-total-storage">
                            <?php
                            echo calculate_size(get_user_option('user_storage', $this->user_id)) . '</span> / ' . calculate_size(plan_feature('storage')) . ')</span>';
                            ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-upload-tab" data-toggle="pill" href="#pills-upload" role="tab" aria-controls="pills-upload" aria-selected="true">
                                    <i class="icon-cloud-upload"></i>
                                    <?php
                                    echo $this->lang->line('upload_files');
                                    ?>
                                </a>
                            </li>
                            <?php
                            if (get_option('app_storage_enable_url_download')) {
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-import-tab" data-toggle="pill" href="#pills-import" role="tab" aria-controls="pills-import" aria-selected="false">
                                        <i class="icon-link"></i>
                                        <?php
                                        echo $this->lang->line('import_urls');
                                        ?>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-8">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-upload" role="tabpanel" aria-labelledby="pills-upload-tab">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-selected-upload-category dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-grip-horizontal"></i> <?php echo $this->lang->line('categories'); ?>
                                        </button>
                                        <div class="dropdown-menu upload-media-categories" aria-labelledby="dropdownMenuButton">
                                        </div>
                                    </div>
                                    <div class="drag-and-drop-files">
                                    <div>
                                        <i class="icon-cloud-upload"></i><br>
                                        <?php
                                        echo $this->lang->line('drag_drop_files');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (get_option('app_storage_enable_url_download')) {
                            ?>
                                <div class="tab-pane fade" id="pills-import" role="tabpanel" aria-labelledby="pills-import-tab">
                                    <div class="import-images-from-url">
                                        <?php echo form_open('user/app/storage', array('class' => 'download-images-from-url', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <textarea class="imported-urls" placeholder="<?php echo $this->lang->line('enter_urls_one_per_line'); ?>" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <button type="submit" class="btn btn-download-photos"><i class="icon-cloud-download"></i> <?php echo $this->lang->line('download'); ?></button>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!--Upload image form !-->
                <?php
                $attributes = array('class' => 'upim d-none', 'id' => 'upim', 'method' => 'post', 'data-csrf' => $this->security->get_csrf_token_name());
                echo form_open_multipart('user/app/posts', $attributes);
                ?>
                <input type="hidden" name="type" id="type" value="video">
                <input type="file" name="file[]" id="file" accept=".gif,.jpg,.jpeg,.png,.mp4,.avi" multiple>
                <input type="hidden" name="category" id="category" value="0">
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Translations !-->
<script language="javascript">
    var words = {
        please_select_a_media: '<?php echo $this->lang->line('please_select_a_media'); ?>',
        please_select_a_category: '<?php echo $this->lang->line('please_select_a_category'); ?>',
        create_category: '<?php echo $this->lang->line('create_category'); ?>',
        select_category: '<?php echo $this->lang->line('select_a_category'); ?>',
        no_files_found: '<?php echo $this->lang->line('no_files_found'); ?>',
    };
</script>