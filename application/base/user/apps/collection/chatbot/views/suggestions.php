<section class="chatbot-page-suggestions" <?php echo isset($group_id) ? ' data-group="' . $group_id . '"' : ''; ?>>
    <div class="row">
        <div class="col-12">
            <div class="theme-box new-suggestions-top">
                <?php echo form_open('user/app/chatbot', array('class' => 'save-suggestions-form', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control group-name" placeholder="<?php echo $this->lang->line('chatbot_enter_name_suggestions_group'); ?>" value="<?php echo isset($group_name) ? htmlspecialchars($group_name) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-secondary theme-background-blue chatbot-save-suggestions">
                            <i class="lni-save"></i>
                            <?php echo $this->lang->line('save'); ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="suggestions-categories">
                <div class="row">
                    <div class="col-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <button class="btn btn-primary" type="button">
                                    <i class="far fa-bookmark"></i>
                                    <?php echo $this->lang->line('chatbot_uncategorized'); ?>
                                </button>
                                <?php
                                if ( isset($categories) ) {

                                    foreach ($categories as $category) {

                                        echo '<button class="btn btn-primary select-category" type="button" data-id="' . $category['category_id'] . '">'
                                                . '<i class="far fa-bookmark"></i>'
                                                . htmlspecialchars($category['name'])
                                            . '</button>';

                                    }
                                    
                                }
                                ?>
                                <button class="btn btn-success select-categories" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="categories">
                                    <span>&#43;</span>
                                </button>
                            </div>
                            <div class="panel-body collapse multi-collapse" id="categories">
                                <div class="row row-eq-height">
                                    <div class="col-12 col-lg-4 col-xl-2">
                                        <button type="button" class="btn btn-secondary theme-color-black" data-toggle="modal" data-target="#categories-manager">
                                            <i class="icon-settings"></i>
                                            <?php echo $this->lang->line('chatbot_manage_categories'); ?>
                                        </button>
                                    </div>
                                    <div class="col-12 col-lg-8 col-xl-10">
                                        <div class="row">
                                            <div class="col-12">
                                                <?php echo form_open('user/app/chatbot', array('class' => 'search-categories', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <i class="icon-magnifier"></i>
                                                    </div>
                                                    <input type="text" class="form-control search-category" placeholder="<?php echo $this->lang->line('chatbot_search_categories'); ?>">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn input-group-text cancel-categories-search">
                                                            <i class="icon-close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 all-categories-list">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="new-suggestions-body">
                <div class="suggestions-body-area">
                    <div class="row">
                        <div class="col-12">
                            <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">
                                <div class="toast" role="main-list" aria-live="assertive" aria-atomic="true">
                                    <div class="toast-body text-center">
                                        <?php echo $this->lang->line('chatbot_start_conversation'); ?>
                                    </div>
                                    <div class="toast-footer show-suggestion-button-add">
                                        <button type="button" class="btn btn-primary theme-color-black add-new-suggestion">
                                            <i class="lni-add-file"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="line-main-bottom"></div>
                            <div class="line-main-left"></div>
                            <div class="line-main-right"></div>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Manager -->
<div class="modal fade" id="categories-manager" tabindex="-1" role="dialog" aria-boostledby="categories-manager" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <?php echo $this->lang->line('chatbot_categories_manager'); ?>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_new_category'); ?>
                            </legend>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php echo form_open('user/app/chatbot', array('class' => 'chatbot-create-category', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                    <div class="input-group">
                                        <input type="text" class="form-control category-name" name="category-name" placeholder="<?php echo $this->lang->line('chatbot_enter_category_name'); ?>">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="lni-save"></i>
                                                <?php echo $this->lang->line('chatbot_save'); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_all_categories'); ?>
                            </legend>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <ul class="all-categories">
                                    </ul>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset class="fieldset-pagination">
                            <nav>
                                <ul class="pagination" data-type="categories">
                                </ul>
                            </nav>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Suggestions Manager -->
<div class="modal fade" id="suggestions-manager" tabindex="-1" role="dialog" aria-boostledby="suggestions-manager" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <?php echo $this->lang->line('chatbot_suggestions_manager'); ?>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('user/app/chatbot', array('class' => 'chatbot-save-template', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="row">
                    <div class="col-md-4">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_category'); ?>
                            </legend>
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="v-pills-generic-template-tab" data-toggle="pill" href="#v-pills-generic-template" role="tab" aria-controls="v-pills-generic-template" aria-selected="true">
                                    <?php echo $this->lang->line('chatbot_generic_template'); ?>
                                </a>
                                <a class="nav-link" id="v-pills-media-template-tab" data-toggle="pill" href="#v-pills-media-template" role="tab" aria-controls="v-pills-media-template" aria-selected="false">
                                    <?php echo $this->lang->line('chatbot_media_template'); ?>
                                </a>
                                <a class="nav-link" id="v-pills-button-template-tab" data-toggle="pill" href="#v-pills-button-template" role="tab" aria-controls="v-pills-button-template" aria-selected="false">
                                    <?php echo $this->lang->line('chatbot_button_template'); ?>
                                </a>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-8">
                        <fieldset>
                            <legend>
                                <?php echo $this->lang->line('chatbot_template'); ?>
                            </legend>
                            <div class="tab-content" id="template-preview">
                                <div class="tab-pane template-model fade show active" id="v-pills-generic-template" role="tabpanel" aria-labelledby="v-pills-generic-template-tab" data-type="generic-template">
                                    <div class="panel-group" id="generic-template">
                                        <div class="panel panel-default" data-target="header">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#generic-template" href="#generic-template-header" aria-expanded="true">
                                                        <?php echo $this->lang->line('chatbot_header'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="generic-template-header" class="panel-collapse collapse in show">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="drag-and-drop-files">
                                                            <?php echo $this->lang->line('chatbot_drag_image_click_to_upload'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control template-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_template_title'); ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control template-subtitle" name="subtitle" placeholder="<?php echo $this->lang->line('chatbot_enter_template_subtitle'); ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control template-main-url" name="url" placeholder="<?php echo $this->lang->line('chatbot_enter_main_url'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" data-target="option-1">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#generic-template" href="#generic-template-button-1">
                                                        <?php echo $this->lang->line('chatbot_button_1'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="generic-template-button-1" class="panel-collapse collapse">
                                                <div class="accordion" id="accordion-generic-template-button-1">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#generic-template-button-1-menu-link" aria-expanded="true" aria-controls="generic-template-button-1-menu-link">
                                                                <?php echo $this->lang->line('chatbot_link'); ?>
                                                            </button>
                                                        </div>

                                                        <div id="generic-template-button-1-menu-link" class="collapse show" aria-labelledby="generic-template-button-1-menu-link" data-parent="#accordion-generic-template-button-1" data-type="link">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-link" name="link" placeholder="<?php echo $this->lang->line('chatbot_enter_button_url'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header" id="headingTwo">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#generic-template-button-1-menu-suggestions" aria-expanded="false" aria-controls="generic-template-button-1-menu-suggestions">
                                                                <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                                            </button>
                                                        </div>
                                                        <div id="generic-template-button-1-menu-suggestions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-generic-template-button-1" data-type="suggestions-group">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" data-target="option-2">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#generic-template" href="#generic-template-button-2">
                                                        <?php echo $this->lang->line('chatbot_button_2'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="generic-template-button-2" class="panel-collapse collapse">
                                                <div class="accordion" id="accordion-generic-template-button-2">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#generic-template-button-2-menu-link" aria-expanded="true" aria-controls="generic-template-button-2-menu-link">
                                                                <?php echo $this->lang->line('chatbot_link'); ?>
                                                            </button>
                                                        </div>

                                                        <div id="generic-template-button-2-menu-link" class="collapse show" aria-labelledby="generic-template-button-2-menu-link" data-parent="#accordion-generic-template-button-2" data-type="link">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-link" name="link" placeholder="<?php echo $this->lang->line('chatbot_enter_button_url'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header" id="headingTwo">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#generic-template-button-2-menu-suggestions" aria-expanded="false" aria-controls="generic-template-button-2-menu-suggestions">
                                                                <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                                            </button>
                                                        </div>
                                                        <div id="generic-template-button-2-menu-suggestions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-generic-template-button-2" data-type="suggestions-group">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" data-target="option-3">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#generic-template" href="#generic-template-button-3">
                                                        <?php echo $this->lang->line('chatbot_button_3'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="generic-template-button-3" class="panel-collapse collapse">
                                                <div class="accordion" id="accordion-generic-template-button-3">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#generic-template-button-3-menu-link" aria-expanded="true" aria-controls="generic-template-button-3-menu-link">
                                                                <?php echo $this->lang->line('chatbot_link'); ?>
                                                            </button>
                                                        </div>

                                                        <div id="generic-template-button-3-menu-link" class="collapse show" aria-labelledby="generic-template-button-3-menu-link" data-parent="#accordion-generic-template-button-3" data-type="link">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-link" name="link" placeholder="<?php echo $this->lang->line('chatbot_enter_button_url'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header" id="headingTwo">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#generic-template-button-3-menu-suggestions" aria-expanded="false" aria-controls="generic-template-button-3-menu-suggestions">
                                                                <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                                            </button>
                                                        </div>
                                                        <div id="generic-template-button-3-menu-suggestions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-generic-template-button-3" data-type="suggestions-group">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane template-model fade" id="v-pills-media-template" role="tabpanel" aria-labelledby="v-pills-media-template-tab" data-type="media-template">
                                    <div class="panel-group" id="media-template">
                                        <div class="panel panel-default" data-target="header">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#media-template" href="#media-template-header" aria-expanded="true">
                                                        <?php echo $this->lang->line('chatbot_header'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="media-template-header" class="panel-collapse collapse in show">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="drag-and-drop-files">
                                                            <?php echo $this->lang->line('chatbot_drag_image_click_to_upload'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" data-target="option-1">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#media-template" href="#media-template-button-1">
                                                        <?php echo $this->lang->line('chatbot_button_1'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="media-template-button-1" class="panel-collapse collapse">
                                                <div class="accordion" id="accordion-media-template-button-1">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#media-template-button-1-menu-link" aria-expanded="true" aria-controls="media-template-button-1-menu-link" disabled>
                                                                <?php echo $this->lang->line('chatbot_link'); ?>
                                                            </button>
                                                        </div>

                                                        <div id="media-template-button-1-menu-link" class="collapse show" aria-labelledby="media-template-button-1-menu-link" data-parent="#accordion-media-template-button-1" data-type="link">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-link" name="link" placeholder="<?php echo $this->lang->line('chatbot_enter_button_url'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane template-model fade" id="v-pills-button-template" role="tabpanel" aria-labelledby="v-pills-button-template-tab" data-type="button-template">
                                    <div class="panel-group" id="button-template">
                                        <div class="panel panel-default" data-target="header">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#button-template" href="#button-template-header" aria-expanded="true">
                                                        <?php echo $this->lang->line('chatbot_header'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="button-template-header" class="panel-collapse collapse in show">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control template-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_template_title'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" data-target="option-1">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#button-template" href="#button-template-button-1">
                                                        <?php echo $this->lang->line('chatbot_button_1'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="button-template-button-1" class="panel-collapse collapse">
                                                <div class="accordion" id="accordion-button-template-button-1">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#button-template-button-1-menu-link" aria-expanded="true" aria-controls="button-template-button-1-menu-link">
                                                                <?php echo $this->lang->line('chatbot_link'); ?>
                                                            </button>
                                                        </div>

                                                        <div id="button-template-button-1-menu-link" class="collapse show" aria-labelledby="button-template-button-1-menu-link" data-parent="#accordion-button-template-button-1" data-type="link">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-link" name="link" placeholder="<?php echo $this->lang->line('chatbot_enter_button_url'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header" id="headingTwo">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#button-template-button-1-menu-suggestions" aria-expanded="false" aria-controls="button-template-button-1-menu-suggestions">
                                                                <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                                            </button>
                                                        </div>
                                                        <div id="button-template-button-1-menu-suggestions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-button-template-button-1" data-type="suggestions-group">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" data-target="option-2">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#button-template" href="#button-template-button-2">
                                                        <?php echo $this->lang->line('chatbot_button_2'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="button-template-button-2" class="panel-collapse collapse">
                                                <div class="accordion" id="accordion-button-template-button-2">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#button-template-button-2-menu-link" aria-expanded="true" aria-controls="button-template-button-2-menu-link">
                                                                <?php echo $this->lang->line('chatbot_link'); ?>
                                                            </button>
                                                        </div>

                                                        <div id="button-template-button-2-menu-link" class="collapse show" aria-labelledby="button-template-button-2-menu-link" data-parent="#accordion-button-template-button-2" data-type="link">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-link" name="link" placeholder="<?php echo $this->lang->line('chatbot_enter_button_url'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header" id="headingTwo">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#button-template-button-2-menu-suggestions" aria-expanded="false" aria-controls="button-template-button-2-menu-suggestions">
                                                                <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                                            </button>
                                                        </div>
                                                        <div id="button-template-button-2-menu-suggestions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-button-template-button-2" data-type="suggestions-group">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" data-target="option-3">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#button-template" href="#button-template-button-3">
                                                        <?php echo $this->lang->line('chatbot_button_3'); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="button-template-button-3" class="panel-collapse collapse">
                                                <div class="accordion" id="accordion-button-template-button-3">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#button-template-button-3-menu-link" aria-expanded="true" aria-controls="button-template-button-3-menu-link">
                                                                <?php echo $this->lang->line('chatbot_link'); ?>
                                                            </button>
                                                        </div>

                                                        <div id="button-template-button-3-menu-link" class="collapse show" aria-labelledby="button-template-button-3-menu-link" data-parent="#accordion-button-template-button-3" data-type="link">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-link" name="link" placeholder="<?php echo $this->lang->line('chatbot_enter_button_url'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header" id="headingTwo">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#button-template-button-3-menu-suggestions" aria-expanded="false" aria-controls="button-template-button-3-menu-suggestions">
                                                                <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                                            </button>
                                                        </div>
                                                        <div id="button-template-button-3-menu-suggestions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-button-template-button-3" data-type="suggestions-group">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control template-button-title" name="title" placeholder="<?php echo $this->lang->line('chatbot_enter_button_title'); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <fieldset>
                            <button type="submit" class="btn btn-primary">
                                <i class="lni-save"></i>
                                <?php echo $this->lang->line('save'); ?>
                            </button>
                        </fieldset>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <?php echo form_open_multipart('user/app/chatbot', array('class' => 'upim d-none', 'id' => 'upim', 'method' => 'post', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <input type="hidden" name="type" id="type" value="video">
                <input type="file" name="file[]" id="file" accept=".gif,.jpg,.jpeg,.png,.mp4,.avi" multiple>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Word list for JS !-->
<script language="javascript">
    var words = {
        no_suggestions_found: '<?php echo $this->lang->line('chatbot_no_suggestions_found'); ?>',
        generic_template: '<?php echo $this->lang->line('chatbot_generic_template'); ?>',
        media_template: '<?php echo $this->lang->line('chatbot_media_template'); ?>',
        drag_image_click_to_upload: '<?php echo $this->lang->line('chatbot_drag_image_click_to_upload'); ?>',
        template_title_required: '<?php echo $this->lang->line('chatbot_template_title_required'); ?>',
        template_subtitle_required: '<?php echo $this->lang->line('chatbot_template_subtitle_required'); ?>',
        template_url_required: '<?php echo $this->lang->line('chatbot_template_url_required'); ?>',
        template_requires_at_least_one_button: '<?php echo $this->lang->line('chatbot_template_requires_at_least_one_button'); ?>',
        please_enter_correct_data_for_your_button: '<?php echo $this->lang->line('chatbot_please_enter_correct_data_for_your_button'); ?>',
        please_consigure_buttons_correct_order: '<?php echo $this->lang->line('chatbot_please_consigure_buttons_correct_order'); ?>',
        templates_supports_only_images: '<?php echo $this->lang->line('chatbot_templates_supports_only_images'); ?>',
        please_upload_an_image: '<?php echo $this->lang->line('chatbot_please_upload_an_image'); ?>',
    };
</script>