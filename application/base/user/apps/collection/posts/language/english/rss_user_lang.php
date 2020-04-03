<?php
$lang["about"] = "About";
$lang["categories"] = "Categories";
$lang["rss_faq"] = '<div class="tab-pane fade show active" id="about-tab" role="tabpanel" aria-labelledby="about-nav">'
                        . '<h1>'
                            . 'About RSS Reader'
                        . '</h1>'
                        . '<p>The RSS Reader allows you to publish automatically posts from a RSS Feed. You can select accounts or groups with accounts where will be published the RSS\'s posts and enable the publish.</p>'
                        . '<p>You can even publish manually the RSS\'s posts. You can select the time when will be published and schedule or publish immediately the RSS\'s post. </p>'
                        . '<p>For each RSS\'s post scheduled or published, the History tabs will display the post content and accounts where will be published or was published. If the RSS\'s post wasn\'t published on a social network, you will see what was wrong in the History\'s tab.</p>'
                    . '</div>'
                    . '<div class="tab-pane fade" id="last-posts-tab" role="tabpanel" aria-labelledby="last-posts-nav">'
                        . '<h1>'
                            . 'Last Posts'
                        . '</h1>'
                        . '<p>In the Last Posts tab you will see the current RSS\'s posts from the connected RSS Feed. If you have enabled the option Publish manually, by clicking on the button <i class="icon-share-alt"></i> you will select a post from the RSS\'s feed and you will be able to schedule or publish it. It\'s important to enable the RSS\'s Feed and select accounts.</p>'
                        . '<p>Images are supported too, but them will not be saved in your storage if you will publish the RSS\'s posts with images.</p>'
                        . '<p>Scheduled RSS\'s posts at this moment can\'t be deleted.</p>'
                    . '</div>'
                    . '<div class="tab-pane fade" id="acconts-tab" role="tabpanel" aria-labelledby="accounts-nav">'
                        . '<h1>'
                            . 'Accounts'
                        . '</h1>'
                        . '<p>In the Accounts tab you can select the social accounts where will be published the RSS\'s posts. If you have enabled groups with accounts from the User\'s Settings page, will be displayed groups with accounts instead accounts.</p>'
                        . '<p>The Accounts Manager allows to connect in real time new accounts or create groups. Accounts Manager allows to delete accounts/groups and delete or add new accounts in a group.</p>'
                        . '<p>To unselect an account or group for a RSS\'s feed, you have to click on the account or group.</p>'
                    . '</div>'
                    . '<div class="tab-pane fade" id="history-tab" role="tabpanel" aria-labelledby="history-nav">'
                        . '<h1>'
                            . 'History'
                        . '</h1>'
                        . '<p>In the History tab are displayed all published and scheduled RSS\'s posts. By clicking on a Posts Details button will be displayed the RSS\'s Post content and social accounts where will be published or was published.</p>'
                        . '<p>If the RSS\'s post wasn\'t published on an account, will be displayed the error. </p>'
                    . '</div>'
                    . '<div class="tab-pane fade" id="settings-tab" role="tabpanel" aria-labelledby="settings-nav">'
                        . '<h1>'
                            . 'Settings'
                        . '</h1>'
                        . '<p>In the Settings tab is possible to manage the connected RSS Feed.</p>'
                        . '<ul>'
                            . '<li><strong>Enabled</strong> - allows to enable or disable the publish of the RSS\'s posts.</li>'
                            . '<li><strong>Publish Description</strong> - if is enabled will be published the post\'s description. If is disabled will be published the post.</li>'
                            . '<li><strong>Publish manually</strong> - allows to publish posts manually or automatically.</li>'
                            . '<li><strong>If posts have an image will be published as images</strong> - if is enabled the post\'s url will be published in the text message and post\'s image will be published as image.</li>'
                            . '<li><strong>Refferal Code</strong> - if you want to add a refferal code, enter in the field but don\'t add ? or &.</li>'
                            . '<li><strong>How often will be published the posts(Ex: 60 - means once/hour)</strong> - you can decide how often will be published the RSS\'s posts from the connect RSS Feed.</li>'
                            . '<li><strong>Publish posts which contains these words</strong> - allows to publish only posts which containes the added words.</li>'
                            . '<li><strong>Don\'t publish posts which contains these words</strong> - allows to not publish posts which contains the added words.</li>'
                            . '<li><strong>Delete RSS Feed</strong> - deletes the RSS\'s Feed, but you can connect it again.</li>'
                        . '</ul>'
                    . '</div>';
$lang["posts_remove_url"] = "Posts will be published without url";
$lang["posts_keep_html"] = "Allow HTML";
$lang["delete_rss"] = "Delete RSS Feed";
$lang["are_you_sure"] = "Are you sure?";
$lang["yes"] = "Yes";
$lang["no"] = "No";
$lang["enabled"] = "Enabled";
$lang["publish_description"] = "Publish Description";
$lang["publish_manually"] = "Publish manually(default automatically)";
$lang["referral_code"] = "Refferal Code";
$lang["publish_images"] = "If posts have an image will be published as images";
$lang["how_often_published"] = "How often will be published the posts(Ex: 60 - means once/hour)";
$lang["only_number_minutes"] = "Only number of minutes";
$lang["publish_posts_with_these_words"] = "Publish posts which contains these words";
$lang["dont_publish_posts_with_these_words"] = "Don't publish posts which contains these words";
$lang["separate_by_comma"] = "Separate words by comma";