<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
  Name: Fifth Helper
  Author: Scrisoft
  Created: 17/09/2016
 * */
if (!function_exists('parse_rss_feed')) {

    function parse_rss_feed($url, $allow_html=NULL) {
        
        $params = parse_url($url);
        if(isset($params['query'])) {
            parse_str($params['query'], $query);
            if(isset($query['rss-url']) && isset($query['tool'])) {
                include_once APPPATH . 'interfaces/Tools.php';
                if (file_exists(APPPATH . 'tools' . '/' . $query['tool'] . '/' . $query['tool'] . '.php')) {
                    include_once APPPATH . 'tools' . '/' . $query['tool'] . '/' . $query['tool'] . '.php';
                    $class = ucfirst(str_replace('-', '_', $query['tool']));
                    $get = new $class;
                    $page = $get->page(['user_id'=>'', 'rss-url' => $query['rss-url']]);
                    return $page;
                } 
                exit();
            }   
        }
        
        $xmlDoc = new DOMDocument();

        @$xmlDoc->load($url);
        
        if ( !$xmlDoc->getElementsByTagName('channel')->length && !$xmlDoc->getElementsByTagName('entry')->length ) {
            return false;
        }
        
        if (!$xmlDoc->getElementsByTagName('channel')->length) {
            $channel = $xmlDoc->getElementsByTagName('title')->item(0);
            $rss_title = strip_tags($channel->nodeValue);
            $channel = $xmlDoc->getElementsByTagName('description')->item(0);
            $rss_description = @strip_tags($channel->nodeValue);
            if ($rss_title) {
                $title = [];
                $url = [];
                $pri = [];
                $description = [];
                for ($i = 0; $i < $xmlDoc->getElementsByTagName('entry')->length; $i++) {
                    $channel = $xmlDoc->getElementsByTagName('entry')->item($i);
                    $title[] = stripslashes(strip_tags($channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue));
                    $pri[] = get_instance()->ecl('Feeds')->single($channel,$xmlDoc,@$channel);
                    $text = $xmlDoc->saveHtml($channel);
                    $xml = @simplexml_load_string($text);

                    if ( !empty($xml->link) ) {
                        $linkAttributes = $xml->link->attributes();
                        $u = $linkAttributes->href[0];
                        $url[] = strip_tags($u[0]);
                    } else {
                        $links = $channel->getElementsByTagName('link');
                        $link = $links->item(0)->getAttribute('href');
                        $url[] = $link;
                    }
                    
                    if ( $allow_html ) {
                        
                        if (@$channel->getElementsByTagName('summary')->length) {
                            $description[] = @stripslashes($channel->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue);
                        } elseif (@$channel->getElementsByTagName('description')->length) {
                            $description[] = @stripslashes($channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue);
                        } elseif (@$channel->getElementsByTagName('encoded')->length) {
                            $description[] = @stripslashes($channel->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue);
                        } elseif (@$channel->getElementsByTagName('content')->length) {
                            $description[] = @stripslashes($channel->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue);
                        }                        
                        
                    } else {
                    
                        if (@$channel->getElementsByTagName('summary')->length) {
                            $description[] = @stripslashes(strip_tags($channel->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue));
                        } elseif (@$channel->getElementsByTagName('description')->length) {
                            $description[] = @stripslashes(strip_tags($channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue));
                        } elseif (@$channel->getElementsByTagName('encoded')->length) {
                            $description[] = @stripslashes(strip_tags($channel->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue));
                        } elseif (@$channel->getElementsByTagName('content')->length) {
                            $description[] = @stripslashes(strip_tags($channel->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue));
                        }
                        
                    }
                    
                }
                return array(
                    'rss_title' => $rss_title,
                    'rss_description' => $rss_description,
                    'title' => $title,
                    'url' => $url,
                    'description' => $description,
                    'show' => $pri
                );
            }
            
        } else {

            if (!@$xmlDoc->getElementsByTagName('channel')) {
                return false;
            }

            $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
            $rss_title = strip_tags($channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue);
            $rss_description = @strip_tags($channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue);
            if ($rss_title) {
                $rss = $xmlDoc->getElementsByTagName('item');
                $title = [];
                $url = [];
                $description = [];
                $pri = [];
                $i = 0;
                foreach ($rss as $item) {
                    $title[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue));
                    $url[] = @strip_tags($rss->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue);
                    $pri[] = get_instance()->ecl('Feeds')->single($item,$xmlDoc,$rss->item($i));
                    
                    if ( $allow_html ) {
                        
                        if (@$rss->item($i)->getElementsByTagName('content')->length) {
                            $description[] = @stripslashes($rss->item($i)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue);
                        } elseif (@$rss->item($i)->getElementsByTagName('encoded')->length) {
                            $description[] = empty(stripslashes($rss->item($i)->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue))?'':stripslashes($rss->item($i)->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue);
                        } elseif (@$rss->item($i)->getElementsByTagName('description')->length) {
                            $description[] = @stripslashes($rss->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue);
                        } elseif (@$rss->item($i)->getElementsByTagName('summary')->length) {
                            $description[] = @stripslashes($rss->item($i)->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue);
                        }                        
                        
                    } else {
                        
                        if (@$rss->item($i)->getElementsByTagName('content')->length) {
                            $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue));
                        } elseif (@$rss->item($i)->getElementsByTagName('encoded')->length) {
                            $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue));
                        } elseif (@$rss->item($i)->getElementsByTagName('description')->length) {
                            $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue));
                        } elseif (@$rss->item($i)->getElementsByTagName('summary')->length) {
                            $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue));
                        }
                    
                    }
                    
                    $i++;
                }

                return array(
                    'rss_title' => $rss_title,
                    'rss_description' => $rss_description,
                    'title' => $title,
                    'url' => $url,
                    'description' => $description,
                    'show' => $pri
                );

            } else {

                $rss = $xmlDoc->getElementsByTagName('item');

                if ($rss) {

                    if (!$rss_title) {
                        $parse = parse_url($url);
                        $rss_title = $parse['host'];
                    }

                    $title = [];
                    $url = [];
                    $pri = [];
                    $description = [];
                    $i = 0;
                    foreach ($rss as $item) {
                        $title[] = stripslashes(strip_tags($rss->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue));
                        $url[] = strip_tags($rss->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue);
                        $pri[] = get_instance()->ecl('Feeds')->single($item,$xmlDoc,$rss->item($i));
                        
                        if ( $allow_html ) {
                        
                            if (@$rss->item($i)->getElementsByTagName('content')->length) {
                                $description[] = @stripslashes($rss->item($i)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue);
                            } elseif (@$rss->item($i)->getElementsByTagName('encoded')->length) {
                                $description[] = @stripslashes($rss->item($i)->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue);
                            } elseif (@$rss->item($i)->getElementsByTagName('description')->length) {
                                $description[] = @stripslashes($rss->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue);
                            } elseif (@$rss->item($i)->getElementsByTagName('summary')->length) {
                                $description[] = @stripslashes($rss->item($i)->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue);
                            }
                        
                        } else {
                            
                            if (@$rss->item($i)->getElementsByTagName('content')->length) {
                                $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue));
                            } elseif (@$rss->item($i)->getElementsByTagName('encoded')->length) {
                                $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue));
                            } elseif (@$rss->item($i)->getElementsByTagName('description')->length) {
                                $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue));
                            } elseif (@$rss->item($i)->getElementsByTagName('summary')->length) {
                                $description[] = @stripslashes(strip_tags($rss->item($i)->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue));
                            }
                            
                        }
                        
                        $i++;
                    }

                    return array(
                        'rss_title' => $rss_title,
                        'rss_description' => $rss_description,
                        'title' => $title,
                        'url' => $url,
                        'description' => $description,
                        'show' => $pri
                    );

                }

            }

        }
        
    }
    
}

if (!function_exists('get_feed_net')) {
    function get_feed_net($data) {
        if($data) {
            $bt = [];
            foreach($data as $name => $account) {
                $accounts = json_decode($account);
                if(is_numeric($accounts)) {
                    $bt[] = $accounts;
                } else {
                    foreach ($accounts as $acc) {
                        $bt[] = (INT)$acc;
                    }
                }
            }
            return $bt;
        }
    }
}