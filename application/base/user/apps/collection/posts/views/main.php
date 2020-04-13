<section class="posts-page" data-up="<?php echo (get_option('upload_limit')) ? get_option('upload_limit') : '6'; ?>"
    data-mobile-installed="<?php echo (get_user_option('mobile_installed')) ? '1' : '0'; ?>">
    <div class="container-fluid mt-3" id="text-custom">
        <h1 class="header-t"><?php echo $this->lang->line('posts'); ?></h1>
    </div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <?php
            if (get_option('app_posts_enable_composer')) {
                ?>
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-composer" role="tab"
                aria-controls="nav-composer" aria-selected="true">
                <?php echo $this->lang->line('composer'); ?>
            </a>
            <?php
            }
            if (get_option('app_posts_enable_scheduled')) {
                ?>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#scheduled-posts" role="tab"
                aria-controls="scheduled-posts" aria-selected="false">
                <?php echo $this->lang->line('scheduled'); ?>
            </a>
            <?php
            }
            if (get_option('app_posts_enable_insights')) {
                ?>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-insights" role="tab"
                aria-controls="nav-insights" aria-selected="false">
                <?php echo $this->lang->line('insights'); ?>
            </a>
            <?php
            }
            if (get_option('app_posts_enable_history')) {
                ?>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-history" role="tab"
                aria-controls="nav-history" aria-selected="false">
                <?php echo $this->lang->line('history'); ?>
            </a>
            <?php
            }
            if (get_option('app_posts_rss_feeds')) {
                ?>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-rss" role="tab"
                aria-controls="nav-rss" aria-selected="false">
                <?php echo $this->lang->line('rss'); ?>
            </a>
            <?php
            }
            ?>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <?php
        if (get_option('app_posts_enable_composer')) {
            ?>
        <div class="tab-pane fade show active" id="nav-composer" role="tabpanel" aria-labelledby="nav-composer">
            <?php
                    if (posts_plan_limit($this->user_id)) {
                        ?>
            <div class="row">
                <div class="col-xl-12">
                    <div class="reached-plan-limit">
                        <div class="row">
                            <div class="col-xl-9">
                                <i class="icon-info"></i>
                                <?php echo $this->lang->line('reached_maximum_number_posts'); ?>
                            </div>
                            <div class="col-xl-3 text-right">
                                <?php
                                                if (!$this->session->userdata('member')) {
                                                    ?>
                                <a href="<?php echo site_url('user/plans') ?>" class="btn"><i class="icon-basket"></i>
                                    <?php echo $this->lang->line('our_plans'); ?></a>
                                <?php
                                                }
                                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    }
                    ?>
            <?php echo form_open('user/app/posts', ['class' => 'send-post', 'data-csrf' => $this->security->get_csrf_token_name()]) ?>
            <div class="row">
                <div class="col-xl-4">
                    <div class="col-xl-12 post-destionation theme-box">
                        <div class="row">
                            <div class="col-xl-12 input-group composer-accounts-search">
                                <div class="input-group-prepend">
                                    <i class="icon-magnifier"></i>
                                </div>
                                <?php if (get_user_option('settings_display_groups')) : ?>
                                <input type="text" class="form-control composer-search-for-groups"
                                    placeholder="<?php echo $this->lang->line('search_for_groups'); ?>">
                                <?php else : ?>
                                <input type="text" class="form-control composer-search-for-accounts"
                                    placeholder="<?php echo $this->lang->line('search_for_accounts'); ?>">
                                <?php endif; ?>
                                <button type="button" class="composer-cancel-search-for-accounts">
                                    <i class="icon-close"></i>
                                </button>
                                <button type="button" class="back-button btn-disabled">
                                    <span class="fc-icon fc-icon-left-single-arrow"></span>
                                </button>
                                <button type="button"
                                    class="next-button<?php echo ($total < 11) ? ' btn-disabled' : '" data-page="2'; ?>">
                                    <span class="fc-icon fc-icon-right-single-arrow"></span>
                                </button>
                                <button type="button" class="composer-manage-members" data-toggle="modal"
                                    data-target="#accounts-manager-popup">
                                    <i class="icon-user-follow"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (get_user_option('settings_display_groups')) {

                                echo '<div class="col-xl-12 composer-groups-list">'
                                    . '<ul>';

                                if ($groups_list) {

                                    foreach ($groups_list as $group) {
                                        ?>
                                        <li>
                                            <a href="#" data-id="<?php echo $group->list_id; ?>">
                                                <?php echo '<i class="icon-folder-alt"></i>'; ?>
                                                <?php echo $group->name; ?>
                                                <i class="icon-check"></i>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                        
                                } else {
                                    echo '<li class="no-groups-found">' . $this->lang->line('no_groups_found') . '</li>';
                                }

                                echo '</ul>'
                                    . '</div>';
                            } else {

                                echo '<div class="col-xl-12 composer-accounts-list">'
                                    . '<ul>';

                                if ($accounts_list) {

                                    foreach ($accounts_list as $account) {
                                        ?>
                            <li>
                                <a href="#" data-id="<?php echo $account['network_id']; ?>"
                                    data-net="<?php echo $account['net_id']; ?>"
                                    data-network="<?php echo $account['network_name']; ?>"
                                    data-category="<?php echo in_array('categories', $account['network_info']['types']) ? 'true' : 'value'; ?>">
                                    <?php echo str_replace(' class', ' style="color: ' . $account['network_info']['color'] . '" class', $account['network_info']['icon']); ?>
                                    <?php echo $account['user_name']; ?>
                                    <span>
                                        <i class="icon-user"></i>
                                        <?php echo ucwords(str_replace('_', ' ', $account['network_name'])); ?>
                                    </span>
                                    <i class="icon-check"></i>
                                </a>
                            </li>
                            <?php
                                    }
                                } else {

                                    echo '<li class="no-accounts-found">' . $this->lang->line('no_accounts_found') . '</li>';
                                }

                                echo '</ul>'
                                    . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="col-xl-12 post-composer theme-box">
                        <div class="row">
                            <div class="col-xl-12 post-body">
                                <div class="col-xl-12 composer-title">
                                    <input type="text"
                                        placeholder="<?php echo $this->lang->line('enter_post_title'); ?>">
                                </div>
                                <div class="col-xl-12 composer-url">
                                    <input type="text" placeholder="<?php echo $this->lang->line('posts_post_url'); ?>">
                                </div>
                                <div class="row post-composer-editor">
                                    <div class="col-xl-12 composer">
                                        <textarea class="new-post"
                                            placeholder="<?php echo $this->lang->line('share_what_new'); ?>"></textarea>
                                    </div>
                                </div>
                                <div class="row post-composer-buttons">
                                    <div class="col-xl-12">
                                        <button type="button" class="btn" data-toggle="modal"
                                            data-target="#file-upload-box">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                        <button type="button" class="btn show-title">
                                            <i class="fas fa-text-width"></i>
                                        </button>
                                        <?php
                                                if (get_user_option('settings_posts_url_import')) {
                                                    ?>
                                        <button type="button" class="btn show-url-input">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <?php
                                                }
                                                ?>
                                        <button type="button" class="btn" data-toggle="modal"
                                            data-target="#saved-posts">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <?php
                                                if ( get_user_option('settings_hashtags_suggestion') ) {
                                                    ?>
                                        <button type="button" class="btn" data-toggle="modal"
                                            data-target="#hashtags-sugestion">
                                            <i class="fas fa-hashtag"></i>
                                        </button>
                                        <?php
                                        }
                                        if ( get_option('app_posts_enable_designbold_button') ) {
                                            ?>
                                                <div class="db-btn-design-me" data-db-width="800" data-db-height="600" data-db-title=" "></div>
                                            <?php
                                        }
                                        
                                        if (get_user_option('settings_character_count')) {
                                            echo '<div class="numchar">0</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 post-footer">
                                <div class="multimedia-gallery">
                                    <ul>
                                    </ul>
                                    <a href="#" class="load-more-medias"
                                        data-page="1"><?php echo $this->lang->line('load_more'); ?></a>
                                    <p class="no-medias-found">
                                        <?php echo $this->lang->line('no_photos_videos_uploaded'); ?></p>
                                </div>
                                <div class="multimedia-gallery-selected-medias">
                                    <div class="row">
                                        <div class="col-xl-4 col-4">
                                            <p></p>
                                        </div>
                                        <div class="col-xl-8 col-8 text-right">
                                            <button type="button" class="btn btn-delete-selected-photos">
                                                <?php echo $this->lang->line('delete_all'); ?>
                                            </button>
                                            <button type="button" class="btn btn-add-selected-photos">
                                                <?php echo $this->lang->line('add_to_post'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 boost-control theme-box">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-9">
                                        <h5>
                                            <i class="fas fa-project-diagram"></i>
                                        </h5>
                                    </div>
                                    <div class="col-3 text-right">
                                        <a class="btn btn-secondary btn-md cancel-post-boosting">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <i style="color: #4065b3;" class="fab fa-facebook"></i>
                                    </div>
                                    <div class="col-7 clean">
                                        <h3></h3>
                                        <p><i class="icon-user"></i> Facebook Pages</p>
                                    </div>
                                    <div class="col-2 text-right"><i class="icon-check"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 buttons-control theme-box">
                        <?php
                                if (get_option('app_facebook_ads_enable_posts_boosting') && get_option('app_facebook_ads_enable') && plan_feature('app_facebook_ads')) {
                                    ?>
                        <div class="pull-left">
                            <a class="btn btn-secondary btn-md" data-toggle="modal" data-target="#boost-post-on">
                                <i class="icon-like"></i>
                                <?php echo $this->lang->line('boost_it'); ?>
                            </a>
                        </div>
                        <?php
                                }
                                ?>
                        <input type="text" class="datetime">
                        <div class="btn-group dropup">
                            <button type="submit" class="btn btn-success"><i class="icon-share-alt"></i>
                                <?php echo $this->lang->line('share_now'); ?></button>
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#"
                                        class="open-midrub-planner"><?php echo $this->lang->line('schedule'); ?></a>
                                </li>
                                <li><a href="#"
                                        class="composer-draft-post"><?php echo $this->lang->line('draft_it'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="col-xl-12 post-preview theme-box">
                        <div class="row">
                            <div class="col-xl-12 post-preview-header">
                                <h3>
                                    <i class="icon-share-alt"></i>
                                    <?php echo $this->lang->line('post_preview'); ?>
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary btn-md dropdown-toggle" href="#" data-slug="default"
                                            role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-screen-desktop"></i>
                                            <?php echo $this->lang->line('posts_default'); ?>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-social-preview"
                                            aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" data-slug="default" href="#">
                                                <i class="icon-screen-desktop"></i>
                                                <?php echo $this->lang->line('posts_default'); ?>
                                            </a>
                                            <?php
                                                    if ($preview_socials) {

                                                        foreach ($preview_socials as $preview_social) {

                                                            echo '<a class="dropdown-item" data-slug="' . $preview_social['slug'] . '" href="#">'
                                                                . $preview_social['preview_icon']
                                                                . $preview_social['name']
                                                                . '</a>';
                                                        }
                                                    }
                                                    ?>
                                        </div>
                                    </div>
                                </h3>
                            </div>
                            <div class="col-xl-12 post-preview-placeholder">
                                <div class="row">
                                    <div class="col-xl-12 post-preview-social">
                                        <div class="row">
                                            <div class="col-xl-12 post-preview-title">
                                                <div class="row">
                                                    <div class="col-xl-8"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 post-preview-body">
                                                <div class="row">
                                                    <div class="col-xl-11"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-11"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-11"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-7"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 post-preview-medias">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class="col-xl-12 post-preview-footer">
                                                <ul></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <?php
        }
        if (get_option('app_posts_enable_scheduled')) {
            ?>
        <div class="tab-pane fade" id="scheduled-posts" role="tabpanel" aria-labelledby="scheduled-posts">
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-12 p-3 theme-box">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        if (get_option('app_posts_enable_insights')) {
            ?>
        <div class="tab-pane fade" id="nav-insights" role="tabpanel" aria-labelledby="nav-insights">
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-12 clean">
                        <ul class="nav nav-pills">
                            <li class="active">
                                <a href="#insights-posts" class="active show"
                                    data-toggle="tab"><?php echo $this->lang->line('posts'); ?></a>
                            </li>
                            <li>
                                <a href="#insights-accounts"
                                    data-toggle="tab"><?php echo $this->lang->line('accounts'); ?></a>
                            </li>
                        </ul>
                        <div class="tab-content clearfix">
                            <div class="tab-pane active" id="insights-posts">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="row">
                                            <div class="col-xl-12 input-group insights-posts-search">
                                                <div class="input-group-prepend">
                                                    <i class="icon-magnifier"></i>
                                                </div>
                                                <input type="text" class="form-control insights-search-for-posts"
                                                    placeholder="<?php echo $this->lang->line('search_for_posts'); ?>">
                                                <button type="button" class="insights-cancel-search-for-posts">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 insights-posts-results">
                                            </div>
                                        </div>
                                        <nav>
                                            <ul class="pagination" data-type="insights-posts">
                                            </ul>
                                        </nav>
                                    </div>
                                    <div class="col-xl-5">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="panel theme-box no-selected-post panel-primary">
                                                    <div class="panel-no-selected-post">
                                                        <p class="no-post-selected">
                                                            <?php echo $this->lang->line('no_post_selected'); ?></p>
                                                    </div>
                                                    <div class="panel-heading insights-post-header" id="accordion">
                                                        <h3>
                                                            <img src="">
                                                            <a href="#" class="insights-post-content-username"></a>
                                                            <span></span>
                                                            <div class="dropdown show">
                                                                <a class="dropdown-toggle" href="#" role="button"
                                                                    id="dropdownMenuLink" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <i class="icon-arrow-down"></i>
                                                                </a>

                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuLink">
                                                                    <a class="dropdown-item insights-post-delete-post"
                                                                        href="#"><?php echo $this->lang->line('delete_post'); ?></a>
                                                                </div>
                                                            </div>
                                                        </h3>
                                                    </div>
                                                    <div class="panel-body insights-post-content">
                                                    </div>
                                                    <div class="panel-footer insights-post-footer">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="tab-content" id="myTabContent">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading" id="accordion">
                                                        <h3>
                                                            <i class="icon-graph"></i>
                                                            <?php echo $this->lang->line('post_insights'); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="panel-body">
                                                        <canvas id="insights-posts-graph" width="600"
                                                            height="400"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="insights-accounts">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="row">
                                            <div class="col-xl-10 col-sm-10 col-9 input-group insights-posts-search">
                                                <div class="input-group-prepend">
                                                    <i class="icon-magnifier"></i>
                                                </div>
                                                <input type="text" class="form-control insights-search-for-accounts"
                                                    placeholder="<?php echo $this->lang->line('search_for_accounts'); ?>">
                                                <button type="button" class="insights-cancel-search-for-accounts">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </div>
                                            <div class="col-xl-2 col-sm-2 col-3">
                                                <button type="button" class="composer-manage-members"
                                                    data-toggle="modal" data-target="#accounts-manager-popup"><i
                                                        class="icon-user-follow"></i></button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 insights-accounts-results">
                                                <ul class="insights-accounts">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <nav>
                                                    <ul class="pagination" data-type="insights-accounts">
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-5">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="panel theme-box no-selected-post panel-primary">
                                                    <div class="panel-no-selected-post">
                                                        <p class="no-post-selected">
                                                            <?php echo $this->lang->line('no_accounts_selected'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="panel theme-box panel-primary">
                                                    <div class="panel-heading" id="accordion">
                                                        <h3>
                                                            <i class="icon-graph"></i>
                                                            <?php echo $this->lang->line('insights'); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="panel-body">
                                                        <canvas id="insights-accounts-graph" width="600"
                                                            height="500"></canvas>
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
        <?php
        }
        if (get_option('app_posts_enable_history')) {
            ?>
        <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history">
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-12 clean">
                        <div class="row">
                            <div class="col-xl-3">
                                <div class="row">
                                    <div class="col-xl-10 col-sm-10 col-9 input-group history-posts-search">
                                        <div class="input-group-prepend">
                                            <i class="icon-magnifier"></i>
                                        </div>
                                        <input type="text" class="form-control history-search-for-posts"
                                            placeholder="<?php echo $this->lang->line('search_for_posts'); ?>">
                                        <button type="button" class="history-cancel-search-for-posts">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                    <div class="col-xl-2 col-sm-2 col-3">
                                        <button type="button" class="history-generate-reports" data-toggle="modal"
                                            data-target="#history-generate-reports">
                                            <i class="icon-pie-chart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 input-group history-posts-results">
                                    </div>
                                </div>
                                <nav>
                                    <ul class="pagination" data-type="history-posts">
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-xl-5">
                                <div class="panel theme-box panel-primary">
                                    <div class="panel-heading" id="accordion">
                                        <div class="row">
                                            <div class="col-xl-9">
                                                <h3>
                                                    <i class="icon-info"></i>
                                                    <?php echo $this->lang->line('post_content'); ?>
                                                </h3>
                                            </div>
                                            <div class="col-xl-3 text-right">
                                                <div class="dropdown show">
                                                    <a class="btn btn-post-actions btn-md dropdown-toggle" href="#"
                                                        data-slug="default" role="button" id="dropdown-post-content"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="icon-arrow-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdown-post-content">
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#posts-edit-post">
                                                            <i class="icon-note"></i>
                                                            <?php echo $this->lang->line('posts_edit_content'); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body history-post-content">
                                        <p class="no-post-selected">
                                            <?php echo $this->lang->line('no_post_selected'); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-xl-12 theme-box history-boost-control">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>
                                                        <i class="fas fa-project-diagram"></i>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-2">
                                                    <i style="color: #4065b3;" class="fab fa-facebook"></i>
                                                </div>
                                                <div class="col-7 clean">
                                                    <h3></h3>
                                                    <p><i class="icon-user"></i> Facebook Pages</p>
                                                </div>
                                                <div class="col-2 text-right"><i class="icon-check"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="panel theme-box panel-primary">
                                    <div class="panel-heading" id="accordion">
                                        <div class="row">
                                            <div class="col-xl-9">
                                                <h3>
                                                    <i class="icon-chart"></i>
                                                    <?php echo $this->lang->line('publish_status'); ?>
                                                </h3>
                                            </div>
                                            <div class="col-xl-3 text-right">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body history-profiles-list">
                                        <p class="no-post-selected"><?php echo $this->lang->line('no_post_selected'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        if (get_option('app_posts_rss_feeds')) {
            ?>
        <div class="tab-pane fade" id="nav-rss" role="tabpanel" aria-labelledby="nav-rss">
            <?php
                    if (rss_plan_limit($this->user_id)) {
                        ?>
            <div class="row">
                <div class="col-xl-12">
                    <div class="reached-plan-limit">
                        <div class="row">
                            <div class="col-xl-9">
                                <i class="icon-info"></i>
                                <?php echo $this->lang->line('reached_maximum_number_rss_feeds'); ?>
                            </div>
                            <div class="col-xl-3 text-right">
                                <?php
                                                if (!$this->session->userdata('member')) {
                                                    ?>
                                <a href="<?php echo site_url('user/plans') ?>" class="btn"><i class="icon-basket"></i>
                                    <?php echo $this->lang->line('our_plans'); ?></a>
                                <?php
                                                }
                                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    }
                    ?>
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-12">
                        <div class="panel theme-box panel-primary">
                            <div class="panel-heading" id="accordion">
                                <div class="checkbox-option-select">
                                    <input id="rss-select-all-feeds" name="rss-select-all-feeds" type="checkbox">
                                    <label for="rss-select-all-feeds"></label>
                                </div>
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-magic-wand"></i> <?php echo $this->lang->line('actions'); ?>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-action" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" data-id="1" href="#"><i class="fas fa-circle"></i>
                                            <?php echo $this->lang->line('enable'); ?></a>
                                        <a class="dropdown-item" data-id="2" href="#"><i class="far fa-circle"></i>
                                            <?php echo $this->lang->line('disable'); ?></a>
                                        <a class="dropdown-item" data-id="3" href="#"><i class="icon-trash"></i>
                                            <?php echo $this->lang->line('delete'); ?></a>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">
                                            <i class="icon-magnifier"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control search-for-rss-feeds"
                                        placeholder="<?php echo $this->lang->line('search_for_rss_feeds'); ?>"
                                        aria-label="search-for-rss-feeds">
                                    <div class="input-group-append">
                                        <button type="button" class="rss-cancel-search-for-feeds">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#rss-feeds-new-rss"><i class="icon-feed"></i>
                                    <?php echo $this->lang->line('add_rss_feed'); ?></button>
                            </div>
                            <div class="panel-body">
                                <ul class="rss-all-feeds">
                                </ul>
                            </div>
                            <div class="panel-footer">
                                <nav>
                                    <ul class="pagination" data-type="rss-feeds">
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="composer-category-picker" tabindex="-1" role="dialog"
    aria-labelledby="composer-category-picker" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('posts_select_a_category'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <select class="form-control" id="selnet">
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-right">
                <div>
                    <button type="button" class="btn btn-success categories-select"
                        data-dismiss="modal"><?php echo $this->lang->line('posts_add_category'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="accounts-manager-popup" tabindex="-1" role="dialog" aria-labelledby="accounts-manager-popup"
    aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-accounts-manager-tab" data-toggle="tab"
                            href="#nav-accounts-manager" role="tab" aria-controls="nav-accounts-manager"
                            aria-selected="true">
                            <?php echo $this->lang->line('accounts'); ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-groups-manager-tab" data-toggle="tab"
                            href="#nav-groups-manager" role="tab" aria-controls="nav-groups-manager"
                            aria-selected="false">
                            <?php echo $this->lang->line('groups'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-accounts-manager" role="tabpanel"
                        aria-labelledby="nav-accounts-manager">
                    </div>
                    <div class="tab-pane fade" id="nav-groups-manager" role="tabpanel"
                        aria-labelledby="nav-groups-manager">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ( get_user_option('settings_hashtags_suggestion') ) {
    ?>
<!-- Modal -->
<div class="modal fade" id="hashtags-sugestion" tabindex="-1" role="dialog" aria-labelledby="hashtags-sugestion"
    aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-twitter-hashtags-tab" data-toggle="tab"
                            href="#nav-twitter-hashtags" role="tab" aria-controls="nav-twitter-hashtags"
                            aria-selected="true">
                            <i class="icon-social-twitter"></i>
                            <?php echo $this->lang->line('twitter_hashtags'); ?>
                        </a>
                        <?php
                                if (plan_feature('instagram_insights')) {
                                    ?>
                        <a class="nav-item nav-link" id="nav-instagram-hashtags-tab" data-toggle="tab"
                            href="#nav-instagram-hashtags" role="tab" aria-controls="nav-instagram-hashtags"
                            aria-selected="false">
                            <i class="icon-social-instagram"></i>
                            <?php echo $this->lang->line('instagram_hashtags'); ?>
                        </a>
                        <?php
                                }
                                ?>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-twitter-hashtags" role="tabpanel"
                        aria-labelledby="nav-twitter-hashtags">
                        <?php echo form_open('user/app/posts', array('class' => 'hashtags-search-form', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="row hashtags-search-form">
                            <div class="col-xl-7 col-sm-7 col-7">
                                <div class="input-group hashtags-enter-hashtag">
                                    <div class="input-group-prepend">
                                        <i class="fas fa-hashtag"></i>
                                    </div>
                                    <input type="text" class="form-control hashtags-enter-word"
                                        placeholder="<?php echo $this->lang->line('enter_a_word'); ?>" required>
                                </div>
                            </div>
                            <div class="col-xl-5 col-sm-5 col-5">
                                <button type="submit" class="hashtags-hashtag-find-btn">
                                    <i class="fas fa-binoculars"></i>
                                    <?php echo $this->lang->line('search_hashtags'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="row hashtags-suggestion-list">
                            <div class="col-xl-12 hashtags-suggestion-single">
                                <h6><?php echo $this->lang->line('please_enter_any_word'); ?></h6>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="modal-footer">
                            <div>
                                <button class="btn add-hashtags-to-posts"><i
                                        class="icon-plus"></i><?php echo $this->lang->line('add_to_post'); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php
                            if (plan_feature('instagram_insights')) {
                                ?>
                    <div class="tab-pane fade" id="nav-instagram-hashtags" role="tabpanel"
                        aria-labelledby="nav-instagram-hashtags">
                        <?php echo form_open('user/app/posts', array('class' => 'hashtags-search-form', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="row hashtags-search-form">
                            <div class="col-xl-7 col-sm-7 col-7">
                                <div class="input-group hashtags-enter-hashtag">
                                    <div class="input-group-prepend">
                                        <i class="fas fa-hashtag"></i>
                                    </div>
                                    <input type="text" class="form-control hashtags-enter-word"
                                        placeholder="<?php echo $this->lang->line('enter_a_hashtag'); ?>" required>
                                </div>
                            </div>
                            <div class="col-xl-5 col-sm-5 col-5">
                                <button type="submit" class="hashtags-hashtag-find-btn">
                                    <i class="fas fa-binoculars"></i>
                                    <?php echo $this->lang->line('search_hashtags'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="row hashtags-suggestion-list">
                            <div class="col-xl-12 hashtags-suggestion-single">
                                <h6><?php echo $this->lang->line('please_enter_any_word'); ?></h6>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="modal-footer">
                            <div>
                                <button class="btn add-hashtags-to-posts"><i
                                        class="icon-plus"></i><?php echo $this->lang->line('add_to_post'); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php
                            }
                            ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<?php
if (get_option('app_facebook_ads_enable_posts_boosting') && get_option('app_facebook_ads_enable') && plan_feature('app_facebook_ads')) {
    ?>
<!-- Modal -->
<div class="modal fade" id="boost-post-on" tabindex="-1" role="dialog" aria-labelledby="boost-post-on-modal"
    aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-ad-boosts-tab" data-toggle="tab"
                            href="#nav-ad-boosts" role="tab" aria-controls="nav-ad-boosts" aria-selected="true">
                            <i class="fas fa-project-diagram"></i>
                            <?php echo $this->lang->line('ad_boosts'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-8">
                        <ul>
                        </ul>
                    </div>
                    <div class="col-xl-4">
                        <div class="col-xl-12">
                            <?php echo $this->lang->line('boost_instructions'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <nav>
                    <ul class="pagination" data-type="ad-boosts">
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<!-- Modal -->
<div class="modal fade" id="history-generate-reports" tabindex="-1" role="dialog"
    aria-labelledby="history-generate-reports" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-publish-reports-tab" data-toggle="tab"
                            href="#nav-publish-reports" role="tab" aria-controls="nav-publish-reports"
                            aria-selected="true">
                            <i class="icon-pie-chart"></i>
                            <?php echo $this->lang->line('publish_report'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-publish-reports" role="tabpanel"
                        aria-labelledby="nav-publish-reports">
                        <?php echo form_open('user/app/posts', array('class' => 'posts-generate-report', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="row">
                            <div class="col-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md order-reports-by-time dropdown-toggle"
                                        data-time="3" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo $this->lang->line('last_30_days'); ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <div class="card">
                                            <div class="card-body">
                                                <ul class="list-group history-reports-by-time">
                                                    <li class="list-group-item">
                                                        <a href="#" data-time="1">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            <?php echo $this->lang->line('today'); ?>
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <a href="#" data-time="2">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            <?php echo $this->lang->line('last_7_days'); ?>
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <a href="#" data-time="3">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            <?php echo $this->lang->line('last_30_days'); ?>
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <a href="#" data-time="4">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            <?php echo $this->lang->line('last_90_days'); ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                &nbsp;
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-default btn-show-reports">
                                    <i class="icon-refresh"></i>
                                    <?php echo $this->lang->line('show_report'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col"><?php echo $this->lang->line('date'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('published_posts'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('accounts'); ?></th>
                                                <th scope="col"><?php echo $this->lang->line('errors'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4">
                                                    <p>
                                                        <?php echo $this->lang->line('no_posts_found'); ?>
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rss-feeds-new-rss" tabindex="-1" role="dialog" aria-labelledby="rss-feeds-new-rss"
    aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-accounts-manager-tab" data-toggle="tab"
                            href="#nav-new-rss" role="tab" aria-controls="nav-new-rss" aria-selected="true">
                            <?php echo $this->lang->line('new_rss'); ?>
                        </a>
                        <?php
                        if (get_option('app_posts_enable_faq')) {
                            ?>
                        <a class="nav-item nav-link" id="nav-groups-manager-tab" data-toggle="tab" href="#nav-rss-faq"
                            role="tab" aria-controls="nav-rss-faq" aria-selected="false">
                            <?php echo $this->lang->line('faq'); ?>
                        </a>
                        <?php
                        }
                        ?>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-new-rss" role="tabpanel"
                        aria-labelledby="nav-new-rss">
                        <?php echo form_open('user/app/posts', array('class' => 'register-new-rss-feed', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="row rss-feeds-add-rss-form">
                            <div class="col-xl-7 col-sm-7 col-7">
                                <div class="input-group rss-feeds-enter-rss">
                                    <div class="input-group-prepend">
                                        <i class="icon-feed"></i>
                                    </div>
                                    <input type="text" class="form-control rss-feeds-enter-rss-url"
                                        placeholder="<?php echo $this->lang->line('enter_rss_url'); ?>" required>
                                </div>
                            </div>
                            <div class="col-xl-5 col-sm-5 col-5">
                                <button type="submit" class="rss-feeds-save-rss">
                                    <i class="far fa-save"></i> <?php echo $this->lang->line('save_rss_feed'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="row rss-feeds-rss-content">
                            <div class="col-xl-12 rss-feeds-rss-content-single">
                                <h6><?php echo $this->lang->line('please_enter_rss_feed_url'); ?></h6>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php
                    if (get_option('app_posts_enable_faq')) {
                        ?>
                    <div class="tab-pane fade" id="nav-rss-faq" role="tabpanel" aria-labelledby="nav-rss-faq">
                        <div class="row clean">
                            <div class="col-xl-12 rss-feeds-faq">
                                <?php echo $this->lang->line('add_rss_faq'); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="file-upload-box" tabindex="-1" role="dialog" aria-labelledby="file-upload-box"
    aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('file_upload'); ?> <span>( <span
                            class="user-total-storage">
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
                    <div class="col-xl-12">
                        <?php
                        if (get_option('app_posts_enable_url_download')) {
                            ?>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-upload-tab" data-toggle="pill" href="#pills-upload"
                                    role="tab" aria-controls="pills-upload" aria-selected="true">
                                    <i class="icon-cloud-upload"></i>
                                    <?php
                                            echo $this->lang->line('upload');
                                            ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-import-tab" data-toggle="pill" href="#pills-import"
                                    role="tab" aria-controls="pills-import" aria-selected="false">
                                    <i class="icon-link"></i>
                                    <?php
                                            echo $this->lang->line('import');
                                            ?>
                                </a>
                            </li>
                        </ul>
                        <?php
                        }
                        ?>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-upload" role="tabpanel"
                                aria-labelledby="pills-upload-tab">
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
                            if (get_option('app_posts_enable_url_download')) {
                                ?>
                            <div class="tab-pane fade" id="pills-import" role="tabpanel"
                                aria-labelledby="pills-import-tab">
                                <div class="import-images-from-url">
                                    <?php echo form_open('user/app/posts', array('class' => 'download-images-from-url', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <textarea class="imported-urls"
                                                placeholder="<?php echo $this->lang->line('enter_urls_one_per_line'); ?>"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <button type="submit" class="btn btn-download-photos"><i
                                                    class="icon-cloud-download"></i>
                                                <?php echo $this->lang->line('download'); ?></button>
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
                <div class="row">
                    <div class="col-xl-12">
                        <ul class="file-uploaded-box-files">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div>
                    <?php

                    if (get_option('app_posts_enable_dropbox')) {
                        echo '<button class="btn dropbox-picker pull-left" id="OpenDropboxFilePicker"><i class="icon-social-dropbox"></i></button>';
                    }

                    if (get_option('app_posts_enable_pixabay')) {
                        echo '<button class="btn pixabay-drive-picker" id="auth"><i class="fab fa-wpexplorer"></i></button>';
                    }

                    ?>
                    <button class="btn select-all-uploaded-media"><i
                            class="icon-plus"></i><?php echo $this->lang->line('add_to_post'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="saved-posts" tabindex="-1" role="dialog" aria-labelledby="saved-posts" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered history-saved-posts" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('posts'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">
                            <i class="icon-magnifier"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control composer-search-for-saved-posts"
                        placeholder="<?php echo $this->lang->line('search_for_posts'); ?>"
                        aria-label="<?php echo $this->lang->line('search_for_posts'); ?>"
                        aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button type="button" class="composer-cancel-search-for-posts">
                            <i class="icon-close"></i>
                        </button>
                    </div>
                </div>
                <ul class="list-group all-saved-posts">
                </ul>
            </div>
            <div class="modal-footer">
                <nav aria-label="Page navigation example">
                    <ul class="pagination" data-type="saved-posts">
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php
if (get_option('app_posts_enable_scheduled')) {
    ?>
<!-- Planner Scheduled Modal -->
<div class="modal fade" id="planner-posts-scheduled-modal" tabindex="-1" role="dialog"
    aria-labelledby="planner-posts-scheduled-modal" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('post_preview'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 scheduler-preview-post-content"></div>
                    <div class="col-lg-6 scheduler-preview-profiles-list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Schedule Modal -->
<div class="modal fade" id="planner-quick-schedule-modal" tabindex="-1" role="dialog"
    aria-labelledby="planner-quick-schedule-modal" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('quick_schedule'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('user/app/posts', array('class' => 'schedule-post', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="col-xl-12 post-destionation">
                            <div class="row">
                                <div class="col-xl-12 input-group quick-scheduler-accounts-search">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <?php
                                            if (get_user_option('settings_display_groups')) {

                                                ?>
                                    <input type="text" class="form-control quick-scheduler-search-for-groups"
                                        placeholder="<?php echo $this->lang->line('search_for_groups'); ?> ">
                                    <?php
                                            } else {
                                                ?>
                                    <input type="text" class="form-control quick-scheduler-search-for-accounts"
                                        placeholder="<?php echo $this->lang->line('search_for_accounts'); ?>">
                                    <?php
                                            }
                                            ?>
                                    <button type="button" class="quick-scheduler-cancel-search-for-accounts">
                                        <i class="icon-close"></i>
                                    </button>
                                    <button type="button" class="back-button btn-disabled">
                                        <span class="fc-icon fc-icon-left-single-arrow"></span>
                                    </button>
                                    <button type="button"
                                        class="next-button<?php echo ($total < 11) ? ' btn-disabled' : '" data-page="2'; ?>">
                                        <span class="fc-icon fc-icon-right-single-arrow"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                        if (get_user_option('settings_display_groups')) {
                                            ?>
                                <div class="col-xl-12 quick-scheduler-groups-list">
                                    <ul>
                                        <?php echo composer_load_groups($groups_list); ?>
                                    </ul>
                                </div>
                                <?php
                                        } else {
                                            ?>
                                <div class="col-xl-12 quick-scheduler-accounts-list">
                                    <ul>
                                        <?php echo composer_load_accounts($accounts_list); ?>
                                    </ul>
                                </div>
                                <?php
                                        }
                                        ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <input type="text" class="quick-scheduler-title"
                            placeholder="<?php echo $this->lang->line('enter_post_title'); ?>">
                        <textarea class="quick-new-post"
                            placeholder="<?php echo $this->lang->line('share_what_new'); ?>"></textarea>
                        <div class="post-scheduler-buttons">
                            <div class="col-xl-12">
                                <button type="button" class="btn show-title">
                                    <i class="fas fa-text-width"></i>
                                </button>
                                <?php echo get_user_option('settings_character_count') ? '<div class="numchar">0</div>' : ''; ?>
                            </div>
                        </div>
                        <div class="multimedia-gallery-quick-schedule">
                            <ul></ul>
                            <a href="#" class="multimedia-gallery-quick-schedule-load-more-medias" data-page="1">
                                <?php echo $this->lang->line('load_more'); ?>
                            </a>
                            <p class="no-medias-found" style="display: none;">
                                <?php echo $this->lang->line('no_photos_videos_uploaded'); ?>
                            </p>
                        </div>
                        <div class="quick-scheduler-selected-accounts">
                            <ul>
                            </ul>
                        </div>
                        <div class="scheduler-status-actions">
                            <div class="row">
                                <div class="col-xl-8">
                                    <input type="text" class="scheduler-quick-date">
                                    <?php echo scheduler_time(); ?>
                                </div>
                                <div class="col-xl-4">
                                    <button type="submit" class="btn btn-schedule-post">
                                        <i class="icon-share-alt"></i>
                                        <?php echo $this->lang->line('schedule'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<!-- Reply Comments Modal -->
<div class="modal fade" id="insights-reply-comments" tabindex="-1" role="dialog"
    aria-labelledby="insights-reply-comments" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('reply'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="post" class="insights-posts-reactions-post-reply">
                            <div class="input-group">
                                <textarea class="form-control input-sm reactions-msg"
                                    placeholder="<?php echo $this->lang->line('enter_reply'); ?>"></textarea>
                                <span class="input-group-btn">
                                    <button class="btn btn-warning btn-sm" type="submit" id="btn-chat">
                                        <i class="icon-cursor"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="posts-edit-post" tabindex="-1" role="dialog" aria-labelledby="posts-edit-post"
    aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/posts', array('class' => 'posts-edit-post-form', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line('posts_edit_post'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="posts-edit-post-form-input">
                            <input type="text" class="posts-edit-post-title"
                                placeholder="<?php echo $this->lang->line('enter_post_title'); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="posts-edit-post-form-input">
                            <input type="text" class="posts-edit-post-url"
                                placeholder="<?php echo $this->lang->line('posts_enter_post_url'); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="posts-edit-post-form-input">
                            <textarea placeholder="<?php echo $this->lang->line('posts_enter_post_content'); ?>"
                                class="posts-edit-post-body"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="posts-edit-post-form-input">
                            <div class="post-preview-medias">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="posts-edit-post-form-input clean">
                            <div class="posts-edit-media-head">
                                <h3><?php echo $this->lang->line('posts_media_files'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="posts-edit-media-area">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xl-8">
                    <input type="text" class="post-edit-date">
                    <?php echo scheduler_time(); ?>
                </div>
                <div class="col-xl-4">
                    <div>
                        <button class="btn posts-post-update" type="submit">
                            <i class="far fa-save"></i>
                            <?php echo $this->lang->line('posts_save'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!--Midrub Planner !-->
<div class="midrub-planner">
    <div class="row">
        <div class="col-xl-12">
            <table class="midrub-calendar iso">
                <thead>
                    <tr>
                        <th class="text-center"><a href="#" class="go-back"><i class="icon-arrow-left"></i></a></th>
                        <th colspan="5" class="text-center year-month"></th>
                        <th class="text-center"><a href="#" class="go-next"><i class="icon-arrow-right"></i></a></th>
                    </tr>
                </thead>
                <tbody class="calendar-dates">
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 text-center">
            <?php echo scheduler_time(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 text-center">
            <button type="btn" class="btn composer-schedule-post">
                <?php echo $this->lang->line('schedule'); ?>
            </button>
        </div>
    </div>
</div>

<!--Upload media form !-->
<?php
$attributes = array('class' => 'upim d-none', 'id' => 'upim', 'method' => 'post', 'data-csrf' => $this->security->get_csrf_token_name());
echo form_open_multipart('user/app/posts', $attributes);
?>
<input type="hidden" name="type" id="type" value="video">
<input type="file" name="file[]" id="file" accept=".gif,.jpg,.jpeg,.png,.mp4,.avi" multiple>
<?php echo form_close(); ?>

<?php if ( get_option('app_post_designbold_api_id') ) { ?>
<!-- DesignBold Button !-->
<script>
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "https://sdk.designbold.com/button.js#app_id=<?php echo get_option('app_post_designbold_api_id'); ?>";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'db-js-sdk'));

window.DBSDK_Cfg = {
    export_mode: ['download'],
    export_file_type: 'png',
    export_callback: function(resultUrl, exportTarget, options) {

        var xhr = new XMLHttpRequest();
        xhr.open('GET', resultUrl, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {

            if (this.status == 200) {

                // Get the Blob
                let blob = this.response;

                // Prepare data to send
                var data = {
                    link: resultUrl,
                    bytes: blob.size,
                    cover: resultUrl,
                    name: exportTarget
                };
                
                // Send photo's data
                window.pixabay_save_photo(data);

                // Close the modal
                document.querySelector('.db-close-lightbox').click();

            }

        };
        xhr.send();

    }
};
</script>

<?php } ?>

<!-- Translations !-->
<script language="javascript">
var words = {
    please_install_the_mobile_client: '<?php echo $this->lang->line('please_install_the_mobile_client'); ?>',
    select: '<?php echo $this->lang->line('select'); ?>',
    selected: '<?php echo $this->lang->line('posts_selected'); ?>',
    posts_scheduled: '<?php echo $this->lang->line('posts_scheduled'); ?>',
    posts_not_published: '<?php echo $this->lang->line('posts_not_published'); ?>',
    posts_draft: '<?php echo $this->lang->line('posts_draft'); ?>',
    posts_published: '<?php echo $this->lang->line('posts_published'); ?>',
};
</script>