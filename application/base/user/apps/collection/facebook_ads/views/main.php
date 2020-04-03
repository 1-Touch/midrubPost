<section class="section facebook-ads-page" data-up="<?php echo (get_option('upload_limit')) ? get_option('upload_limit') : '6'; ?>">
    <div class="row">
        <div class="col-xl-12">
            <div>
                <div class="row row-eq-height">
                    <div class="col-xl-3">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item facebook-ads-connector">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="btn-group dropup">
                                            <?php
                                            if ($selected_account) {
                                                ?>
                                                <button type="submit" class="btn btn-success ads-select-account" data-id="<?php echo $selected_account['account'][0]->network_id; ?>">
                                                    <i class="fab fa-facebook"></i> <?php echo $selected_account['account'][0]->user_name; ?>
                                                </button>
                                            <?php
                                            } else {
                                                ?>
                                                <button type="submit" class="btn btn-success ads-select-account">
                                                    <i class="icon-plus"></i> <?php echo $this->lang->line('select_ad_account'); ?>
                                                </button>
                                            <?php
                                            }
                                            ?>
                                            <ul class="dropdown-menu" role="menu">
                                            </ul>
                                            <button type="button" class="btn btn-success facebook-ads-manage-accounts" data-toggle="modal" data-target="#facebook-ads-manage-accounts">
                                                <i class="icon-user-follow"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ads-available-accounts">
                                    <div class="col-xl-12 ads-search-accounts">
                                        <div class="input-group ads-search-available-accounts">
                                            <div class="input-group-prepend">
                                                <i class="icon-magnifier"></i>
                                            </div>
                                            <input type="text" class="form-control available-accounts-search-for-accounts" placeholder="<?php echo $this->lang->line('search_ads_accounts'); ?>">
                                            <button type="button" class="cancel-available-accounts-search">
                                                <i class="icon-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <ul>
                                            <li>
                                                <?php echo $this->lang->line('no_accounts_found'); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#overview" role="tab" aria-controls="overview">
                                    <i class="icon-screen-desktop"></i>
                                    <?php echo $this->lang->line('overview'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#campaigns" role="tab" aria-controls="campaigns">
                                    <i class="icon-basket-loaded"></i>
                                    <?php echo $this->lang->line('campaigns'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#ad-sets" role="tab" aria-controls="ad-sets">
                                    <i class="icon-wallet"></i>
                                    <?php echo $this->lang->line('ad_sets'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#ads" role="tab" aria-controls="ads">
                                    <i class="icon-puzzle"></i>
                                    <?php echo $this->lang->line('ads'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#insights" role="tab" aria-controls="insights">
                                    <i class="icon-graph"></i>
                                    <?php echo $this->lang->line('insights'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ads-dropdown-toggle" href="#">
                                    <i class="far fa-chart-bar"></i>
                                    <?php echo $this->lang->line('pixel'); ?>
                                </a>
                                <ul class="ads-pixel nav nav-tabs" id="myTab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#pixel-conversion" role="tab" aria-controls="pixel-conversion">
                                            <i class="fas fa-chart-line"></i> <?php echo $this->lang->line('conversion_tracking'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ads-dropdown-toggle" href="#">
                                    <i class="icon-loop"></i>
                                    <?php echo $this->lang->line('automatizations'); ?>
                                </a>
                                <ul class="ads-automatizations nav nav-tabs" id="automatizations-tab" role="tablist">
                                    <?php facebook_ads_automatizations(); ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xl-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="overview" role="tabpanel">
                                <?php
                                if ($reached_the_maximum_api_limit) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-bell"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $reached_the_maximum_api_limit; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } elseif ($selected_account) {
                                    ?>
                                    <div class="row page-titles">
                                        <div class="col-xl-6">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle overview-filter-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <?php echo $this->lang->line('today'); ?>
                                                </button>
                                                <div class="dropdown-menu overview-filter-list" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#" data-type="1">
                                                        <i class="far fa-calendar-alt"></i>
                                                        <?php echo $this->lang->line('today'); ?>
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-type="2">
                                                        <i class="far fa-calendar-alt"></i>
                                                        <?php echo $this->lang->line('week'); ?>
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-type="3">
                                                        <i class="far fa-calendar-alt"></i>
                                                        <?php echo $this->lang->line('month'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <p></p>
                                        </div>
                                        <div class="col-xl-6 text-right clean">
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-new-ad">
                                                <i class="icon-puzzle"></i>
                                                <?php echo $this->lang->line('new_ad'); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('total_spent'); ?>
                                                        </h4>
                                                        <p class="overview-stats-total-spent"><?php echo isset($selected_account['account_insights']['data'][0]['spend']) ? $selected_account['account_insights']['data'][0]['spend'] : '0'; ?> <?php echo isset($selected_account['account_insights']['data'][0]['account_currency']) ? $selected_account['account_insights']['data'][0]['account_currency'] : ' '; ?></p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('social_spent'); ?>
                                                        </h4>
                                                        <p class="overview-stats-social-spent">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['social_spend']) ? $selected_account['account_insights']['data'][0]['social_spend'] : '0'; ?> <?php echo isset($selected_account['account_insights']['data'][0]['account_currency']) ? $selected_account['account_insights']['data'][0]['account_currency'] : ' '; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fab fa-facebook"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('impressions'); ?>
                                                        </h4>
                                                        <p class="overview-stats-impressions">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['impressions']) ? $selected_account['account_insights']['data'][0]['impressions'] : '0'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="far fa-eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('clicks'); ?>
                                                        </h4>
                                                        <p class="overview-stats-clicks">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['clicks']) ? $selected_account['account_insights']['data'][0]['clicks'] : '0'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fas fa-street-view"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('reach'); ?>
                                                        </h4>
                                                        <p class="overview-stats-reach">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['reach']) ? $selected_account['account_insights']['data'][0]['reach'] : '0'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fas fa-chalkboard-teacher"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('frequency'); ?>
                                                        </h4>
                                                        <p class="overview-stats-frequency">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['frequency']) ? $selected_account['account_insights']['data'][0]['frequency'] : '0'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fas fa-chart-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('cpm'); ?>
                                                        </h4>
                                                        <p class="overview-stats-cpm">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['cpm']) ? $selected_account['account_insights']['data'][0]['cpm'] : '0'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fas fa-file-contract"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('cpp'); ?>
                                                        </h4>
                                                        <p class="overview-stats-cpp">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['cpp']) ? $selected_account['account_insights']['data'][0]['cpp'] : '0'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="col-12 overview-insights-single">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4>
                                                            <?php echo $this->lang->line('ctr'); ?>
                                                        </h4>
                                                        <p class="overview-stats-ctr">
                                                            <?php echo isset($selected_account['account_insights']['data'][0]['ctr']) ? $selected_account['account_insights']['data'][0]['ctr'] : '0'; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <i class="fas fa-user-tag"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-user-unfollow"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $this->lang->line('no_account_selected'); ?></h3>
                                                    <p><?php echo $this->lang->line('please_select_ad_account'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tab-pane<?php if (!$selected_account ||  $reached_the_maximum_api_limit) : ?> no-account-result<?php endif; ?>" id="campaigns" role="tabpanel">
                                <?php
                                if ($reached_the_maximum_api_limit) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-bell"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $reached_the_maximum_api_limit; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } elseif ($selected_account) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="row" colspan="3">
                                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-campaign">
                                                                    <i class="icon-basket-loaded"></i>
                                                                    <?php echo $this->lang->line('new_campaign'); ?>
                                                                </button>
                                                                <button type="button" class="btn btn-dark ads-delete-campaigns"><i class="icon-trash"></i> <?php echo $this->lang->line('delete'); ?></button>
                                                            </th>
                                                            <th scope="row" colspan="3">
                                                                <button type="button" class="btn btn-dark pull-right btn-ads-reports btn-load-campaign-insights" data-toggle="modal" data-target="#ads-campaigns-insights">
                                                                    <i class="icon-graph"></i>
                                                                    <?php echo $this->lang->line('insights'); ?>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="checkbox-option-select">
                                                                    <input id="ads-campaigns-all" name="ads-campaigns-all" type="checkbox">
                                                                    <label for="ads-campaigns-all"></label>
                                                                </div>
                                                            </th>
                                                            <th scope="col"><?php echo $this->lang->line('name'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('status'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('campaign_objective'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('impressions'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('spent'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if ($selected_account['campaigns']['data']) {

                                                                $campaigns = $selected_account['campaigns']['data'];

                                                                $from = array(
                                                                    'APP_INSTALLS',
                                                                    'BRAND_AWARENESS',
                                                                    'CONVERSIONS',
                                                                    'EVENT_RESPONSES',
                                                                    'LEAD_GENERATION',
                                                                    'LINK_CLICKS',
                                                                    'LOCAL_AWARENESS',
                                                                    'MESSAGES',
                                                                    'OFFER_CLAIMS',
                                                                    'PAGE_LIKES',
                                                                    'POST_ENGAGEMENT',
                                                                    'PRODUCT_CATALOG_SALES',
                                                                    'REACH',
                                                                    'VIDEO_VIEWS'
                                                                );

                                                                $to = array(
                                                                    $this->lang->line('app_installs'),
                                                                    $this->lang->line('brand_awareness'),
                                                                    $this->lang->line('conversions'),
                                                                    $this->lang->line('event_responses'),
                                                                    $this->lang->line('lead_generation'),
                                                                    $this->lang->line('link_clicks'),
                                                                    $this->lang->line('local_awareness'),
                                                                    $this->lang->line('messages'),
                                                                    $this->lang->line('offer_claims'),
                                                                    $this->lang->line('page_likes'),
                                                                    $this->lang->line('post_engagement'),
                                                                    $this->lang->line('product_catalog_sales'),
                                                                    $this->lang->line('reach'),
                                                                    $this->lang->line('video_views')
                                                                );

                                                                foreach ($campaigns as $campaign) {

                                                                    $impressions = 0;

                                                                    if (isset($campaign['insights']['data'][0]['impressions'])) {
                                                                        $impressions = $campaign['insights']['data'][0]['impressions'];
                                                                    }

                                                                    $spend = 0;

                                                                    if (isset($campaign['insights']['data'][0]['spend'])) {
                                                                        $spend = $campaign['insights']['data'][0]['spend'];
                                                                    }

                                                                    $spend = $spend . ' <span class="ads-account-currency"></span>';

                                                                    echo '<tr>'
                                                                        . '<th scope="row">'
                                                                        . '<div class="checkbox-option-select">'
                                                                        . '<input id="ads-campaigns-' . $campaign['id'] . '" name="ads-campaigns-' . $campaign['id'] . '" type="checkbox" data-id="' . $campaign['id'] . '">'
                                                                        . '<label for="ads-campaigns-' . $campaign['id'] . '"></label>'
                                                                        . '</div>'
                                                                        . '</th>'
                                                                        . '<td>'
                                                                        . $campaign['name']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $campaign['status']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . str_replace($from, $to, $campaign['objective'])
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $impressions
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $spend
                                                                        . '</td>'
                                                                        . '</tr>';
                                                                }
                                                            } else {

                                                                echo '<tr>'
                                                                    . '<td colspan="6" class="p-3">'
                                                                    . $this->lang->line('no_campaigns_found')
                                                                    . '</td>'
                                                                    . '</tr>';
                                                            }

                                                            ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="8" class="text-right">
                                                                <?php
                                                                    $previous = '';
                                                                    $pdisabled = ' btn-disabled';

                                                                    if (isset($selected_account['campaigns']['paging']['previous'])) {
                                                                        $previous .= ' data-url="' . $selected_account['campaigns']['paging']['previous'] . '"';
                                                                        $pdisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-previous btn-campaign-pagination' . $pdisabled . '"' . $previous . '><i class="far fa-arrow-alt-circle-left"></i> ' . $this->lang->line('previous') . '</button>';

                                                                    $next = '';
                                                                    $ndisabled = ' btn-disabled';

                                                                    if (isset($selected_account['campaigns']['paging']['next'])) {
                                                                        $next .= ' data-url="' . $selected_account['campaigns']['paging']['next'] . '"';
                                                                        $ndisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-next btn-campaign-pagination' . $ndisabled . '"' . $next . '>' . $this->lang->line('next') . ' <i class="far fa-arrow-alt-circle-right"></i></button>';
                                                                    ?>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-user-unfollow"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $this->lang->line('no_account_selected'); ?></h3>
                                                    <p><?php echo $this->lang->line('please_select_ad_account'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tab-pane<?php if (!$selected_account ||  $reached_the_maximum_api_limit) : ?> no-account-result<?php endif; ?>" id="ad-sets" role="tabpanel">
                                <?php
                                if ($reached_the_maximum_api_limit) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-bell"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $reached_the_maximum_api_limit; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } elseif ($selected_account) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="row" colspan="3">
                                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-ad-set">
                                                                    <i class="icon-wallet"></i>
                                                                    <?php echo $this->lang->line('new_ad_set'); ?>
                                                                </button>
                                                                <button type="button" class="btn btn-dark ads-delete-adsets"><i class="icon-trash"></i> <?php echo $this->lang->line('delete'); ?></button>
                                                            </th>
                                                            <th scope="row" colspan="3">
                                                                <button type="button" class="btn btn-dark pull-right btn-ads-reports btn-load-ad-sets-insights" data-toggle="modal" data-target="#ads-ad-sets-insights">
                                                                    <i class="icon-graph"></i>
                                                                    <?php echo $this->lang->line('insights'); ?>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="checkbox-option-select">
                                                                    <input id="ads-adsets-all" name="ads-adsets-all" type="checkbox">
                                                                    <label for="ads-adsets-all"></label>
                                                                </div>
                                                            </th>
                                                            <th scope="col"><?php echo $this->lang->line('name'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('status'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('campaign'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('impressions'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('spent'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if ($selected_account['adsets']['data']) {

                                                                $campaigns = $selected_account['adsets']['data'];

                                                                foreach ($campaigns as $campaign) {

                                                                    $impressions = 0;

                                                                    if (isset($campaign['insights']['data'][0]['impressions'])) {
                                                                        $impressions = $campaign['insights']['data'][0]['impressions'];
                                                                    }

                                                                    $spend = 0;

                                                                    if (isset($campaign['insights']['data'][0]['spend'])) {
                                                                        $spend = $campaign['insights']['data'][0]['spend'];
                                                                    }

                                                                    $spend = $spend . ' <span class="ads-account-currency"></span>';

                                                                    echo '<tr>'
                                                                        . '<th scope="row">'
                                                                        . '<div class="checkbox-option-select">'
                                                                        . '<input id="ads-campaigns-' . $campaign['id'] . '" name="ads-campaigns-' . $campaign['id'] . '" type="checkbox" data-id="' . $campaign['id'] . '">'
                                                                        . '<label for="ads-campaigns-' . $campaign['id'] . '"></label>'
                                                                        . '</div>'
                                                                        . '</th>'
                                                                        . '<td>'
                                                                        . $campaign['name']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $campaign['status']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $campaign['campaign']['name']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $impressions
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $spend
                                                                        . '</td>'
                                                                        . '</tr>';
                                                                }
                                                            } else {

                                                                echo '<tr>'
                                                                    . '<td colspan="6" class="p-3">'
                                                                    . $this->lang->line('no_adsets_found')
                                                                    . '</td>'
                                                                    . '</tr>';
                                                            }

                                                            ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="6" class="text-right">
                                                                <?php
                                                                    $previous = '';
                                                                    $pdisabled = ' btn-disabled';

                                                                    if (isset($selected_account['adsets']['paging']['previous'])) {
                                                                        $previous .= ' data-url="' . $selected_account['adsets']['paging']['previous'] . '"';
                                                                        $pdisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-previous btn-adsets-pagination' . $pdisabled . '"' . $previous . '><i class="far fa-arrow-alt-circle-left"></i> ' . $this->lang->line('previous') . '</button>';

                                                                    $next = '';
                                                                    $ndisabled = ' btn-disabled';

                                                                    if (isset($selected_account['adsets']['paging']['next'])) {
                                                                        $next .= ' data-url="' . $selected_account['adsets']['paging']['next'] . '"';
                                                                        $ndisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-next btn-adsets-pagination' . $ndisabled . '"' . $next . '>' . $this->lang->line('next') . ' <i class="far fa-arrow-alt-circle-right"></i></button>';
                                                                    ?>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-user-unfollow"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $this->lang->line('no_account_selected'); ?></h3>
                                                    <p><?php echo $this->lang->line('please_select_ad_account'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tab-pane<?php if (!$selected_account ||  $reached_the_maximum_api_limit) : ?> no-account-result<?php endif; ?>" id="ads" role="tabpanel">
                                <?php
                                if ($reached_the_maximum_api_limit) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-bell"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $reached_the_maximum_api_limit; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } elseif ($selected_account) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="row" colspan="3">
                                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-new-ad">
                                                                    <i class="icon-puzzle"></i>
                                                                    <?php echo $this->lang->line('new_ad'); ?>
                                                                </button>
                                                                <button type="button" class="btn btn-dark ads-delete-ad"><i class="icon-trash"></i> <?php echo $this->lang->line('delete'); ?></button>
                                                            </th>
                                                            <th scope="row" colspan="3">
                                                                <button type="button" class="btn btn-dark pull-right btn-ads-reports btn-load-ad-insights" data-toggle="modal" data-target="#ads-ad-insights">
                                                                    <i class="icon-graph"></i>
                                                                    <?php echo $this->lang->line('insights'); ?>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="checkbox-option-select">
                                                                    <input id="ads-ad-all" name="ads-ad-all" type="checkbox">
                                                                    <label for="ads-ad-all"></label>
                                                                </div>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('name'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-secondary dropdown-toggle ads-status-filter-btn" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <?php echo $this->lang->line('status'); ?>
                                                                    </button>
                                                                    <div class="dropdown-menu ads-status-filter-list" aria-labelledby="dropdownMenuButton2" x-placement="bottom-start">
                                                                        <a class="dropdown-item" href="#" data-type="1">
                                                                            ACTIVE
                                                                        </a>
                                                                        <a class="dropdown-item" href="#" data-type="2">
                                                                            PAUSED
                                                                        </a>  
                                                                        <a class="dropdown-item" href="#" data-type="3">
                                                                            DELETED
                                                                        </a>   
                                                                        <a class="dropdown-item" href="#" data-type="4">
                                                                            ARCHIVED
                                                                        </a>                                                                                                                                                                                                                                                                                     
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('ad_set'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('impressions'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('spent'); ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if ($selected_account['ads']['data']) {

                                                                $ads = $selected_account['ads']['data'];

                                                                foreach ($ads as $ad) {

                                                                    $impressions = 0;

                                                                    if (isset($ad['insights']['data'][0]['impressions'])) {
                                                                        $impressions = $ad['insights']['data'][0]['impressions'];
                                                                    }

                                                                    $spend = 0;

                                                                    if (isset($ad['insights']['data'][0]['spend'])) {
                                                                        $spend = $ad['insights']['data'][0]['spend'];
                                                                    }

                                                                    $spend = $spend . ' <span class="ads-account-currency"></span>';

                                                                    echo '<tr>'
                                                                        . '<th scope="row">'
                                                                        . '<div class="checkbox-option-select">'
                                                                        . '<input id="ads-campaigns-' . $ad['id'] . '" name="ads-campaigns-' . $ad['id'] . '" type="checkbox" data-id="' . $ad['id'] . '">'
                                                                        . '<label for="ads-campaigns-' . $ad['id'] . '"></label>'
                                                                        . '</div>'
                                                                        . '</th>'
                                                                        . '<td>'
                                                                        . $ad['name']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $ad['status']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $ad['adset']['name']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $impressions
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $spend
                                                                        . '</td>'
                                                                        . '</tr>';
                                                                }
                                                            } else {

                                                                echo '<tr>'
                                                                    . '<td colspan="6" class="p-3">'
                                                                    . $this->lang->line('no_ads_found')
                                                                    . '</td>'
                                                                    . '</tr>';
                                                            }

                                                            ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="6" class="text-right">
                                                                <?php
                                                                    $previous = '';
                                                                    $pdisabled = ' btn-disabled';

                                                                    if (isset($selected_account['adsets']['paging']['previous'])) {
                                                                        $previous .= ' data-url="' . $selected_account['adsets']['paging']['previous'] . '"';
                                                                        $pdisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-previous btn-ad-pagination' . $pdisabled . '"' . $previous . '><i class="far fa-arrow-alt-circle-left"></i> ' . $this->lang->line('previous') . '</button>';

                                                                    $next = '';
                                                                    $ndisabled = ' btn-disabled';

                                                                    if (isset($selected_account['adsets']['paging']['next'])) {
                                                                        $next .= ' data-url="' . $selected_account['adsets']['paging']['next'] . '"';
                                                                        $ndisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-next btn-ad-pagination' . $ndisabled . '"' . $next . '>' . $this->lang->line('next') . ' <i class="far fa-arrow-alt-circle-right"></i></button>';
                                                                    ?>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-user-unfollow"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $this->lang->line('no_account_selected'); ?></h3>
                                                    <p><?php echo $this->lang->line('please_select_ad_account'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tab-pane<?php if (!$selected_account ||  $reached_the_maximum_api_limit) : ?> no-account-result<?php endif; ?>" id="insights" role="tabpanel">
                                <?php
                                if ($reached_the_maximum_api_limit) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-bell"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $reached_the_maximum_api_limit; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } elseif ($selected_account) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="row" colspan="6">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-secondary dropdown-toggle insights-filter-btn" data-type="1" type="button" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="far fa-calendar-alt"></i>
                                                                        <?php echo $this->lang->line('today'); ?>
                                                                    </button>
                                                                    <div class="dropdown-menu insights-filter-list" aria-labelledby="dropdownMenuButton3">
                                                                        <a class="dropdown-item" href="#" data-type="1">
                                                                            <i class="far fa-calendar-alt"></i>
                                                                            <?php echo $this->lang->line('today'); ?>
                                                                        </a>
                                                                        <a class="dropdown-item" href="#" data-type="2">
                                                                            <i class="far fa-calendar-alt"></i>
                                                                            <?php echo $this->lang->line('week'); ?>
                                                                        </a>
                                                                        <a class="dropdown-item" href="#" data-type="3">
                                                                            <i class="far fa-calendar-alt"></i>
                                                                            <?php echo $this->lang->line('month'); ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <button type="button" class="btn btn-success insights-btn-show-insights">
                                                                    <i class="fas fa-file-download"></i>
                                                                    <?php echo $this->lang->line('show'); ?>
                                                                </button>
                                                            </th>
                                                            <th scope="row" colspan="2">
                                                                <button type="button" class="btn btn-dark pull-right btn-ads-reports btn-insights-download">
                                                                    <i class="icon-cloud-download"></i>
                                                                    <?php echo $this->lang->line('download'); ?>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('date'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('impressions'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('reach'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('clicks'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('cpm'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('cpc'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('ctr'); ?>
                                                            </th>
                                                            <th scope="col">
                                                                <?php echo $this->lang->line('spent'); ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8">
                                                                <?php echo $this->lang->line('no_insights_found'); ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-user-unfollow"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $this->lang->line('no_account_selected'); ?></h3>
                                                    <p><?php echo $this->lang->line('please_select_ad_account'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="tab-pane<?php if (!$selected_account ||  $reached_the_maximum_api_limit) : ?> no-account-result<?php endif; ?>" id="pixel-conversion" role="tabpanel">
                                <?php
                                if ($reached_the_maximum_api_limit) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-bell"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $reached_the_maximum_api_limit; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } elseif ($selected_account) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="row">
                                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pixel-new-coversion">
                                                                    <i class="fas fa-chart-line"></i>
                                                                    <?php echo $this->lang->line('new_conversion'); ?>
                                                                </button>
                                                            </th>
                                                            <th scope="row">
                                                            </th>
                                                            <th scope="row">
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col"><?php echo $this->lang->line('name'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('type'); ?></th>
                                                            <th scope="col"><?php echo $this->lang->line('url'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if ($selected_account['tracking_conversion']['data']) {

                                                                $conversions = $selected_account['tracking_conversion']['data'];

                                                                $from = array(
                                                                    'CONTENT_VIEW',
                                                                    'SEARCH',
                                                                    'ADD_TO_CART',
                                                                    'ADD_TO_WISHLIST',
                                                                    'INITIATED_CHECKOUT',
                                                                    'ADD_PAYMENT_INFO',
                                                                    'PURCHASE',
                                                                    'LEAD',
                                                                    'COMPLETE_REGISTRATION'
                                                                );

                                                                $to = array(
                                                                    $this->lang->line('view_content'),
                                                                    $this->lang->line('search'),
                                                                    $this->lang->line('add_to_cart'),
                                                                    $this->lang->line('add_to_wishlist'),
                                                                    $this->lang->line('initiate_checkout'),
                                                                    $this->lang->line('add_payment_info'),
                                                                    $this->lang->line('purchase'),
                                                                    $this->lang->line('lead'),
                                                                    $this->lang->line('complete_registration')
                                                                );

                                                                foreach ($conversions as $conversion) {

                                                                    $rule = '';

                                                                    if (isset($conversion['rule'])) {

                                                                        $decode = json_decode($conversion['rule'], true);

                                                                        if (isset($decode['url']['i_contains'])) {
                                                                            $rule = $decode['url']['i_contains'];
                                                                        }
                                                                    }

                                                                    echo '<tr>'
                                                                        . '<td>'
                                                                        . $conversion['name']
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . str_replace($from, $to, $conversion['custom_event_type'])
                                                                        . '</td>'
                                                                        . '<td>'
                                                                        . $rule
                                                                        . '</td>'
                                                                        . '</tr>';
                                                                }
                                                            } else {

                                                                echo '<tr>'
                                                                    . '<td colspan="6" class="p-3">'
                                                                    . $this->lang->line('no_conversion_tracking_found')
                                                                    . '</td>'
                                                                    . '</tr>';
                                                            }

                                                            ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="8" class="text-right">
                                                                <?php
                                                                    $previous = '';
                                                                    $pdisabled = ' btn-disabled';

                                                                    if (isset($selected_account['tracking_conversion']['paging']['previous'])) {
                                                                        $previous .= ' data-url="' . $selected_account['tracking_conversion']['paging']['previous'] . '"';
                                                                        $pdisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-previous btn-conversions-pagination' . $pdisabled . '"' . $previous . '>'
                                                                        . '<i class="far fa-arrow-alt-circle-left"></i> '
                                                                        . $this->lang->line('previous')
                                                                        . '</button>';

                                                                    $next = '';
                                                                    $ndisabled = ' btn-disabled';

                                                                    if (isset($selected_account['tracking_conversion']['paging']['next'])) {
                                                                        $next .= ' data-url="' . $selected_account['tracking_conversion']['paging']['next'] . '"';
                                                                        $ndisabled = '';
                                                                    }

                                                                    echo '<button type="button" class="btn btn-dark btn-next btn-conversions-pagination' . $ndisabled . '"' . $next . '>'
                                                                        . $this->lang->line('next')
                                                                        . ' <i class="far fa-arrow-alt-circle-right"></i>'
                                                                        . '</button>';

                                                                    ?>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group no-account-selected">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="icon-user-unfollow"></i>
                                                    </span>
                                                </div>
                                                <div class="form-control">
                                                    <h3><?php echo $this->lang->line('no_account_selected'); ?></h3>
                                                    <p><?php echo $this->lang->line('please_select_ad_account'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <?php

                            foreach (glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR) as $automatization_dir) {

                                $automatization = trim(basename($automatization_dir) . PHP_EOL);

                                // Create an array
                                $array = array(
                                    'MidrubBase',
                                    'User',
                                    'Apps',
                                    'Collection',
                                    'Facebook_ads',
                                    'Automatizations',
                                    ucfirst($automatization),
                                    'Main'
                                );

                                // Implode the array above
                                $cl = implode('\\', $array);

                                // Get automatization info
                                $automatization_info = (new $cl())->automatization_info();

                                ?>
                                <div class="tab-pane<?php if (!$selected_account ||  $reached_the_maximum_api_limit) : ?> no-account-result<?php endif; ?>" id="<?php echo $automatization_info['automatization_slug']; ?>" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <?php
                                                echo (new $cl())->user();
                                                ?>
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
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="facebook-ads-manage-accounts" tabindex="-1" role="dialog" aria-labelledby="facebook-ads-manage-accounts" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-ad-accounts-tab" data-toggle="tab" href="#nav-ad-accounts" role="tab" aria-controls="nav-ad-accounts" aria-selected="true">
                            <?php echo $this->lang->line('facebook_ad_accounts'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="nav-ad-accounts" role="tabpanel" aria-labelledby="nav-ad-accounts">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="jumbotron">
                                    <div class="container">
                                        <button class="btn btn-success connect-add-accounts" type="button">
                                            <i class="fab fa-facebook"></i>
                                            <?php echo $this->lang->line('connect_ad_accounts'); ?>
                                        </button>
                                        <p>
                                            <?php echo $this->lang->line('connect_ad_accounts_description'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 account-manager-accounts-list">
                                <div class="row manage-accounts-search-form">
                                    <div class="col-xl-12">
                                        <div class="input-group accounts-manager-search">
                                            <div class="input-group-prepend">
                                                <i class="icon-magnifier"></i>
                                            </div>
                                            <input type="text" class="form-control accounts-manager-search-for-accounts" placeholder="<?php echo $this->lang->line('search_ads_accounts'); ?>">
                                            <button type="button" class="cancel-accounts-manager-search">
                                                <i class="icon-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <ul class="accounts-manager-connected-accounts">
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 clean">
                                        <ul class="pagination" data-type="available-accounts">
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

<?php echo form_open('user/app/facebook-ads', array('class' => 'facebook-ads-create-ad', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
<?php echo form_close(); ?>

<!-- Create Campaign -->
<div class="modal fade" id="ads-create-campaign" tabindex="-1" role="dialog" aria-labelledby="ads-create-campaign-tab" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/facebook-ads', array('class' => 'facebook-ads-create-campaign', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-tab-stream-react">
                            <?php echo $this->lang->line('create_campaign'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="row-input">
                    <div class="panel-group wrap" id="bs-collapse">
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#campaign-create" class="opened-box">
                                        <span>
                                            1
                                        </span>
                                        <?php echo $this->lang->line('create_ad_campaign'); ?>
                                        <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="campaign-create" class="panel-collapse collapse in collapse show">
                                <div class="panel-body">
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('ad_campaign_name'); ?> <em>(<?php echo $this->lang->line('required'); ?>)</em>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('add_name_ad_campaign'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-campaign-name" placeholder="<?php echo $this->lang->line('enter_campaign_name'); ?>" required="">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('campaign_objective'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('campaign_objective_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-campaign-objective btn-select" data-id="LINK_CLICKS" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('link_clicks'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-objective-list" aria-labelledby="dropdownMenuButton4">
                                                            <a class="dropdown-item" href="#" data-id="LINK_CLICKS"><?php echo $this->lang->line('link_clicks'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="POST_ENGAGEMENT"><?php echo $this->lang->line('post_engagement'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('campaign_status'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('select_campaign_status'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-campaign-status btn-select" data-id="ACTIVE" type="button" id="dropdownMenuButton5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('active'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-status-list" aria-labelledby="dropdownMenuButton5">
                                                            <a class="dropdown-item" href="#" data-id="ACTIVE"><?php echo $this->lang->line('active'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="PAUSED"><?php echo $this->lang->line('paused'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('special_ad_category'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('special_ad_category_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle special-ad-category btn-select" data-id="HOUSING" type="button" id="dropdownMenuButton6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('housing'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown special-ad-category-list" aria-labelledby="dropdownMenuButton6">
                                                            <a class="dropdown-item" href="#" data-id="HOUSING"><?php echo $this->lang->line('housing'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="CREDIT"><?php echo $this->lang->line('credit'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="EMPLOYMENT"><?php echo $this->lang->line('employment'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="NONE"><?php echo $this->lang->line('none'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#campaign-create-ad-set">
                                        <span>
                                            2
                                        </span>
                                        <?php echo $this->lang->line('create_ad_set'); ?>
                                        <em class="required">(<?php echo $this->lang->line('optional'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="campaign-create-ad-set" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('ad_set_name'); ?> <em>(<?php echo $this->lang->line('required'); ?>)</em>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('ad_set_name_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-adset-name" placeholder="<?php echo $this->lang->line('enter_ad_set_name'); ?>">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('optimization_goal'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('optimization_goal_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-campaign-optimization-goal btn-select" data-id="IMPRESSIONS" type="button" id="dropdownMenuButton7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('impressions'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-optimization-goal-list" aria-labelledby="dropdownMenuButton7">
                                                            <a class="dropdown-item" href="#" data-id="IMPRESSIONS"><?php echo $this->lang->line('impressions'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="LINK_CLICKS"><?php echo $this->lang->line('link_clicks'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="REACH"><?php echo $this->lang->line('daily_unique_reach'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('billing_event'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('billing_event_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-campaign-billing-event btn-select" data-id="IMPRESSIONS" type="button" id="dropdownMenuButton8" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('impressions'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-billing-event-list" aria-labelledby="dropdownMenuButton8">
                                                            <a class="dropdown-item" href="#" data-id="IMPRESSIONS"><?php echo $this->lang->line('impressions'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('placement'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('all_placements'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-ad-set-placements" aria-labelledby="dropdownMenuButton9">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Facebook</h5>
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('feeds'); ?>
                                                                            <input type="checkbox" id="ad-set-placement-facebook-feeds" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Instagram</h5>
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('feed'); ?>
                                                                            <input type="checkbox" id="ad-set-placement-instagram-feed" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Messenger</h5>
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('inbox'); ?>
                                                                            <input type="checkbox" id="ad-set-placement-messenger-inbox" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('target_cost'); ?> <span class="ad_set_target_cost"></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('target_cost_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-adset-target-cost" placeholder="<?php echo $this->lang->line('enter_ad_set_target_cost'); ?>">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('daily_budget'); ?> <span class="ad_set_daily_budget"></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('daily_budget_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-adset-daily-budget" placeholder="<?php echo $this->lang->line('enter_ad_set_daily_budget'); ?>">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('countries'); ?> <span class="ad_set_default_country"></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('select_countries_supported'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="show-more-content">
                                                        <a data-toggle="collapse" href="#campaign_creation_show_select_more_countries" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                                                            <?php echo $this->lang->line('show_countries_list'); ?>
                                                        </a>
                                                    </p>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="collapse select-countries multi-collapse row" id="campaign_creation_show_select_more_countries">
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-africa" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('africa'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-africa">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('burundi'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-burundi" data-id="BI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('comoros'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-comoros" data-id="KM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ghana'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ghana" data-id="GH">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('eritrea'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-eritrea" data-id="ER">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ethiopia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ethiopia" data-id="ET">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kenya'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kenya" data-id="KE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('madagascar'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-madagascar" data-id="MG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('malawi'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-malawi" data-id="MW">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mauritius'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mauritius" data-id="MU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mayotte'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mayotte" data-id="YT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mozambique'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mozambique" data-id="MZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('reunion'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-reunion" data-id="RE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('rwanda'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-rwanda" data-id="RW">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('seychelles'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-seychelles" data-id="SC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('uganda'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-uganda" data-id="UG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('tanzania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-tanzania" data-id="TZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('zambia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-zambia" data-id="ZM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('angola'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-angola" data-id="AO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('cameroon'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-cameroon" data-id="CM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guinea'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guinea" data-id="PG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('algeria'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-algeria" data-id="DZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('egypt'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-egypt" data-id="EG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('morocco'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-morocco" data-id="MA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('tunisia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-tunisia" data-id="TN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('south_africa'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-south_africa" data-id="ZA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('nigeria'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-nigeria" data-id="NG">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-asia" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('asia'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-asia">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kazakhstan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kazakhstan" data-id="KZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kyrgyzstan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kyrgyzstan" data-id="KG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('tajikistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-tajikistan" data-id="TJ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('turkmenistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-turkmenistan" data-id="TM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('uzbekistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-uzbekistan" data-id="UZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('china'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-china" data-id="CN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('hong_kong'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-hong_kong" data-id="HK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('japan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-japan" data-id="JP">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bangladesh'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bangladesh" data-id="BD">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('india'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-india" data-id="IN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('nepal'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-nepal" data-id="NP">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('pakistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-pakistan" data-id="PK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('indonesia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-indonesia" data-id="ID">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('malaysia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-malaysia" data-id="MY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('philippines'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-philippines" data-id="PH">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('singapore'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-singapore" data-id="SG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('thailand'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-thailand" data-id="TH">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('israel'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-israel" data-id="IL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('jordan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-jordan" data-id="JO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kuwait'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kuwait" data-id="KW">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('qatar'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-qatar" data-id="QA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('saudi_arabia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-saudi_arabia" data-id="SA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('united_arab_emirates'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-united_arab_emirates" data-id="AE">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-central-america" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('central_america'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-central-america">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('belize'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-belize" data-id="BZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('costa_rica'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-costa_rica" data-id="CR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('el_salvador'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-el_salvador" data-id="SV">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guatemala'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guatemala" data-id="GT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('honduras'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-honduras" data-id="HN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mexico'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mexico" data-id="MX">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('nicaragua'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-nicaragua" data-id="NI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('panama'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-panama" data-id="PA">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-baltic_states" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('baltic_states'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-baltic_states">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('estonia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-estonia" data-id="EE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('latvia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-latvia" data-id="LV">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('lithuania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-lithuania" data-id="LT">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-caucasus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('caucasus'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-caucasus">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('azerbaijan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-azerbaijan" data-id="AZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('georgia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-georgia" data-id="GE">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-southeastern" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('southeastern'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-southeastern">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('belarus'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-belarus" data-id="BY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bulgaria'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bulgaria" data-id="BG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('moldova'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-moldova" data-id="MD">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('romania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-romania" data-id="RO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('russia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-russia" data-id="RU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ukraine'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ukraine" data-id="UA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('albania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-albania" data-id="AL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('croatia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-croatia" data-id="HR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('greece'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-greece" data-id="GR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('italy'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-italy" data-id="IT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('malta'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-malta" data-id="MT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('montenegro'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-montenegro" data-id="ME">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('serbia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-serbia" data-id="RS">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('slovenia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-slovenia" data-id="SI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('macedonia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-macedonia" data-id="MK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('turkey'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-turkey" data-id="TR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('cyprus'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-cyprus" data-id="CY">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-central" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('central'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-central">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('czech_republic'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-czech_republic" data-id="CZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('hungary'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-hungary" data-id="HU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('poland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-poland" data-id="PL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('slovakia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-slovakia" data-id="SK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('germany'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-germany" data-id="DE">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-western" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('western'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-western">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('denmark'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-denmark" data-id="DK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('finland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-finland" data-id="FI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('iceland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-iceland" data-id="IS">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('greenland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-greenland" data-id="GL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ireland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ireland" data-id="IE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('norway'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-norway" data-id="NO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('sweden'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-sweden" data-id="SE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('united_kingdom'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-united_kingdom" data-id="GB">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('portugal'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-portugal" data-id="PT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('spain'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-spain" data-id="ES">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('belgium'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-belgium" data-id="BE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('france'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-france" data-id="FR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('luxembourg'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-luxembourg" data-id="LU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('monaco'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-monaco" data-id="MC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('netherlands'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-netherlands" data-id="NL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('switzerland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-switzerland" data-id="CH">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-north_america" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('north_america'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-north_america">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bermuda'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bermuda" data-id="BM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('canada'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-canada" data-id="CA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('united_states'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-united_states" data-id="US">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-oceania" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('oceania'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-oceania">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('australia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-australia" data-id="AU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('new_zealand'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-new_zealand" data-id="NZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('new_caledonia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-new_caledonia" data-id="NC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('papua_new_guinea'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-papua_new_guinea" data-id="PG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guam'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guam" data-id="GU">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-south_america" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('south_america'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-south_america">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('argentina'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-argentina" data-id="AR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bolivia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bolivia" data-id="BO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('brazil'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-brazil" data-id="BR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('chile'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-chile" data-id="CL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('colombia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-colombia" data-id="CO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ecuador'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ecuador" data-id="EC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guyana'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guyana" data-id="GY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('paraguay'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-paraguay" data-id="PY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('uruguay'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-uruguay" data-id="UY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('venezuela'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-venezuela" data-id="VE">
                                                                                        </li>
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
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('regions'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('regions_description'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 select-regions select-input-search">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('cities'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('cities_description'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 select-cities select-input-search">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('genders'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('genders_select'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdown-genders" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('all_genders'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-ad-genders" aria-labelledby="dropdown-genders">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('female'); ?>
                                                                            <input type="checkbox" id="ad-campaign-gender-female" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('male'); ?>
                                                                            <input type="checkbox" id="ad-campaign-gender-male" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('age'); ?> <span><em>(<?php echo $this->lang->line('default_age_any'); ?>)</em></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('age_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-campaign-age-from btn-select" data-id="0" type="button" id="dropdownMenuButton10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('age_from'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-age-from-list" aria-labelledby="dropdownMenuButton10">
                                                            <a class="dropdown-item" href="#" data-id="18">18</a>
                                                            <a class="dropdown-item" href="#" data-id="20">20</a>
                                                            <a class="dropdown-item" href="#" data-id="25">25</a>
                                                            <a class="dropdown-item" href="#" data-id="30">30</a>
                                                            <a class="dropdown-item" href="#" data-id="35">35</a>
                                                            <a class="dropdown-item" href="#" data-id="40">40</a>
                                                            <a class="dropdown-item" href="#" data-id="45">45</a>
                                                            <a class="dropdown-item" href="#" data-id="50">50</a>
                                                            <a class="dropdown-item" href="#" data-id="55">55</a>
                                                            <a class="dropdown-item" href="#" data-id="60">60</a>
                                                            <a class="dropdown-item" href="#" data-id="65">65</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-campaign-age-to btn-select" data-id="0" type="button" id="dropdownMenuButton11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('age_to'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-age-to-list" aria-labelledby="dropdownMenuButton11">
                                                            <a class="dropdown-item" href="#" data-id="20">20</a>
                                                            <a class="dropdown-item" href="#" data-id="25">25</a>
                                                            <a class="dropdown-item" href="#" data-id="30">30</a>
                                                            <a class="dropdown-item" href="#" data-id="35">35</a>
                                                            <a class="dropdown-item" href="#" data-id="40">40</a>
                                                            <a class="dropdown-item" href="#" data-id="45">45</a>
                                                            <a class="dropdown-item" href="#" data-id="50">50</a>
                                                            <a class="dropdown-item" href="#" data-id="55">55</a>
                                                            <a class="dropdown-item" href="#" data-id="60">60</a>
                                                            <a class="dropdown-item" href="#" data-id="65">65</a>
                                                            <a class="dropdown-item" href="#" data-id="70">70</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('device_types'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('device_types_description'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdown-devices" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('all_devices'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-select-type" aria-labelledby="dropdown-devices">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('mobile'); ?>
                                                                            <input type="checkbox" id="ad-campaign-mobile-type" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('desktop'); ?>
                                                                            <input type="checkbox" id="ad-campaign-desktop-type" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#campaign-create-ads">
                                        <span>
                                            3
                                        </span>
                                        <?php echo $this->lang->line('create_ad'); ?>
                                        <em class="required">(<?php echo $this->lang->line('optional'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="campaign-create-ads" class="panel-collapse collapse">
                                <div class="tab-content" id="myTabContent5">
                                    <div class="tab-pane fade show active" id="campaign-create-ads-links" role="tabpanel" aria-labelledby="campaign-create-ads-links-tab">
                                        <div class="panel-body">
                                            <div class="row clean">
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-options">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_content'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="ad_name"><?php echo $this->lang->line('ad_name'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="ad_name">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 links-clicks-preview-settings text-center">
                                                                        <hr>
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton12" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="icon-picture"></i>
                                                                                <?php echo $this->lang->line('image'); ?> <em class="required">(<?php echo $this->lang->line('required_for_instagram'); ?>)</em>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton12">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-settings-photo">
                                                                                    <i class="icon-picture"></i>
                                                                                    <?php echo $this->lang->line('image'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 text-center">
                                                                        <div class="tab-content" id="links-clicks-preview-settings">
                                                                            <div class="tab-pane fade show active" id="links-clicks-preview-settings-photo" role="tabpanel" aria-labelledby="links-clicks-preview-settings-photo-tab">
                                                                                <div class="row">
                                                                                    <div class="col-xl-12">
                                                                                        <button type="button" class="btn btn-primary btn-upload upload-ads-image" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                                            <i class="icon-cloud-upload"></i>
                                                                                            <?php echo $this->lang->line('select_image'); ?>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row ads-uploaded-photo">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="text"><?php echo $this->lang->line('text'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <textarea id="text" class="form-control text"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="website_url"><?php echo $this->lang->line('website_url'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="website_url">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="show-more-content">
                                                                            <a data-toggle="collapse" href="#campaign_creation_show_ads_advanced" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                                                                                <?php echo $this->lang->line('show_advanced_options'); ?>
                                                                            </a>
                                                                        </p>
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <div class="collapse multi-collapse" id="campaign_creation_show_ads_advanced">
                                                                                    <div class="row">
                                                                                        <div class="col-12">
                                                                                            <label for="headline"><?php echo $this->lang->line('headline'); ?></label>
                                                                                            <input type="text" class="headline">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-12">
                                                                                            <label for="description"><?php echo $this->lang->line('news_feed_link_description'); ?></label>
                                                                                            <textarea id="text" class="form-control description"></textarea>
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
                                                    <div class="col-12 ad-creation-options ad-identity">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-preview ad-preview-display">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_preview'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton13" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton13">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-fb-desktop-feed">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-instagram-feed">
                                                                                    <i class="icon-social-instagram"></i>
                                                                                    <?php echo $this->lang->line('instagram_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-messenger-inbox">
                                                                                    <i class="fab fa-facebook-messenger"></i>
                                                                                    <?php echo $this->lang->line('messenger_inbox'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 ad-creation-options pixel-conversion-tracking">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('tracking'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="campaign-create-ads-page-likes" role="tabpanel" aria-labelledby="campaign-create-ads-links-tab">
                                        <div class="panel-body">
                                            <div class="row clean">
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-options">
                                                        22
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12">
                                                        2
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="campaign-create-ads-post-engagement" role="tabpanel" aria-labelledby="campaign-create-ads-links-tab">
                                        <div class="panel-body">
                                            <div class="row clean">
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-options ad-identity">
                                                    </div>
                                                    <div class="col-12 ad-creation-options post-engagement-list">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('posts'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                Facebook
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton14">
                                                                                <a class="dropdown-item" href="#post-engagement-from-facebook">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    Facebook
                                                                                </a>
                                                                                <!--<a class="dropdown-item" href="#post-engagement-from-instagram">
                                                                                        <i class="icon-social-instagram"></i>
                                                                                        Instagram
                                                                                    </a>-->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="ad_name"><?php echo $this->lang->line('ad_name'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="ad_name">
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="tab-content" id="post-engagement-list">
                                                                    <div class="tab-pane fade show active" id="post-engagement-from-facebook" role="tabpanel" aria-labelledby="post-engagement-from-facebook-tab">
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="row" colspan="3">
                                                                                            <input type="text" class="form-control post-engagement-search-posts" placeholder="Search posts">
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" colspan="2">
                                                                                            <?php echo $this->lang->line('post'); ?>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            <?php echo $this->lang->line('actions'); ?>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="post-engagement-from-instagram" role="tabpanel" aria-labelledby="post-engagement-from-instagram-tab">
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="row" colspan="3">
                                                                                            <input type="text" class="form-control post-engagement-search-posts" placeholder="Search posts">
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" colspan="2">
                                                                                            <?php echo $this->lang->line('post'); ?>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            <?php echo $this->lang->line('actions'); ?>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-preview ad-preview-display">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_preview'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton15" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton15">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-fb-desktop-feed">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-instagram-feed">
                                                                                    <i class="icon-social-instagram"></i>
                                                                                    <?php echo $this->lang->line('instagram_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-messenger-inbox">
                                                                                    <i class="fab fa-facebook-messenger"></i>
                                                                                    <?php echo $this->lang->line('messenger_inbox'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 ad-creation-options pixel-conversion-tracking">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('tracking'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">

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
                <div class="alerts-display-reports">
                </div>
                <div class="row-input">
                    <div class="row clean">
                        <div class="col-6">
                        </div>
                        <div class="col-6">
                            <button type="submit" class="ads-save-campaign">
                                <i class="far fa-save"></i> <?php echo $this->lang->line('save_campaign'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Create Ad Set -->
<div class="modal fade" id="ads-create-ad-set" tabindex="-1" role="dialog" aria-labelledby="ads-create-ad-set-tab" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/facebook-ads', array('class' => 'facebook-ads-create-ad-set', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-tab-stream-react">
                            <?php echo $this->lang->line('create_ad_set'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="row-input">
                    <div class="panel-group wrap" id="bs-collapse">
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#adset-select-campaign" class="opened-box">
                                        <span>
                                            1
                                        </span>
                                        <?php echo $this->lang->line('select_ad_campaign'); ?>
                                        <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="adset-select-campaign" class="panel-collapse collapse in collapse show">
                                <div class="panel-body">
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('ad_campaign'); ?> <em>(<?php echo $this->lang->line('required'); ?>)</em>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('ad_campaign_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-selected-ad-campaign btn-select" type="button" id="dropdownMenuButton16" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('ad_campaigns'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-select-ad-campaign" aria-labelledby="dropdownMenuButton16" x-placement="bottom-start">
                                                            <div class="card">
                                                                <div class="card-head"><input type="text" class="ad-creation-filter-fb-campaigns" placeholder="<?php echo $this->lang->line('search_for_campaigns'); ?>"></div>
                                                                <div class="card-body">
                                                                    <ul class="list-group ad-creation-filter-fb-campaigns-list">
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#adset-create-ad-set">
                                        <span>
                                            2
                                        </span>
                                        <?php echo $this->lang->line('create_ad_set'); ?>
                                        <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="adset-create-ad-set" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('ad_set_name'); ?> <em>(<?php echo $this->lang->line('required'); ?>)</em>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('ad_set_name_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-adset-name" placeholder="<?php echo $this->lang->line('enter_ad_set_name'); ?>">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('optimization_goal'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('optimization_goal_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-ad-set-optimization-goal btn-select" data-id="IMPRESSIONS" type="button" id="dropdownMenuButton17" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('impressions'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-ad-set-optimization-goal-list" aria-labelledby="dropdownMenuButton17">
                                                            <a class="dropdown-item" href="#" data-id="IMPRESSIONS"><?php echo $this->lang->line('impressions'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="LINK_CLICKS"><?php echo $this->lang->line('link_clicks'); ?></a>
                                                            <a class="dropdown-item" href="#" data-id="REACH"><?php echo $this->lang->line('daily_unique_reach'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('billing_event'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('billing_event_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-ad-set-billing-event btn-select" data-id="IMPRESSIONS" type="button" id="dropdownMenuButton18" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('impressions'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-ad-set-billing-event-list" aria-labelledby="dropdownMenuButton18">
                                                            <a class="dropdown-item" href="#" data-id="IMPRESSIONS"><?php echo $this->lang->line('impressions'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('placement'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton19" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('all_placements'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-adset-ad-set-placements" aria-labelledby="dropdownMenuButton19">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Facebook</h5>
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('feeds'); ?>
                                                                            <input type="checkbox" id="ad-set-placement-facebook-feeds2" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Instagram</h5>
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('feed'); ?>
                                                                            <input type="checkbox" id="ad-set-placement-instagram-feed2" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Messenger</h5>
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('inbox'); ?>
                                                                            <input type="checkbox" id="ad-set-placement-messenger-inbox2" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('target_cost'); ?> <span class="ad_set_target_cost"></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('target_cost_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-adset-target-cost" placeholder="<?php echo $this->lang->line('enter_ad_set_target_cost'); ?>">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('daily_budget'); ?> <span class="ad_set_daily_budget"></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('daily_budget_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-adset-daily-budget" placeholder="<?php echo $this->lang->line('enter_ad_set_daily_budget'); ?>">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('countries'); ?> <span class="ad_set_default_country"></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('select_countries_supported'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="show-more-content">
                                                        <a data-toggle="collapse" href="#adset_creation_show_select_more_countries" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                                                            <?php echo $this->lang->line('show_countries_list'); ?>
                                                        </a>
                                                    </p>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="collapse select-countries multi-collapse row" id="adset_creation_show_select_more_countries">
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-africa2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('africa'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-africa2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('burundi'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-burundi2" data-id="BI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('comoros'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-comoros2" data-id="KM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ghana'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ghana2" data-id="GH">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('eritrea'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-eritrea2" data-id="ER">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ethiopia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ethiopia2" data-id="ET">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kenya'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kenya2" data-id="KE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('madagascar'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-madagascar2" data-id="MG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('malawi'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-malawi2" data-id="MW">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mauritius'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mauritius2" data-id="MU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mayotte'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mayotte2" data-id="YT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mozambique'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mozambique2" data-id="MZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('reunion'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-reunion2" data-id="RE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('rwanda'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-rwanda2" data-id="RW">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('seychelles'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-seychelles2" data-id="SC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('uganda'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-uganda2" data-id="UG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('tanzania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-tanzania2" data-id="TZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('zambia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-zambia2" data-id="ZM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('angola'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-angola2" data-id="AO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('cameroon'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-cameroon2" data-id="CM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guinea'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guinea2" data-id="PG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('algeria'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-algeria2" data-id="DZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('egypt'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-egypt2" data-id="EG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('morocco'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-morocco2" data-id="MA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('tunisia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-tunisia2" data-id="TN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('south_africa'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-south_africa2" data-id="ZA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('nigeria'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-nigeria2" data-id="NG">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-asia2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('asia'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-asia2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kazakhstan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kazakhstan2" data-id="KZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kyrgyzstan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kyrgyzstan2" data-id="KG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('tajikistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-tajikistan2" data-id="TJ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('turkmenistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-turkmenistan2" data-id="TM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('uzbekistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-uzbekistan2" data-id="UZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('china'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-china2" data-id="CN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('hong_kong'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-hong_kong2" data-id="HK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('japan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-japan2" data-id="JP">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bangladesh'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bangladesh2" data-id="BD">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('india'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-india2" data-id="IN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('nepal'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-nepal2" data-id="NP">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('pakistan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-pakistan2" data-id="PK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('indonesia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-indonesia2" data-id="ID">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('malaysia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-malaysia2" data-id="MY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('philippines'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-philippines2" data-id="PH">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('singapore'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-singapore2" data-id="SG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('thailand'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-thailand2" data-id="TH">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('israel'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-israel2" data-id="IL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('jordan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-jordan2" data-id="JO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('kuwait'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-kuwait2" data-id="KW">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('qatar'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-qatar2" data-id="QA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('saudi_arabia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-saudi_arabia2" data-id="SA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('united_arab_emirates'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-united_arab_emirates2" data-id="AE">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-central-america2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('central_america'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-central-america2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('belize'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-belize2" data-id="BZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('costa_rica'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-costa_rica2" data-id="CR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('el_salvador'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-el_salvador2" data-id="SV">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guatemala'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guatemala2" data-id="GT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('honduras'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-honduras2" data-id="HN">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('mexico'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-mexico2" data-id="MX">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('nicaragua'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-nicaragua2" data-id="NI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('panama'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-panama2" data-id="PA">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-baltic_states2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('baltic_states'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-baltic_states2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('estonia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-estonia2" data-id="EE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('latvia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-latvia2" data-id="LV">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('lithuania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-lithuania2" data-id="LT">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-caucasus2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('caucasus'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-caucasus2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('azerbaijan'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-azerbaijan2" data-id="AZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('georgia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-georgia2" data-id="GE">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-southeastern2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('southeastern'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-southeastern2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('belarus'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-belarus2" data-id="BY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bulgaria'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bulgaria2" data-id="BG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('moldova'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-moldova2" data-id="MD">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('romania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-romania2" data-id="RO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('russia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-russia2" data-id="RU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ukraine'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ukraine2" data-id="UA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('albania'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-albania2" data-id="AL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('croatia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-croatia2" data-id="HR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('greece'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-greece2" data-id="GR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('italy'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-italy2" data-id="IT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('malta'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-malta2" data-id="MT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('montenegro'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-montenegro2" data-id="ME">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('serbia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-serbia2" data-id="RS">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('slovenia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-slovenia2" data-id="SI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('macedonia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-macedonia2" data-id="MK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('turkey'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-turkey2" data-id="TR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('cyprus'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-cyprus2" data-id="CY">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-central2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('central'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-central2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('czech_republic'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-czech_republic2" data-id="CZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('hungary'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-hungary2" data-id="HU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('poland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-poland2" data-id="PL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('slovakia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-slovakia2" data-id="SK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('germany'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-germany2" data-id="DE">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-europe-western2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('europe'); ?> (<em><?php echo $this->lang->line('western'); ?></em>)
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-europe-western2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('denmark'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-denmark2" data-id="DK">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('finland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-finland2" data-id="FI">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('iceland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-iceland2" data-id="IS">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('greenland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-greenland2" data-id="GL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ireland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ireland2" data-id="IE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('norway'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-norway2" data-id="NO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('sweden'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-sweden2" data-id="SE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('united_kingdom'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-united_kingdom2" data-id="GB">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('portugal'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-portugal2" data-id="PT">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('spain'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-spain2" data-id="ES">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('belgium'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-belgium2" data-id="BE">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('france'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-france2" data-id="FR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('luxembourg'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-luxembourg2" data-id="LU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('monaco'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-monaco2" data-id="MC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('netherlands'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-netherlands2" data-id="NL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('switzerland'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-switzerland2" data-id="CH">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-north_america2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('north_america'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-north_america2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bermuda'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bermuda2" data-id="BM">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('canada'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-canada2" data-id="CA">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('united_states'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-united_states2" data-id="US">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-oceania2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('oceania'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-oceania2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('australia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-australia2" data-id="AU">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('new_zealand'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-new_zealand2" data-id="NZ">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('new_caledonia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-new_caledonia2" data-id="NC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('papua_new_guinea'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-papua_new_guinea2" data-id="PG">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guam'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guam2" data-id="GU">
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-campaign-set-countries-south_america2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <?php echo $this->lang->line('south_america'); ?>
                                                                        </button>
                                                                        <div class="dropdown-menu ads-campaign-dropdown ads-campaign-set-countries-list" aria-labelledby="ads-campaign-set-countries-south_america2">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <ul class="list-group">
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('argentina'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-argentina2" data-id="AR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('bolivia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-bolivia2" data-id="BO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('brazil'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-brazil2" data-id="BR">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('chile'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-chile2" data-id="CL">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('colombia'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-colombia2" data-id="CO">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('ecuador'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-ecuador2" data-id="EC">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('guyana'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-guyana2" data-id="GY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('paraguay'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-paraguay2" data-id="PY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('uruguay'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-uruguay2" data-id="UY">
                                                                                        </li>
                                                                                        <li class="list-group-item">
                                                                                            <?php echo $this->lang->line('venezuela'); ?>
                                                                                            <input type="checkbox" id="ads-campaign-set-countries-list-venezuela2" data-id="VE">
                                                                                        </li>
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
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('regions'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('regions_description'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 select-regions select-input-search">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('cities'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('cities_description'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 select-cities select-input-search">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('genders'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('genders_select'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdown-set-genders" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('all_genders'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-ad-set-ad-genders" aria-labelledby="dropdown-set-genders">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('female'); ?>
                                                                            <input type="checkbox" id="ad-set-gender-female" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('male'); ?>
                                                                            <input type="checkbox" id="ad-set-gender-male" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('age'); ?> <span><em>(<?php echo $this->lang->line('default_age_any'); ?>)</em></span>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('age_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-ad-set-age-from btn-select" data-id="0" type="button" id="dropdown-set-age-from" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('age_from'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-ad-set-age-from-list" aria-labelledby="dropdown-set-age-from">
                                                            <a class="dropdown-item" href="#" data-id="18">18</a>
                                                            <a class="dropdown-item" href="#" data-id="20">20</a>
                                                            <a class="dropdown-item" href="#" data-id="25">25</a>
                                                            <a class="dropdown-item" href="#" data-id="30">30</a>
                                                            <a class="dropdown-item" href="#" data-id="35">35</a>
                                                            <a class="dropdown-item" href="#" data-id="40">40</a>
                                                            <a class="dropdown-item" href="#" data-id="45">45</a>
                                                            <a class="dropdown-item" href="#" data-id="50">50</a>
                                                            <a class="dropdown-item" href="#" data-id="55">55</a>
                                                            <a class="dropdown-item" href="#" data-id="60">60</a>
                                                            <a class="dropdown-item" href="#" data-id="65">65</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-ad-set-age-to btn-select" data-id="0" type="button" id="dropdown-set-age-to" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('age_to'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-ad-set-age-to-list" aria-labelledby="dropdown-set-age-to">
                                                            <a class="dropdown-item" href="#" data-id="20">20</a>
                                                            <a class="dropdown-item" href="#" data-id="25">25</a>
                                                            <a class="dropdown-item" href="#" data-id="30">30</a>
                                                            <a class="dropdown-item" href="#" data-id="35">35</a>
                                                            <a class="dropdown-item" href="#" data-id="40">40</a>
                                                            <a class="dropdown-item" href="#" data-id="45">45</a>
                                                            <a class="dropdown-item" href="#" data-id="50">50</a>
                                                            <a class="dropdown-item" href="#" data-id="55">55</a>
                                                            <a class="dropdown-item" href="#" data-id="60">60</a>
                                                            <a class="dropdown-item" href="#" data-id="65">65</a>
                                                            <a class="dropdown-item" href="#" data-id="70">70</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('device_types'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('device_types_description'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdown-devices2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('all_devices'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-set-select-type" aria-labelledby="dropdown-devices2">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('mobile'); ?>
                                                                            <input type="checkbox" id="ad-set-mobile-type" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <?php echo $this->lang->line('desktop'); ?>
                                                                            <input type="checkbox" id="ad-set-desktop-type" checked="checked">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#adset-create-ads">
                                        <span>
                                            3
                                        </span>
                                        <?php echo $this->lang->line('create_ad'); ?>
                                        <em class="required">(<?php echo $this->lang->line('optional'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="adset-create-ads" class="panel-collapse collapse">
                                <div class="tab-content" id="myTabContent6">
                                    <div class="tab-pane fade show active" id="campaign-create-ads-links" role="tabpanel" aria-labelledby="campaign-create-ads-links-tab">
                                        <div class="panel-body">
                                            <div class="row clean">
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-options">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_content'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="ad_name"><?php echo $this->lang->line('ad_name'); ?> <em>(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="ad_name">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 links-clicks-preview-settings text-center">
                                                                        <hr>
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton20" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="icon-picture"></i>
                                                                                <?php echo $this->lang->line('image'); ?> <em class="required">(<?php echo $this->lang->line('required_for_instagram'); ?>)</em>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton20">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-settings-photo">
                                                                                    <i class="icon-picture"></i>
                                                                                    <?php echo $this->lang->line('image'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 text-center">
                                                                        <div class="tab-content" id="links-clicks-preview-settings">
                                                                            <div class="tab-pane fade show active" id="links-clicks-preview-settings-photo" role="tabpanel" aria-labelledby="links-clicks-preview-settings-photo-tab">
                                                                                <div class="row">
                                                                                    <div class="col-xl-12">
                                                                                        <button type="button" class="btn btn-primary btn-upload upload-ads-image" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                                            <i class="icon-cloud-upload"></i>
                                                                                            <?php echo $this->lang->line('select_image'); ?>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row ads-uploaded-photo">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="text"><?php echo $this->lang->line('text'); ?> <em>(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <textarea id="text" class="form-control text"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="website_url"><?php echo $this->lang->line('website_url'); ?> <em>(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="website_url">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="show-more-content">
                                                                            <a data-toggle="collapse" href="#adset_creation_show_ads_advanced" role="button" aria-expanded="false" aria-controls="multiCollapseExample2">
                                                                                <?php echo $this->lang->line('show_advanced_options'); ?>
                                                                            </a>
                                                                        </p>
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <div class="collapse multi-collapse" id="adset_creation_show_ads_advanced">
                                                                                    <div class="row">
                                                                                        <div class="col-12">
                                                                                            <label for="headline"><?php echo $this->lang->line('headline'); ?></label>
                                                                                            <input type="text" class="headline">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-12">
                                                                                            <label for="description"><?php echo $this->lang->line('news_feed_link_description'); ?></label>
                                                                                            <textarea id="text" class="form-control description"></textarea>
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
                                                    <div class="col-12 ad-creation-options ad-identity">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-preview ad-preview-display">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_preview'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton21" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton21">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-fb-desktop-feed">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-instagram-feed">
                                                                                    <i class="icon-social-instagram"></i>
                                                                                    <?php echo $this->lang->line('instagram_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-messenger-inbox">
                                                                                    <i class="fab fa-facebook-messenger"></i>
                                                                                    <?php echo $this->lang->line('messenger_inbox'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 ad-creation-options pixel-conversion-tracking">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('tracking'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="campaign-create-ads-post-engagement" role="tabpanel" aria-labelledby="campaign-create-ads-links-tab">
                                        <div class="panel-body">
                                            <div class="row clean">
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-options ad-identity">
                                                    </div>
                                                    <div class="col-12 ad-creation-options post-engagement-list">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('posts'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton22" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                Facebook
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton22">
                                                                                <a class="dropdown-item" href="#post-engagement-from-facebook">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    Facebook
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="ad_name"><?php echo $this->lang->line('ad_name'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="ad_name">
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="tab-content" id="post-engagement-list">
                                                                    <div class="tab-pane fade show active" id="post-engagement-from-facebook" role="tabpanel" aria-labelledby="post-engagement-from-facebook-tab">
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="row" colspan="3">
                                                                                            <input type="text" class="form-control post-engagement-search-posts" placeholder="Search posts">
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" colspan="2">
                                                                                            <?php echo $this->lang->line('post'); ?>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            <?php echo $this->lang->line('actions'); ?>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="post-engagement-from-instagram" role="tabpanel" aria-labelledby="post-engagement-from-instagram-tab">
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="row" colspan="3">
                                                                                            <input type="text" class="form-control post-engagement-search-posts" placeholder="Search posts">
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" colspan="2">
                                                                                            <?php echo $this->lang->line('post'); ?>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            <?php echo $this->lang->line('actions'); ?>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-preview ad-preview-display">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_preview'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton23" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton23">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-fb-desktop-feed">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-instagram-feed">
                                                                                    <i class="icon-social-instagram"></i>
                                                                                    <?php echo $this->lang->line('instagram_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-messenger-inbox">
                                                                                    <i class="fab fa-facebook-messenger"></i>
                                                                                    <?php echo $this->lang->line('messenger_inbox'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 ad-creation-options pixel-conversion-tracking">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('tracking'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">

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
                <div class="alerts-display-reports">
                </div>
                <div class="row-input">
                    <div class="row clean">
                        <div class="col-6">
                        </div>
                        <div class="col-6">
                            <button type="submit" class="ads-save-ad-set">
                                <i class="far fa-save"></i> <?php echo $this->lang->line('save_ad_set'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Create New Ad -->
<div class="modal fade" id="ads-create-new-ad" tabindex="-1" role="dialog" aria-labelledby="ads-create-new-ad-tab" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/facebook-ads', array('class' => 'facebook-ads-create-new-ad', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-tab-stream-react">
                            <?php echo $this->lang->line('create_new_ad'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="row-input">
                    <div class="panel-group wrap" id="bs-collapse">
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#ads-select-campaign" class="opened-box">
                                        <span>
                                            1
                                        </span>
                                        <?php echo $this->lang->line('select_ad_campaign'); ?>
                                        <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="ads-select-campaign" class="panel-collapse collapse in collapse show">
                                <div class="panel-body">
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('ad_campaign'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('ad_campaign_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-selected-ad-campaign btn-select" type="button" id="dropdownMenuButton24" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('ad_campaigns'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-select-ad-campaign" aria-labelledby="dropdownMenuButton24" x-placement="bottom-start">
                                                            <div class="card">
                                                                <div class="card-head"><input type="text" class="ad-creation-filter-fb-campaigns" placeholder="<?php echo $this->lang->line('search_for_campaigns'); ?>"></div>
                                                                <div class="card-body">
                                                                    <ul class="list-group ad-creation-filter-fb-campaigns-list">
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#ads-select-ad-set">
                                        <span>
                                            2
                                        </span>
                                        <?php echo $this->lang->line('select_ad_set'); ?>
                                        <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="ads-select-ad-set" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('ad_set'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('ad_set_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle ads-selected-ad-set btn-select" type="button" id="dropdownMenuButton25" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php echo $this->lang->line('ad_sets'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-select-ad-campaign" aria-labelledby="dropdownMenuButton25" x-placement="bottom-start">
                                                            <div class="card">
                                                                <div class="card-head">
                                                                    <input type="text" class="ad-creation-filter-fb-adsets" placeholder="<?php echo $this->lang->line('search_for_adsets'); ?>">
                                                                </div>
                                                                <div class="card-body">
                                                                    <ul class="list-group ad-creation-filter-fb-adsets-list">
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#" href="#ads-create-ads">
                                        <span>
                                            3
                                        </span>
                                        <?php echo $this->lang->line('create_ad'); ?>
                                        <em class="required">(<?php echo $this->lang->line('required'); ?>)</em>
                                    </a>
                                </h4>
                            </div>
                            <div id="ads-create-ads" class="panel-collapse collapse">
                                <div class="tab-content" id="myTabContent7">
                                    <div class="tab-pane fade show active" id="campaign-create-ads-links" role="tabpanel" aria-labelledby="campaign-create-ads-links-tab">
                                        <div class="panel-body">
                                            <div class="row clean">
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-options">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_content'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="ad_name"><?php echo $this->lang->line('ad_name'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="ad_name">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 links-clicks-preview-settings text-center">
                                                                        <hr>
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton26" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="icon-picture"></i>
                                                                                <?php echo $this->lang->line('image'); ?> <em class="required">(<?php echo $this->lang->line('required_for_instagram'); ?>)</em>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton26">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-settings-photo">
                                                                                    <i class="icon-picture"></i>
                                                                                    <?php echo $this->lang->line('image'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 text-center">
                                                                        <div class="tab-content" id="links-clicks-preview-settings">
                                                                            <div class="tab-pane fade show active" id="links-clicks-preview-settings-photo" role="tabpanel" aria-labelledby="links-clicks-preview-settings-photo-tab">
                                                                                <div class="row">
                                                                                    <div class="col-xl-12">
                                                                                        <button type="button" class="btn btn-primary btn-upload upload-ads-image" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                                            <i class="icon-cloud-upload"></i>
                                                                                            <?php echo $this->lang->line('select_image'); ?>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row ads-uploaded-photo">
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="links-clicks-preview-settings-video" role="tabpanel" aria-labelledby="links-clicks-preview-settings-video-tab">
                                                                                <div class="row">
                                                                                    <div class="col-xl-12">
                                                                                        <button type="button" class="btn btn-primary btn-upload upload-ads-video" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                                            <i class="icon-cloud-upload"></i>
                                                                                            <?php echo $this->lang->line('select_video'); ?>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row ads-uploaded-video">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="text"><?php echo $this->lang->line('text'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <textarea id="text" class="form-control text"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="website_url"><?php echo $this->lang->line('website_url'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="website_url">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="show-more-content">
                                                                            <a data-toggle="collapse" href="#ads_creation_show_ads_advanced" role="button" aria-expanded="false" aria-controls="multiCollapseExample2">
                                                                                <?php echo $this->lang->line('show_advanced_options'); ?>
                                                                            </a>
                                                                        </p>
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <div class="collapse multi-collapse" id="ads_creation_show_ads_advanced">
                                                                                    <div class="row">
                                                                                        <div class="col-12">
                                                                                            <label for="headline"><?php echo $this->lang->line('headline'); ?></label>
                                                                                            <input type="text" class="headline">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-12">
                                                                                            <label for="description"><?php echo $this->lang->line('news_feed_link_description'); ?></label>
                                                                                            <textarea id="text" class="form-control description"></textarea>
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
                                                    <div class="col-12 ad-creation-options ad-identity">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-preview ad-preview-display">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_preview'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton27" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton27">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-fb-desktop-feed">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-instagram-feed">
                                                                                    <i class="icon-social-instagram"></i>
                                                                                    <?php echo $this->lang->line('instagram_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-messenger-inbox">
                                                                                    <i class="fab fa-facebook-messenger"></i>
                                                                                    <?php echo $this->lang->line('messenger_inbox'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 ad-creation-options pixel-conversion-tracking">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('tracking'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="campaign-create-ads-post-engagement" role="tabpanel" aria-labelledby="campaign-create-ads-links-tab">
                                        <div class="panel-body">
                                            <div class="row clean">
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-options ad-identity">
                                                    </div>
                                                    <div class="col-12 ad-creation-options post-engagement-list">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('posts'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton28" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                Facebook
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton28">
                                                                                <a class="dropdown-item" href="#post-engagement-from-facebook">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    Facebook
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="ad_name"><?php echo $this->lang->line('ad_name'); ?> <em class="required">(<?php echo $this->lang->line('required'); ?>)</em></label>
                                                                        <input type="text" class="ad_name">
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="tab-content" id="post-engagement-list">
                                                                    <div class="tab-pane fade show active" id="post-engagement-from-facebook" role="tabpanel" aria-labelledby="post-engagement-from-facebook-tab">
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="row" colspan="3">
                                                                                            <input type="text" class="form-control post-engagement-search-posts" placeholder="Search posts">
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" colspan="2">
                                                                                            <?php echo $this->lang->line('post'); ?>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            <?php echo $this->lang->line('actions'); ?>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="post-engagement-from-instagram" role="tabpanel" aria-labelledby="post-engagement-from-instagram-tab">
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="row" colspan="3">
                                                                                            <input type="text" class="form-control post-engagement-search-posts" placeholder="Search posts">
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="col" colspan="2">
                                                                                            <?php echo $this->lang->line('post'); ?>
                                                                                        </th>
                                                                                        <th scope="col">
                                                                                            <?php echo $this->lang->line('actions'); ?>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12 ad-creation-preview ad-preview-display">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('ad_preview'); ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="dropdownMenuButton29" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fab fa-facebook"></i>
                                                                                <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                            </button>
                                                                            <div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton29">
                                                                                <a class="dropdown-item" href="#links-clicks-preview-fb-desktop-feed">
                                                                                    <i class="fab fa-facebook"></i>
                                                                                    <?php echo $this->lang->line('desktop_news_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-instagram-feed">
                                                                                    <i class="icon-social-instagram"></i>
                                                                                    <?php echo $this->lang->line('instagram_feed'); ?>
                                                                                </a>
                                                                                <a class="dropdown-item" href="#links-clicks-preview-messenger-inbox">
                                                                                    <i class="fab fa-facebook-messenger"></i>
                                                                                    <?php echo $this->lang->line('messenger_inbox'); ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 ad-creation-options pixel-conversion-tracking">
                                                        <div class="panel">
                                                            <div class="panel-heading">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="panel-title">
                                                                            <?php echo $this->lang->line('tracking'); ?>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">

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
                <div class="alerts-display-reports">
                </div>
                <div class="row-input">
                    <div class="row clean">
                        <div class="col-6">
                        </div>
                        <div class="col-6">
                            <button type="submit" class="ads-save-ad-set">
                                <i class="far fa-save"></i> <?php echo $this->lang->line('save_ad'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Create Pixel's conversion-->
<div class="modal fade" id="pixel-new-coversion" tabindex="-1" role="dialog" aria-labelledby="pixel-new-coversion-tab" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/facebook-ads', array('class' => 'facebook-ads-create-pixel-conversion', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-tab-stream-react">
                            <?php echo $this->lang->line('create_pixel_coversion'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="row-input">
                    <div class="panel-group wrap" id="bs-collapse">
                        <div class="panel">
                            <div class="panel-collapse collapse in collapse show">
                                <div class="panel-body">
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('conversion_name'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('conversion_name_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-pixel-conversion-name" placeholder="<?php echo $this->lang->line('enter_conversion_name'); ?>" required>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('conversion_url'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('conversion_url_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control ads-pixel-conversion-url" placeholder="<?php echo $this->lang->line('enter_conversion_url'); ?>" required>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3>
                                                        <?php echo $this->lang->line('conversion_type'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo $this->lang->line('conversion_type_description'); ?>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle btn-select" type="button" id="ads-select-conversion-type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('select_type'); ?>
                                                        </button>
                                                        <div class="dropdown-menu ads-campaign-dropdown ads-pixel-conversion-type" aria-labelledby="ads-select-conversion-type">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="CONTENT_VIEW">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('view_content'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="SEARCH">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('search'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="ADD_TO_CART">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('add_to_cart'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="ADD_TO_WISHLIST">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('add_to_wishlist'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="INITIATED_CHECKOUT">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('initiate_checkout'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="ADD_PAYMENT_INFO">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('add_payment_info'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="PURCHASE">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('purchase'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="LEAD">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('lead'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <a class="dropdown-item" href="#" data-id="COMPLETE_REGISTRATION">
                                                                                <i class="far fa-arrow-alt-circle-right"></i> <?php echo $this->lang->line('complete_registration'); ?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-input">
                    <div class="row clean">
                        <div class="col-6">
                        </div>
                        <div class="col-6">
                            <button type="submit" class="ads-pixel-save-conversion">
                                <i class="far fa-save"></i> <?php echo $this->lang->line('save_conversion'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Campaign Insights Modal -->
<div class="modal fade" id="ads-campaigns-insights" tabindex="-1" role="dialog" aria-labelledby="ads-campaigns-insights" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="ads-campaigns-insights-show-tab" data-toggle="tab" href="#ads-campaigns-insights-show" role="tab" aria-controls="ads-campaigns-insights-show" aria-selected="true">
                            <i class="icon-graph"></i>
                            <?php echo $this->lang->line('campaigns_insights'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="ads-campaigns-insights-show" role="tabpanel" aria-labelledby="ad-labels-generate-report">
                        <?php echo form_open('user/app/facebook-ads', array('class' => 'ads-campaign-insights', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="row">
                            <div class="col-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md ads-campaign-insights-by-time dropdown-toggle" data-time="3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo $this->lang->line('last_30_days'); ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <div class="card">
                                            <div class="card-body">
                                                <ul class="list-group ads-campaign-insights-by-time-list">
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
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md ads-campaign-insights-by-campaign dropdown-toggle" href="#" role="button" id="dropdownMenuButton30" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-basket-loaded"></i>
                                        <?php echo $this->lang->line('campaigns'); ?>
                                    </a>
                                    <div class="dropdown-menu ads-insights-select-ad-campaign" aria-labelledby="dropdownMenuButton30" x-placement="bottom-start">
                                        <div class="card">
                                            <div class="card-head"><input type="text" class="ads-insights-filter-fb-campaigns" placeholder="<?php echo $this->lang->line('search_for_campaigns'); ?>"></div>
                                            <div class="card-body">
                                                <ul class="list-group ad-insights-fb-campaigns-list">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                &nbsp;
                            </div>
                            <div class="col-3">
                                <div class="btn-group" role="group" aria-label="Insights Show">
                                    <button type="submit" class="btn btn-default btn-show-reports">
                                        <i class="icon-refresh"></i>
                                        <?php echo $this->lang->line('show_insights'); ?>
                                    </button>
                                    <button type="button" class="btn btn-default btn-insights-campaign-download">
                                        <i class="icon-cloud-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('date'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('impressions'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('reach'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('clicks'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('cpm'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('cpc'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('ctr'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('spent'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="8">
                                                    <?php echo $this->lang->line('no_insights_found'); ?>
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

<!-- Ad Set Insights Modal -->
<div class="modal fade" id="ads-ad-sets-insights" tabindex="-1" role="dialog" aria-labelledby="ads-ad-sets-insights" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="ads-ad-sets-insights-show-tab" data-toggle="tab" href="#ads-ad-sets-insights-show" role="tab" aria-controls="ads-ad-sets-insights-show" aria-selected="true">
                            <i class="icon-wallet"></i>
                            <?php echo $this->lang->line('ad_sets_insights'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="ads-ad-sets-insights-show" role="tabpanel" aria-labelledby="ads-ad-sets-insights-show">
                        <?php echo form_open('user/app/facebook-ads', array('class' => 'ads-ad-sets-insights', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="row">
                            <div class="col-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md ads-ad-sets-insights-by-time dropdown-toggle" data-time="3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo $this->lang->line('last_30_days'); ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <div class="card">
                                            <div class="card-body">
                                                <ul class="list-group ads-ad-sets-insights-by-time-list">
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
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md ads-campaign-insights-by-ad-sets dropdown-toggle" href="#" role="button" id="dropdownMenuButton31" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-wallet"></i>
                                        <?php echo $this->lang->line('ad_sets'); ?>
                                    </a>
                                    <div class="dropdown-menu ads-insights-select-ad-ad-sets" aria-labelledby="dropdownMenuButton31" x-placement="bottom-start">
                                        <div class="card">
                                            <div class="card-head"><input type="text" class="ads-insights-filter-fb-ad-sets" placeholder="<?php echo $this->lang->line('search_for_adsets'); ?>"></div>
                                            <div class="card-body">
                                                <ul class="list-group ads-campaign-insights-by-ad-sets-list">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                &nbsp;
                            </div>
                            <div class="col-3">
                                <div class="btn-group" role="group" aria-label="Insights Show">
                                    <button type="submit" class="btn btn-default btn-show-reports">
                                        <i class="icon-refresh"></i>
                                        <?php echo $this->lang->line('show_insights'); ?>
                                    </button>
                                    <button type="button" class="btn btn-default btn-insights-ad-set-download">
                                        <i class="icon-cloud-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('date'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('impressions'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('reach'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('clicks'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('cpm'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('cpc'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('ctr'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('spent'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="8">
                                                    <?php echo $this->lang->line('no_insights_found'); ?>
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

<!-- Ad Insights Modal -->
<div class="modal fade" id="ads-ad-insights" tabindex="-1" role="dialog" aria-labelledby="ads-ad-insights" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="ads-ad-insights-show-tab" data-toggle="tab" href="#ads-ad-insights-show" role="tab" aria-controls="ads-ad-insights-show" aria-selected="true">
                            <i class="icon-puzzle"></i>
                            <?php echo $this->lang->line('ad_insights'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="ads-ad-insights-show" role="tabpanel" aria-labelledby="ads-ad-insights-show">
                        <?php echo form_open('user/app/facebook-ads', array('class' => 'ads-ad-insights', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
                        <div class="row">
                            <div class="col-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md ads-ad-insights-by-time dropdown-toggle" data-time="3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo $this->lang->line('last_30_days'); ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <div class="card">
                                            <div class="card-body">
                                                <ul class="list-group ads-ad-insights-by-time-list">
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
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-md ads-campaign-insights-by-ad dropdown-toggle" href="#" role="button" id="dropdownMenuButton32" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-puzzle"></i>
                                        <?php echo $this->lang->line('ads'); ?>
                                    </a>
                                    <div class="dropdown-menu ads-insights-select-ads" aria-labelledby="dropdownMenuButton32" x-placement="bottom-start">
                                        <div class="card">
                                            <div class="card-head">
                                                <input type="text" class="ads-insights-filter-fb-ad" placeholder="<?php echo $this->lang->line('search_for_ads'); ?>">
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group ads-campaign-insights-by-ad-list">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                &nbsp;
                            </div>
                            <div class="col-3">
                                <div class="btn-group" role="group" aria-label="Insights Show">
                                    <button type="submit" class="btn btn-default btn-show-reports">
                                        <i class="icon-refresh"></i>
                                        <?php echo $this->lang->line('show_insights'); ?>
                                    </button>
                                    <button type="button" class="btn btn-default btn-insights-ad-download">
                                        <i class="icon-cloud-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('date'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('impressions'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('reach'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('clicks'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('cpm'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('cpc'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('ctr'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('spent'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="8">
                                                    <?php echo $this->lang->line('no_insights_found'); ?>
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

<?php facebook_ads_automatizations_modals(); ?>

<!-- Planner modal !-->
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
</div>

<!--Upload image form !-->
<?php
$attributes = array('class' => 'upim d-none', 'id' => 'upim', 'method' => 'post', 'data-csrf' => $this->security->get_csrf_token_name());
echo form_open_multipart('user/app/posts', $attributes);
?>
<input type="hidden" name="type" id="type" value="video">
<input type="file" name="file" id="file" accept=".gif,.jpg,.jpeg,.png,.mp4,.avi">
<?php echo form_close(); ?>

<!-- Translations !-->
<script language="javascript">
    var words = {
        please_select_a_campaign: "<?php echo $this->lang->line('please_select_at_least_campaign'); ?>",
        no_campaigns_found: "<?php echo $this->lang->line('no_campaigns_found'); ?>",
        no_adsets_found: "<?php echo $this->lang->line('no_adsets_found'); ?>",
        no_ads_found: "<?php echo $this->lang->line('no_ads_found'); ?>",
        please_select_an_ad_sets: "<?php echo $this->lang->line('please_select_an_ad_sets'); ?>",
        please_select_an_ad: "<?php echo $this->lang->line('please_select_an_ad'); ?>",
        please_enter_valid_url: "<?php echo $this->lang->line('please_enter_valid_url'); ?>",
        like: "<?php echo $this->lang->line('like'); ?>",
        comment: "<?php echo $this->lang->line('comment'); ?>",
        share: "<?php echo $this->lang->line('share'); ?>",
        sponsored: "<?php echo $this->lang->line('sponsored'); ?>",
        learn_more: "<?php echo $this->lang->line('learn_more'); ?>",
        please_enter_website_url: "<?php echo $this->lang->line('please_enter_website_url'); ?>",
        please_select_conversion_type: "<?php echo $this->lang->line('please_select_conversion_type'); ?>",
        select_type: "<?php echo $this->lang->line('select_type'); ?>",
        no_conversion_tracking_found: "<?php echo $this->lang->line('no_conversion_tracking_found'); ?>",
        view_content: "<?php echo $this->lang->line('view_content'); ?>",
        search: "<?php echo $this->lang->line('search'); ?>",
        add_to_cart: "<?php echo $this->lang->line('add_to_cart'); ?>",
        add_to_wishlist: "<?php echo $this->lang->line('add_to_wishlist'); ?>",
        initiate_checkout: "<?php echo $this->lang->line('initiate_checkout'); ?>",
        add_payment_info: "<?php echo $this->lang->line('add_payment_info'); ?>",
        purchase: "<?php echo $this->lang->line('purchase'); ?>",
        lead: "<?php echo $this->lang->line('lead'); ?>",
        complete_registration: "<?php echo $this->lang->line('complete_registration'); ?>",
        your_facebook_pixel_account: "<?php echo $this->lang->line('your_facebook_pixel_account'); ?>",
        conversion_tracking: "<?php echo $this->lang->line('conversion_tracking'); ?>",
        select_a_conversion_tracking: "<?php echo $this->lang->line('select_a_conversion_tracking'); ?>",
        search_pixel_conversions: "<?php echo $this->lang->line('search_pixel_conversions'); ?>",
        please_select_ad_campaign: "<?php echo $this->lang->line('please_select_ad_campaign'); ?>",
        please_select_ad_set: "<?php echo $this->lang->line('please_select_ad_set'); ?>",
        ad_campaigns: "<?php echo $this->lang->line('ad_campaigns'); ?>",
        selected_campaign_not_has_ad_sets: "<?php echo $this->lang->line('selected_campaign_not_has_ad_sets'); ?>",
        ad_sets: "<?php echo $this->lang->line('ad_sets'); ?>",
        no_insights_found: "<?php echo $this->lang->line('no_insights_found'); ?>",
        today: "<?php echo $this->lang->line('today'); ?>",
        week: "<?php echo $this->lang->line('week'); ?>",
        month: "<?php echo $this->lang->line('month'); ?>",
        show: "<?php echo $this->lang->line('show'); ?>",
        download: "<?php echo $this->lang->line('download'); ?>",
        date: "<?php echo $this->lang->line('date'); ?>",
        impressions: "<?php echo $this->lang->line('impressions'); ?>",
        reach: "<?php echo $this->lang->line('reach'); ?>",
        clicks: "<?php echo $this->lang->line('clicks'); ?>",
        cpm: "<?php echo $this->lang->line('cpm'); ?>",
        cpc: "<?php echo $this->lang->line('cpc'); ?>",
        ctr: "<?php echo $this->lang->line('ctr'); ?>",
        spent: "<?php echo $this->lang->line('spent'); ?>",
        campaign_objective: "<?php echo $this->lang->line('campaign_objective'); ?>",
        app_installs: "<?php echo $this->lang->line('app_installs'); ?>",
        brand_awareness: "<?php echo $this->lang->line('brand_awareness'); ?>",
        conversions: "<?php echo $this->lang->line('conversions'); ?>",
        event_responses: "<?php echo $this->lang->line('event_responses'); ?>",
        lead_generation: "<?php echo $this->lang->line('lead_generation'); ?>",
        link_clicks: "<?php echo $this->lang->line('link_clicks'); ?>",
        local_awareness: "<?php echo $this->lang->line('local_awareness'); ?>",
        messages: "<?php echo $this->lang->line('messages'); ?>",
        offer_claims: "<?php echo $this->lang->line('offer_claims'); ?>",
        page_likes: "<?php echo $this->lang->line('page_likes'); ?>",
        post_engagement: "<?php echo $this->lang->line('post_engagement'); ?>",
        product_catalog_sales: "<?php echo $this->lang->line('product_catalog_sales'); ?>",
        video_views: "<?php echo $this->lang->line('video_views'); ?>",
        campaign: "<?php echo $this->lang->line('campaign'); ?>",
        ad_set: "<?php echo $this->lang->line('ad_set'); ?>",
        please_select_ad: "<?php echo $this->lang->line('please_select_ad'); ?>",
        insights: "<?php echo $this->lang->line('insights'); ?>",
        your_page_name: "<?php echo $this->lang->line('your_page_name'); ?>",
        your_name: "<?php echo $this->lang->line('your_name'); ?>",
        age_from: "<?php echo $this->lang->line('age_from'); ?>",
        age_to: "<?php echo $this->lang->line('age_to'); ?>",
        send_message: "<?php echo $this->lang->line('send_message'); ?>",
        connect_in_messenger: "<?php echo $this->lang->line('connect_in_messenger'); ?>",
        no_selected_facebook_page_as_identity: "<?php echo $this->lang->line('no_selected_facebook_page_as_identity'); ?>",
        no_selected_instagram_account_as_identity: "<?php echo $this->lang->line('no_selected_instagram_account_as_identity'); ?>",
        boost: "<?php echo $this->lang->line('boost'); ?>",
        campaign_objective_not_supported: "<?php echo $this->lang->line('campaign_objective_not_supported'); ?>",
        status: "<?php echo $this->lang->line('status'); ?>",
    };
</script>