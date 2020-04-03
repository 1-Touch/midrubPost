<?php get_theme_part('header'); ?>
<?php
if (the_content_meta('theme_top_section_enable')) {
    ?>
    <section class="main-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <?php
                    if (the_content_meta('theme_top_section_slogan')) {

                        echo '<h3>' . the_content_meta('theme_top_section_slogan') . '</h3>';
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?php
                    if (the_content_meta('theme_top_section_text_below_slogan')) {

                        echo '<p>' . the_content_meta('theme_top_section_text_below_slogan') . '</p>';
                    }
                    if (the_content_meta('theme_top_section_button_text')) {

                        $url = site_url();

                        if ( the_content_meta('theme_top_section_button_link') ) {
                            $url = site_url(the_content_meta('theme_top_section_button_link'));
                        }

                        echo '<a href="' . $url . '" class="btn-success">' . the_content_meta('theme_top_section_button_text') . '</a>';
                    }
                    if (the_content_meta('theme_top_section_text_below_button')) {

                        echo '<span>' . the_content_meta('theme_top_section_text_below_button') . '</span>';
                    }
                    ?>
                </div>
                <div class="col-lg-6 text-right">
                    <?php
                    if (the_content_meta('theme_top_section_large_image')) {

                        echo '<img src="' . the_content_meta('theme_top_section_large_image') . '" alt="presentation">';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php
}
if (the_content_meta('theme_presentation_section_enable')) {
    ?>
    <section class="main-features">
        <div class="container">
            <?php
            if ( the_content_meta('theme_top_section_stats') ) {
            ?>
            <div class="row">
                <div class="col-xl-12 general-stats">
                    <div class="row">
                        <?php
                        get_home_page_stats();
                        ?>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="row">
                <div class="col-xl-12 service-features">
                    <div class="row">
                        <div class="col-12">
                            <?php
                            if (the_content_meta('theme_presentation_section_title')) {

                                echo '<h1>' . the_content_meta('theme_presentation_section_title') . '</h1>';
                            }
                            if (the_content_meta('theme_presentation_text_below_title')) {

                                echo '<h2>' . the_content_meta('theme_presentation_text_below_title') . '</h2>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <?php
                                $tabs = the_content_meta_list('theme_presentation_videos');
                                if ( $tabs ) {

                                    for ( $t = 0; $t < count($tabs); $t++ ) {

                                        $active = '';

                                        if ( $t < 1 ) {
                                            $active = ' active';
                                        }

                                        echo '<li class="nav-item">'
                                                . '<a class="nav-link' . $active . '" id="social-presentation-tab" data-toggle="tab" href="#social-presentation-' . $t . '" role="tab" aria-controls="social-presentation" aria-selected="true">'
                                                    . $tabs[$t]['theme_presentation_section_tab_title']
                                                . '</a>'
                                            . '</li>';

                                    }

                                }
                                ?>
                            </ul>
                            <div class="tab-content" id="video-presentation">
                                <?php
                                if ( $tabs ) {
                                    for ( $tb = 0; $tb < count($tabs); $tb++ ) {

                                        $active = '';

                                        if ( $tb < 1 ) {
                                            $active = ' active';
                                        }
                                        ?>
                                        <div class="tab-pane fade show<?php echo $active; ?>" id="social-presentation-<?php echo $tb; ?>" role="tabpanel" aria-labelledby="social-presentation-tab">
                                            <div class="row">
                                                <?php 
                                                if ( isset($tabs[$tb]['theme_presentation_section_video_title']) && isset($tabs[$tb]['theme_presentation_section_video_cover']) && isset($tabs[$tb]['theme_presentation_section_video_url']) ) {
                                                ?>
                                                <div class="col-md-4 col-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="<?php echo $tabs[$tb]['theme_presentation_section_video_url']; ?>">
                                                                <img src="<?php echo $tabs[$tb]['theme_presentation_section_video_cover']; ?>" alt="<?php echo $tabs[$tb]['theme_presentation_section_video_title']; ?>">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p>
                                                                <?php echo $tabs[$tb]['theme_presentation_section_video_title']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                if ( isset($tabs[$tb]['theme_presentation_section_video_title2']) && isset($tabs[$tb]['theme_presentation_section_video_cover2']) && isset($tabs[$tb]['theme_presentation_section_video_url2']) ) {
                                                ?>
                                                <div class="col-md-4 col-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="<?php echo $tabs[$tb]['theme_presentation_section_video_url2']; ?>">
                                                                <img src="<?php echo $tabs[$tb]['theme_presentation_section_video_cover2']; ?>" alt="<?php echo $tabs[$tb]['theme_presentation_section_video_title2']; ?>">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p>
                                                                <?php echo $tabs[$tb]['theme_presentation_section_video_title2']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                if ( isset($tabs[$tb]['theme_presentation_section_video_title3']) && isset($tabs[$tb]['theme_presentation_section_video_cover3']) && isset($tabs[$tb]['theme_presentation_section_video_url3']) ) {
                                                ?>
                                                <div class="col-md-4 col-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="<?php echo $tabs[$tb]['theme_presentation_section_video_url3']; ?>">
                                                                <img src="<?php echo $tabs[$tb]['theme_presentation_section_video_cover3']; ?>" alt="<?php echo $tabs[$tb]['theme_presentation_section_video_title3']; ?>">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p>
                                                                <?php echo $tabs[$tb]['theme_presentation_section_video_title3']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php

                                    }
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
    <div class="modal fade" id="video-presentation-modal" tabindex="-1" role="dialog" aria-labelledby="modal-video-presentation">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <iframe id="video-modal" style="width: 100%; height: 100%;" src="" frameborder="0" allowfullscreen></iframe>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
if (the_content_meta('theme_questions_section_enable')) {
    ?>
    <section class="main-questions">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 questions-answers">
                    <div class="row">
                        <div class="col-12">
                            <?php
                            if (the_content_meta('theme_questions_section_title')) {

                                echo '<h1>' . the_content_meta('theme_questions_section_title') . '</h1>';
                            }
                            if (the_content_meta('theme_questions_text_below_title')) {

                                echo '<h2>' . the_content_meta('theme_questions_text_below_title') . '</h2>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="list-group">
                                <?php
                                if ( the_content_meta('theme_questions_section_list') ) {

                                    $values = the_content_meta_list('theme_questions_section_list');

                                    echo '<li class="list-group-item active">';

                                    $count = 1;

                                    for ( $c = 0; $c < count($values); $c++ ) {

                                        if ( $c > 0 ) {
                                            echo '</li><li class="list-group-item">';
                                        }

                                        echo '<a href="#">'
                                            . '<span>'
                                                . $count
                                            . '</span>'
                                            . $values[$c]['theme_questions_section_list_question']
                                        . '</a>';

                                        $count++;

                                        echo '<p>'
                                            . $values[$c]['theme_questions_section_list_answer']
                                        . '</p>';

                                    }

                                    echo '</li>';

                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
}
?>

<?php get_theme_part('footer'); ?>