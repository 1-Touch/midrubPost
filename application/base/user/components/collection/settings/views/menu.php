<ul class="nav nav-tabs">
    <li>
        <h3 class="settings-menu-header">
            <?php echo $this->lang->line('general'); ?>
        </h3>
    </li>
    <?php
    if ( $options ) {

        foreach ( $options as $option ) {

            if ( empty($option['component']) ) {
                continue;
            }

            $active = '';

            if ( $page === $option['section_slug'] ) {
                $active = ' active show';
            }

            echo '<li class="nav-item">'
                    . '<a href="' . site_url('user/settings?p=' . $option['section_slug']) . '" class="nav-link' . $active . '">'
                        . $option['section_name']
                    . '</a>'
                . '</li>';

        }

    }
    ?>
    <li>
        <h3 class="settings-menu-header">
            <?php echo $this->lang->line('additional'); ?>
        </h3>
    </li>
    <?php
    if ( $options ) {

        foreach ( $options as $option ) {

            if ( !empty($option['component']) ) {
                continue;
            }

            $active = '';

            if ( $page === $option['section_slug'] ) {
                $active = ' active show';
            }

            echo '<li class="nav-item">'
                    . '<a href="' . site_url('user/settings?p=' . $option['section_slug']) . '" class="nav-link' . $active . '">'
                        . $option['section_name']
                    . '</a>'
                . '</li>';

        }

    }
    ?>
</ul>