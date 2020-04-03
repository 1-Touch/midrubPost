<?php get_theme_part('header'); ?>
<section class="contact-area">
    <div class="container">
        <div class="row">
            <?php
            if (the_content_meta('theme_contact_form_enable')) {
                ?>
                <div class="col-sm-6 contact-us-form">
                    <?php echo start_form('', array('class' => 'form-contact-us')); ?>
                    <div class="row">
                        <div class="col-12">
                            <?php
                            if (the_content_meta('theme_contact_form_title')) {

                                echo '<h3>' . the_content_meta('theme_contact_form_title') . '</h3>';
                            }
                            if (the_content_meta('theme_contact_text_below_title')) {

                                echo '<p>' . the_content_meta('theme_contact_text_below_title') . '</p>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control full-name" id="exampleFormControlInput1" placeholder="<?php get_the_string('full_name'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control email" id="exampleFormControlInput1" placeholder="name@example.com" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control subject" id="exampleFormControlInput1" placeholder="<?php get_the_string('subject'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control message" id="exampleFormControlTextarea1" rows="3" placeholder="<?php get_the_string('message'); ?>" required></textarea>
                                </div>
                                <div class="form-group">
                                    <script src='https://www.google.com/recaptcha/api.js'></script>
                                    <div class="g-recaptcha" data-sitekey="<?php echo the_content_meta('theme_contact_recaptcha_site_key'); ?>"></div>
                                    <input type="hidden" class="content-id" value="<?php echo the_content_id(); ?>">
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary mb-2">
                                        <?php get_the_string('submit'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            <?php
            }
            if (the_content_meta('theme_contact_map_enable')) {
            ?>
            <div class="col-sm-6 contact-us-map">
                <div class="row">
                    <div class="col-12">
                        <!--The div element for the map -->
                        <div id="map"></div>
                        <script>
                            // Initialize and add the map
                            function initMap() {
                                // The location of Uluru
                                var uluru = {
                                    lat: <?php echo the_content_meta('theme_contact_map_latitude'); ?>,
                                    lng: <?php echo the_content_meta('theme_contact_map_longitude'); ?>
                                };
                                // The map, centered at Uluru
                                var map = new google.maps.Map(
                                    document.getElementById('map'), {
                                        zoom: 4,
                                        center: uluru
                                    });
                                // The marker, positioned at Uluru
                                var marker = new google.maps.Marker({
                                    position: uluru,
                                    map: map
                                });
                            }
                        </script>
                        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo the_content_meta('theme_contact_map_api_key'); ?>&callback=initMap">
                        </script>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<?php
if (the_content_meta('theme_contact_details_enable')) {
?>
<section class="contact-address">
    <div class="container">
        <div class="row">
            <?php
            if (the_content_meta('theme_contact_details_location')) {
            ?>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="icon-location-pin"></i>
                    </div>
                    <div class="col-md-8">
                        <h3><?php get_the_string('theme_location'); ?>:</h3>
                        <p>
                            <?php
                            echo the_content_meta('theme_contact_details_location');
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php
            }
            if (the_content_meta('theme_contact_details_phone')) {
            ?>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="icon-screen-smartphone"></i>
                    </div>
                    <div class="col-md-8">
                        <h3><?php get_the_string('theme_phone'); ?>:</h3>
                        <p>
                            <?php
                            echo the_content_meta('theme_contact_details_phone');
                            ?>                            
                        </p>
                    </div>
                </div>
            </div>
            <?php
            }
            if (the_content_meta('theme_contact_details_hours')) {
            ?>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="icon-clock"></i>
                    </div>
                    <div class="col-md-8">
                        <h3><?php get_the_string('theme_hours'); ?>:</h3>
                        <p>
                        <?php
                            echo the_content_meta('theme_contact_details_hours');
                        ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<?php
}
?>
<?php get_theme_part('footer'); ?>