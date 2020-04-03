<?php get_theme_part('header'); ?>
<section class="plans-list">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 all-plans">
                <div class="row">
                    <div class="col-12">
                        <?php
                        if (the_content_meta('theme_plans_title')) {

                            echo '<h1>' . the_content_meta('theme_plans_title') . '</h1>';
                        }
                        if (the_content_meta('theme_plans_text_below_title')) {

                            echo '<h2>' . the_content_meta('theme_plans_text_below_title') . '</h2>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="plans-area">
                            <div class="row">
                                <?php

                                // Get plans
                                $plans = get_all_visible_plans();

                                if ( $plans ) {

                                    $column = 4;

                                    if ( count($plans) === 1 ) {
                                        $column = 12;
                                    } else if ( count($plans) === 2 ) {
                                        $column = 6;
                                    } else if ( count($plans) === 4 ) {
                                        $column = 3;
                                    } else if ( count($plans) === 5 ) {
                                        $column = 2;
                                    }

                                    $url = the_url_by_page_role('sign_up')?the_url_by_page_role('sign_up'):site_url('auth/signup');

                                    foreach ( $plans as $plan ) {

                                        $period = get_the_string('theme_per_month', true);

                                        if ( $plan['period'] > 30 ) {

                                            $period = get_the_string('theme_per_year', true);

                                        }

                                        $plans_features = '';

                                        if ( $plan['features']) {

                                            $features = explode("\n", $plan['features']);

                                            foreach ($features as $feature) {
                                                
                                                if ( $feature ) {
                                                
                                                    $plans_features .= '<li>'
                                                                . '<i class="icon-check"></i>'
                                                                . $feature
                                                            . '</li>';

                                                }

                                            }

                                        }

                                        echo '<div class="col-lg-' . $column . '">'
                                                . '<div class="row">'
                                                    . '<div class="col-12">'
                                                        . '<h3>'
                                                            . $plan['plan_name']
                                                        . '</h3>'
                                                    . '</div>'
                                                . '</div>'
                                                . '<div class="row">'
                                                    . '<div class="col-12">'
                                                        . '<h2>'
                                                            . '<span>'
                                                                . $plan['currency_sign']
                                                            . '</span>'
                                                            . $plan['plan_price']
                                                        . '</h2>'
                                                    . '</div>'
                                                . '</div>'
                                                . '<div class="row">'
                                                    . '<div class="col-12">'
                                                        . '<h4>'
                                                            . $period
                                                        . '</h4>'
                                                    . '</div>'
                                                . '</div>'
                                                . '<div class="row">'
                                                    . '<div class="col-12">'
                                                        . '<ul>'
                                                            . $plans_features
                                                        . '</ul>'
                                                    . '</div>'
                                                . '</div>'
                                                . '<div class="row">'
                                                    . '<div class="col-12 text-center">'
                                                        . '<a href="' . $url . '?plan=' . $plan['plan_id'] . '" class="btn-success">'
                                                            . get_the_string('theme_get_started', true)
                                                        . '</a>'
                                                    . '</div>'
                                                . '</div>'
                                            . '</div>';

                                    }

                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_theme_part('footer'); ?>