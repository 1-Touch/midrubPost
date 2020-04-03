<section class="chatbot-page-reply" data-reply="<?php echo $reply_id; ?>">
    <div class="row">
        <div class="col-12">
            <div class="theme-box new-reply-top">
                <?php echo form_open('user/app/chatbot', array('class' => 'save-reply-form', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="row">
                    <div class="col-4 col-lg-8">
                        <textarea class="form-control reply-keywords" rows="3" placeholder="<?php echo $this->lang->line('chatbot_enter_the_keywords'); ?>" required><?php echo htmlspecialchars($keywords); ?></textarea>
                    </div>
                    <div class="col-8 col-lg-4 text-right">
                        <div class="btn-group" role="group">
                            <div class="dropdown show">
                                <button class="btn btn-secondary dropdown-toggle keywords-accuracy" data-id="<?php echo $accuracy; ?>" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo ($accuracy) ? $accuracy . '%' : '<i class="fas fa-percent"></i> ' . $this->lang->line('chatbot_accuracy'); ?>
                                </button>

                                <div class="dropdown-menu dropdown-menu-action keywords-accuracy-list" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" data-id="10" href="#">10%</a>
                                    <a class="dropdown-item" data-id="20" href="#">20%</a>
                                    <a class="dropdown-item" data-id="30" href="#">30%</a>
                                    <a class="dropdown-item" data-id="40" href="#">40%</a>
                                    <a class="dropdown-item" data-id="50" href="#">50%</a>
                                    <a class="dropdown-item" data-id="60" href="#">60%</a>
                                    <a class="dropdown-item" data-id="70" href="#">70%</a>
                                    <a class="dropdown-item" data-id="80" href="#">80%</a>
                                    <a class="dropdown-item" data-id="90" href="#">90%</a>
                                    <a class="dropdown-item" data-id="100" href="#">100%</a>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-secondary theme-background-blue chatbot-save-reply">
                                <i class="lni-save"></i>
                                <?php echo $this->lang->line('save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="replies-categories">
                <div class="row">
                    <div class="col-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <button class="btn btn-primary" type="button">
                                    <i class="far fa-bookmark"></i>
                                    <?php echo $this->lang->line('chatbot_uncategorized'); ?>
                                </button>
                                <?php
                                if (isset($categories)) {

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
            <div class="new-reply-body">
                <div class="row">
                    <div class="col-12 col-lg-8 mb-4">
                        <div class="theme-box reply-response">
                            <div class="row">
                                <div class="col-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3>
                                                <i class="lni-reply"></i>
                                                <?php echo $this->lang->line('chatbot_response'); ?>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p>
                                                <i class="lni-alarm"></i>
                                                <?php echo $this->lang->line('chatbot_text_reply_or_suggestions'); ?>
                                            </p>
                                            <div class="accordion" id="accordion">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link<?php echo ($type > 1) ? ' collapsed' : ''; ?>" type="button" data-toggle="collapse" data-target="#menu-text-reply" aria-expanded="true" aria-controls="menu-text-reply">
                                                            <?php echo $this->lang->line('chatbot_text_reply'); ?>
                                                        </button>
                                                    </div>

                                                    <div id="menu-text-reply" class="collapse<?php echo ($type < 2) ? ' show' : ''; ?>" aria-labelledby="menu-text-reply" data-parent="#accordion" data-type="text-reply">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control reply-text-message" rows="3" placeholder="<?php echo $this->lang->line('chatbot_enter_the_keywords'); ?>"><?php echo isset($response) ? $response : ''; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header" id="headingTwo">
                                                        <button class="btn btn-link<?php echo ($type < 2) ? ' collapsed' : ''; ?>" type="button" data-toggle="collapse" data-target="#menu-suggestions" aria-expanded="false" aria-controls="menu-suggestions">
                                                            <?php echo $this->lang->line('chatbot_suggestions'); ?>
                                                        </button>
                                                    </div>
                                                    <div id="menu-suggestions" class="collapse<?php echo ($type > 1) ? ' show' : ''; ?>" aria-labelledby="headingTwo" data-parent="#accordion" data-type="suggestions-group">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="dropdown dropdown-suggestions">
                                                                        <button class="btn btn-secondary dropdown-toggle chatbot-select-suggestions-group btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-id="<?php echo isset($group_id) ? $group_id : ''; ?>">
                                                                            <?php echo isset($group_name) ? $group_name : $this->lang->line('chatbot_select_suggestion'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu chatbot-suggestions-dropdown" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
                                                                            <div class="card">
                                                                                <div class="card-head">
                                                                                    <input type="text" class="chatbot-search-for-suggestions" placeholder="<?php echo $this->lang->line('chatbot_search_suggestions'); ?>">
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <ul class="list-group chatbot-suggestions-list">
                                                                                    </ul>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="theme-box reply-graph">
                            <div class="row">
                                <div class="col-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3>
                                                <i class="lni-stats-up"></i>
                                                <?php echo $this->lang->line('chatbot_active_last_month'); ?>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <canvas id="replies-stats-chart" style="width: 100%;" height="400"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="theme-box reply-users">
                            <div class="row">
                                <div class="col-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3>
                                                <i class="lni-users"></i>
                                                <?php echo $this->lang->line('chatbot_subscribers'); ?>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-group subscribers-list">

                                            </ul>
                                        </div>
                                        <div class="panel-footer">
                                            <nav>
                                                <ul class="pagination" data-type="subscribers">
                                                </ul>
                                            </nav>
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
</section>

<!-- Categories Manager -->
<div class="modal fade" id="categories-manager" tabindex="-1" role="dialog" aria-boostledby="categories-manager" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <?php echo $this->lang->line('chatbot_manage_categories'); ?>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-boost="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend><?php echo $this->lang->line('chatbot_new_category'); ?></legend>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php echo form_open('user/app/chatbot', array('class' => 'chatbot-create-category', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                                    <div class="input-group">
                                        <input type="text" class="form-control category-name" name="category-name" placeholder="<?php echo $this->lang->line('chatbot_enter_category_name'); ?>">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="lni-save"></i>
                                                <?php echo $this->lang->line('save'); ?>
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

<!-- Word list for JS !-->
<script language="javascript">
    var words = {
        please_enter_text_reply: '<?php echo $this->lang->line('chatbot_please_enter_text_reply'); ?>',
        please_select_suggestion_group: '<?php echo $this->lang->line('chatbot_please_select_suggestion_group'); ?>',
        please_select_at_least_reply: '<?php echo $this->lang->line('chatbot_please_select_at_least_reply'); ?>',
        file_too_large: '<?php echo $this->lang->line('chatbot_file_too_large'); ?>',
    };
</script>