<?php get_theme_part('header'); ?>
<?php
if (the_content_meta('theme_about_us_image_cover')) {
?>
<section class="about-us-cover">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 cover">
                <img src="<?php echo the_content_meta('theme_about_us_image_cover'); ?>" alt="About Us Cover">
            </div>
        </div>
    </div>
</section>
<?php
}
if (the_content_meta('theme_about_us_content')) {
?>
<section class="about-us-description">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 our-mission">
                <div class="row">
                    <div class="col-12">
                        <?php
                        echo the_content_meta('theme_about_us_content');
                        ?>
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