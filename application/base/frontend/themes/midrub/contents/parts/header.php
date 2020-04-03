<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title><?php md_get_the_title(); ?></title>

    <!-- Set website url -->
    <meta name="url" content="<?php echo site_url(); ?>">
    <?php md_get_the_meta_description(); ?>
    <?php md_get_the_meta_keywords(); ?>

    <!-- Frontend Midrub Styles -->
    <link rel="stylesheet" id="midrub-styles-css" href="<?php echo the_theme_uri(); ?>styles/css/style.css?ver=0.0.2" type="text/css" media="all" />

    <!-- Simple Line Icons -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <?php md_get_the_frontend_header(); ?>

</head>

<body>

    <main role="main">
        <header>
            <div class="container justify-content-between">
                <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3">
                    <h5 class="font-logo">
                        <a href="<?php echo site_url(); ?>">
                            <?php
                            get_site_name();
                            ?>
                        </a>
                    </h5>
                    <?php
                    get_menu(
                        'main_menu',
                        array(
                            'before_menu' => '<nav class="my-2 my-md-0 ml-md-3 ml-md-auto">',
                            'before_single_item' => '<a class="p-2[class]" href="[url]">',
                            'after_single_item' => '</a>',
                            'after_menu' => '</nav>'
                        )
                    );
                    ?>

                    <?php
                    if (md_the_user_session()) {

                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle" id="navbarDropdownMenuLink-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5(md_the_user_session()['email']) ?>" class="img-fluid rounded-circle z-depth-0" width="30">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-purple" aria-labelledby="navbarDropdownMenuLink-5">
                                <a class="dropdown-item whov waves-effect waves-light" href="<?php echo site_url('user/app/dashboard') ?>">
                                    <?php get_the_string('theme_dashboard'); ?>
                                </a>
                                <a class="dropdown-item whov waves-effect waves-light" href="<?php echo site_url('logout') ?>">
                                    <?php get_the_string('theme_sign_out'); ?>
                                </a>
                            </div>
                        </div>
                    <?php

                } else {

                    get_menu(
                        'access_menu',
                        array(
                            'before_menu' => '<nav>',
                            'before_single_item' => '<a class="btn[class]" href="[url]">',
                            'after_single_item' => '</a>',
                            'after_menu' => '</nav>'
                        )
                    );
                }
                ?>

                </div>
            </div>
        </header>