<section class="chatbot-page" data-csrf-name="<?php echo $this->security->get_csrf_token_name(); ?>" data-csrf-hash="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="row">
        <div class="col-xl-2 offset-xl-1 theme-box">
            <?php get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'views/menu.php'); ?>
        </div>
        <div class="col-xl-8">
            <div class="row clean">
                <div class="col-12 col-xl-4">
                    <div class="audit-small-widget theme-box">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php echo $this->lang->line('chatbot_total_replies'); ?>
                            </div>
                            <div class="panel-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <p>
                                            <?php echo htmlspecialchars($total_replies); ?>
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <i class="lni-support"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="audit-small-widget theme-box">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php echo $this->lang->line('chatbot_subscribers'); ?>
                            </div>
                            <div class="panel-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <p>
                                            <?php echo htmlspecialchars($total_subscribers); ?>
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <i class="lni-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="audit-small-widget theme-box">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php echo $this->lang->line('chatbot_facebook_pages'); ?>
                            </div>
                            <div class="panel-body p-3 text-center">
                                <div class="row">
                                    <div class="col-6">
                                        <p>
                                            <?php echo htmlspecialchars($total_pages); ?>
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <i class="lni-facebook"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clean">
                <div class="col-12">
                    <div class="audit-large-widget theme-box">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-6 col-lg-8">
                                        <i class="lni-stats-up"></i>
                                        <?php echo $this->lang->line('chatbot_replies_stats'); ?>
                                    </div>
                                    <div class="col-6 col-lg-4">
                                        <div class="dropdown dropdown-suggestions">
                                            <button class="btn btn-secondary dropdown-toggle chatbot-select-stats-facebook-page btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php echo $this->lang->line('chatbot_all_facebook_pages'); ?>
                                            </button>
                                            <div class="dropdown-menu chatbot-suggestions-dropdown" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
                                                <div class="card">
                                                    <div class="card-head">
                                                        <input type="text" class="chatbot-search-for-stats-facebook-page" placeholder="<?php echo $this->lang->line('chatbot_search_facebook_pages'); ?>">
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-group chatbot-stats-pages-list">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body p-3">
                                <canvas id="replies-stats-chart" style="width: 100%;" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clean">
                <div class="col-12">
                    <div class="audit-large-widget theme-box">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-6 col-lg-8">
                                        <i class="lni-support"></i>
                                        <?php echo $this->lang->line('chatbot_quick_replies_popularity'); ?>
                                    </div>
                                    <div class="col-6 col-lg-4">
                                        <div class="dropdown dropdown-suggestions">
                                            <button class="btn btn-secondary dropdown-toggle chatbot-select-keywords-facebook-page btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php echo $this->lang->line('chatbot_all_facebook_pages'); ?>
                                            </button>
                                            <div class="dropdown-menu chatbot-suggestions-dropdown" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
                                                <div class="card">
                                                    <div class="card-head">
                                                        <input type="text" class="chatbot-search-for-keywords-facebook-page" placeholder="<?php echo $this->lang->line('chatbot_search_facebook_pages'); ?>">
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-group chatbot-keywords-stats-pages-list">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('chatbot_keywords'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('chatbot_response'); ?>
                                                </th>
                                                <th scope="col">
                                                    <?php echo $this->lang->line('chatbot_replied'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3">
                                                    <nav>
                                                        <ul class="pagination" data-type="popularity-replies">
                                                        </ul>
                                                    </nav>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>