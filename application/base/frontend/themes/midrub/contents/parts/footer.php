<?php
if (the_content_meta('theme_contact_section_enable')) {
    ?>
    <section class="main-contact">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 questions-answers">
                    <div class="row">
                        <div class="col-12">
                            <?php
                            if (the_content_meta('theme_contact_section_title')) {

                                echo '<h1>' . the_content_meta('theme_contact_section_title') . '</h1>';
                            }
                            if (the_content_meta('theme_contact_text_below_title')) {

                                echo '<h2>' . the_content_meta('theme_contact_text_below_title') . '</h2>';
                            }
                            ?>
                            <p class="lead">
                                <?php
                                if (the_content_meta('theme_contact_section_button_text')) {

                                    $url = site_url();

                                    if ( the_content_meta('theme_contact_section_button_link') ) {
                                        $url = site_url(the_content_meta('theme_contact_section_button_link'));
                                    }

                                    echo '<a href="' . $url . '" class="btn btn-lg btn-secondary">' . the_content_meta('theme_contact_section_button_text') . '</a>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
}
?>
</main>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <?php
                get_menu(
                    'footer_menu',
                    array(
                        'before_menu' => '<ul>',
                        'before_single_item' => '<li class="nav-docs[class]"><a href="[url]">',
                        'after_single_item' => '</a></li>',
                        'after_menu' => '</ul>'
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 follow-us text-center">
                <?php
                get_menu(
                    'social_menu',
                    array(
                        'before_menu' => '',
                        'before_single_item' => '<a href="[url]" target="_blank"><i class="',
                        'after_single_item' => '"></i></a>',
                        'after_menu' => ''
                    )
                );
                ?>
            </div>
        </div>
    </div>
</footer>
<div class="gdpr-modal">
    <div class="container">
        <div class="row">
            <div class="col-10">
                <p>
                    <?php

                    get_the_string('theme_website_uses_cookies');

                    if ( the_url_by_page_role('cookies') ) {
                        echo ' <a href="' . the_url_by_page_role('cookies') . '">' . get_the_string('theme_learn_more', true) . '</a>';
                    } 
                    
                    ?>
                </p>
            </div>
            <div class="col-2 text-right">
                <button class="btn-default accept-cookies" type="button">
                    <?php get_the_string('theme_accept'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<?php md_get_the_frontend_footer(); ?>
<script src="<?php echo the_theme_uri(); ?>js/main.js"></script>

</body>
</html>