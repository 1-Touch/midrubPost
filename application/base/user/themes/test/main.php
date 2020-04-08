<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php get_the_site_favicon(); ?>

    <!-- Title -->
    <title><?php get_the_title(); ?></title>

    <!-- Set website url -->
    <meta name="url" content="<?php echo site_url(); ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.2.0/css/all.css">

    <!-- Simple Line Icons -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Midrub CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/base/user/themes/blue/styles/css/style.css?ver=' . MD_VER); ?>" media="all" />

    <!-- Styles -->
    <?php get_the_css_urls(); ?>

    <?php get_the_header(); ?>

</head>

<body>

    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light collapse navbar-collapse" id="mainMenu">
            <?php get_the_site_logo(); ?>
            <a href="#mainMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle close-main-menu toggle-btn">
                <i class="fas fa-times"></i>
            </a>
            <?php
            get_menu(
                'user_left_menu',
                array(
                    'before_menu' => '<ul>',
                    'before_single_item' => '<li[active]><a href="[url]"><i class="[class]"></i><br>',
                    'after_single_item' => '</a></li>',
                    'after_menu' => '</ul>'
                )
            );
            ?>
        </nav>

        <main id="main" class="site-main main">
            <header>
                <div class="container-fluid">
                    <ul class="nav navbar navbar-left">
                        <li>
                            <a href="#mainMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle toggle-btn">
                                <div class="menu-line"></div>
                                <div class="menu-line"></div>
                                <div class="menu-line"></div>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('user/app/posts'); ?>" class="new-post-button">
                                <i class="icon-note"></i>
                                <?php echo $this->lang->line('theme_new_post'); ?>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar justify-content-end">
                        <?php get_the_tickets(); ?>
                        <?php get_the_notifications(); ?>
                        <?php get_the_user_profile(); ?>
                    </ul>
                </div>
            </header>
            <?php get_user_view(); ?>
        </main>
    </div>
    <?php get_the_footer(); ?>

    <!-- Bootstrap Libraries -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    
    <?php get_the_js_urls(); ?>

    <!-- Animation Loader -->
    <div class="page-loading">
        <div class="animation-area">
            <div class="loading-animation">
                <div class="animation side_one"></div>
                <div class="animation side_two"></div>
                <div class="animation side_three"></div>
                <div class="animation side_four"></div>
            </div>
        </div>
    </div>

    <script language="javascript">
        Main.translation = {
            theme_days: "<?php echo $this->lang->line('theme_days'); ?>",
            theme_ago: "<?php echo $this->lang->line('theme_ago'); ?>",
            theme_just_now: "<?php echo $this->lang->line('theme_just_now'); ?>",
            theme_minutes: "<?php echo $this->lang->line('theme_minutes'); ?>",
            theme_hours: "<?php echo $this->lang->line('theme_hours'); ?>",
            theme_prev: "<?php echo $this->lang->line('theme_prev'); ?>",
            theme_next: "<?php echo $this->lang->line('theme_next'); ?>",
            theme_s: "<?php echo $this->lang->line('theme_s'); ?>",
            theme_m: "<?php echo $this->lang->line('theme_m'); ?>",
            theme_t: "<?php echo $this->lang->line('theme_t'); ?>",
            theme_w: "<?php echo $this->lang->line('theme_w'); ?>",
            theme_tu: "<?php echo $this->lang->line('theme_tu'); ?>",
            theme_f: "<?php echo $this->lang->line('theme_f'); ?>",
            theme_su: "<?php echo $this->lang->line('theme_su'); ?>",
            theme_january: "<?php echo $this->lang->line('theme_january'); ?>",
            theme_february: "<?php echo $this->lang->line('theme_february'); ?>",
            theme_march: "<?php echo $this->lang->line('theme_march'); ?>",
            theme_april: "<?php echo $this->lang->line('theme_april'); ?>",
            theme_may: "<?php echo $this->lang->line('theme_may'); ?>",
            theme_june: "<?php echo $this->lang->line('theme_june'); ?>",
            theme_july: "<?php echo $this->lang->line('theme_july'); ?>",
            theme_august: "<?php echo $this->lang->line('theme_august'); ?>",
            theme_september: "<?php echo $this->lang->line('theme_september'); ?>",
            theme_october: "<?php echo $this->lang->line('theme_october'); ?>",
            theme_november: "<?php echo $this->lang->line('theme_november'); ?>",
            theme_december: "<?php echo $this->lang->line('theme_december'); ?>",
        }
    </script>

</body>

</html>