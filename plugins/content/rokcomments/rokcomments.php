<?php
/**
 * @version        1.3
 * @package        RokComments
 * @copyright      (C) 2005 - 2011 RocketTheme, LLC. All rights reserved.
 * @license        http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//$mainframe->registerEvent('onPrepareContent', 'plgContentRokComments');

class plgContentRokComments extends JPlugin
{
    
    var $domain;
    var $subdomain;
    var $postid;
    var $url;
    var $path;
    var $devmode;
    var $commenticon;
    var $account;
    var $width;
    var $postcount;
    var $host;


    /**
     * Page break plugin
     *
     * <b>Usage:</b>
     * <code>{rokcomments}</code>
     * @param $context
     * @param $row
     * @param $params
     * @param int $page
     *
     * @return bool
     */
    function onContentAfterTitle($context, &$row, &$params, $page = 0)
    {
        $option = strtolower(JRequest::getCmd('option'));
        $view = JRequest::getCmd('view');
        $itemid = JRequest::getInt('Itemid');

        // return is not active
        if(JPluginHelper::isEnabled('content','rokcomments')==false || $row == null) return;

        // only works for content
        if (!preg_match('/^com_content/', $context)) return;


        // Get Plugin info
        $user =& JFactory::getUser();
        $plgname = "rokcomments";
        $plugin =& JPluginHelper::getPlugin('content', $plgname);
        $document =& JFactory::getDocument();

        $pluginParams = new JRegistry($plugin->params);
        $r_id = $row->id;
        $r_catid = $row->catid;
        $r_slug = $row->slug;
        $r_alias = $row->alias;

        if (!isset($row->text) && isset($row->introtext)) {
            $text = $row->introtext;
        } else {
            $text = $row->text;
        }

        
        $regex = '#{rokcomments(-count)?}#s';

        $option = JRequest::getCmd('option');

        $system = $pluginParams->get('system', 'intensedebate');
        $method = $pluginParams->get('method', 'id');
        $catids = $pluginParams->get('categories', '');
        $menuids = $pluginParams->get('menus', '');
        $tagmode = $pluginParams->get('tagmode', 0);
        $showcount = $pluginParams->get('showcount', 1);
        $fb_appid = $pluginParams->get('fb-appid', '');
        $fb_moderatorid = $pluginParams->get('fb-modid', '');
        $lf_siteid = $pluginParams->get('lf-siteid','');
        

        //set vars
        $this->domain = $pluginParams->get('js-domain');
        $this->subdomain = $pluginParams->get('d-subdomain');
        $this->devmode = $pluginParams->get('d-devmode', 0);
        $this->account = $pluginParams->get('id-account');
        $this->commenticon = " " . $pluginParams->get('showicon', 'rk-icon');
        $this->width = $pluginParams->get('fb-width', '500');
        $this->postcount = $pluginParams->get('fb-postcount',10);

        //setup appropriate accounts
        if ($system == 'facebook') $this->account = $fb_appid;
        if ($system == 'livefyre') $this->account = $lf_siteid;

        $commentpage = false;

        //add some css
        $document->addStyleSheet(JURI::base() . "plugins/content/rokcomments/css/rokcomments.css");
   
        //url
        $baseurl = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['HTTP_HOST'] : "http://" . $_SERVER['HTTP_HOST'];
        if ($_SERVER['SERVER_PORT'] != "80") $baseurl .= ":" . $_SERVER['SERVER_PORT'];
        $this->host = $_SERVER['HTTP_HOST'];

     
        $this->path = JRoute::_(ContentHelperRoute::getArticleRoute($r_slug, $r_catid));
        $this->url = $baseurl . $this->path;

        //commentpage
        if ($view == 'article') $commentpage = true;

        // handle tag style
        switch ($tagmode) {
            case 1:
                $postid = $r_slug;
                break;
            case 2:
                $postid = $this->path;
                break;
            case 3:
                $postid = $r_id;
                break;
            default:
                $postid = $r_alias;
        }

        //sepcial case for ID
        if ($system == "intensedebate")
        {
            $postid = str_replace(array("-", ":"), array("_", "_"), $postid);
        }
            
        $this->postid = $postid;


        // get array of category ids
        if (is_array($catids))
        {
            $categories = $catids;
        } elseif ($catids == '')
        {
            $categories[] = $r_catid;
        } else
        {
            $categories[] = $catids;
        }


        $categories = $this->_getChildCategories($categories);

        // get array of menus ids
        if (is_array($menuids))
        {
            $menus = $menuids;
        } elseif ($menuids == '')
        {
            $menus[] = $itemid;
        } else
        {
            $menus[] = $menuids;
        }


        // check to make sure we are where we should be
        if ($method == 'code')
        {
            if (!(strpos($text, '{rokcomments') !== false))
            {
                $text = preg_replace($regex, '', $text);
                $this->_setContentText($row,$text);
                return;
            }
        } else
        {
            // remove rokcomments code if in there
            $text = preg_replace($regex, '', $text);
            if (!(in_array($r_catid, $categories) || in_array($itemid, $menus)))
            {
                $this->_setContentText($row,$text);
                return;
            }
        }

        // remove the count if both codes are visible - that makes no sense
        if (strpos($text,'{rokcomments}') !== false && strpos($text,'{rokcomments-count}') !== false) {
            $text = str_replace('{rokcomments-count}','',$text);
        }


        // check to make sure commentcount should be shown
        if (!$commentpage and $showcount == 0) return;




        if ($system == 'disqus')
        {
            // disqus
            if ($commentpage == false) {
                $output = "<div class=\"rk-commentcount{rk-icon}\"><a class=\"rokcomment-counter\" href=\"{post-url}#disqus_thread\" title=\"Comments\">Comments</a></div>\n";
                if (!defined('ROKCOMMENT_COUNT'))
                {

                    $headscript = '
                    <script type="text/javascript">

                        var disqus_shortname = "{subdomain}"; 
                        var disqus_developer = {devmode};
                        var disqus_identifier = "{post-id}";
                        var disqus_url = "{post-url}";

                        (function () {
                            var s = document.createElement("script"); s.async = true;
                            s.type = "text/javascript";
                            s.src = "http://" + disqus_shortname + ".disqus.com/count.js";
                            (document.getElementsByTagName("HEAD")[0] || document.getElementsByTagName("BODY")[0]).appendChild(s);
                        }());
                    </script>';
                    $headsript = $this->_replaceText($headscript);
                    $document->addCustomTag($headscript);
                    define('ROKCOMMENT_COUNT', true);
                }
            } else {
                $output = '

                <div id="disqus_thread"></div>
                <script type="text/javascript">

                    var disqus_shortname = "{subdomain}"; 
                    var disqus_developer = {devmode};
                    var disqus_identifier = "{post-id}";
                    var disqus_url = "{post-url}";

                    (function() {
                        var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
                        dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
                        (document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
                    })();
                </script>';
            }

        } elseif ($system == 'jskit') {
            // js-kit
            if ($commentpage == false)
            {
                if (!defined('ROKCOMMENT_COUNT'))
                {
                    $headscript = "
                <script type='text/javascript'>
				window.addEvent('domready', function(){
					var jskitScript = document.createElement('script');
					jskitScript.setAttribute('charset','utf-8');
					jskitScript.setAttribute('type','text/javascript');
					jskitScript.setAttribute('src','http://js-kit.com/for/{domain}/comments-count.js');
					var b = document.getElementsByTagName('body')[0];
					b.appendChild(jskitScript);
                });
                </script>";
                    $headsript = $this->_replaceText($headscript);
                    $document->addCustomTag($headscript);
                    define('ROKCOMMENT_COUNT', 1);
                }
                $output = '<div class="rk-commentcount{rk-icon}"><a href="{post-path}">Comments (<span class="js-kit-comments-count" uniq="{post-path}">0</span>)</a></div>';
            } else
            {
                $output = '<div style="margin-top:25px;" class="js-kit-comments" permalink="{post-url}" path=""></div><script src="http://js-kit.com/for/{domain}/comments.js"></script>';
            }
        } elseif ($system == 'facebook') {
            // facebook comments
            if ($commentpage == false) {
            
                $output = '<iframe src="http://www.facebook.com/plugins/comments.php?href={post-url}&permalink=1" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:16px;" allowTransparency="true"></iframe> ';   


            } else {
                $headscript = '
                    <meta property="fb:admins" content="'.$fb_moderatorid.'"/>
                    <meta property="fb:app_id" content="'.$this->account.'"/>
                ';
                $document->addCustomTag($headscript);

                $output = '
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) {return;}
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId={account}";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, "script", "facebook-jssdk"));</script>
                    <div class="fb-comments" data-href="{post-url}" data-num-posts="{postcount}" data-width="{width}" <div class="fb-comments" data-href="home.rhuk.net" data-num-posts="5" data-width="500" data-colorscheme="light"></div>';
 
            }
        } elseif ($system == 'livefyre') {
            // livefyre comments
            if ($commentpage == false) {
                //comment count currently not supported in livefyre
                $output = '';
            } else {
                $output = "
                    <!-- START: Livefyre Embed -->
                    <script type='text/javascript' src='http://www.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js'></script>
                    <script type='text/javascript'>
                        var fyre = LF({
                            site_id: {account},
                            version: '1.0'
                        });
                    </script>
                    <!-- END: Livefyre Embed -->
                ";
            }
        } else {
            // intense debate

            if ($commentpage == false)
            {
                $output = '
            		<script type="text/javascript">
            		var idcomments_acct = "{account}";var idcomments_post_id = "{post-id}";var idcomments_post_url = "{post-url}";
            		</script>
            		<div class="rk-commentcount{rk-icon}">    		
            		<script type="text/javascript" src="http://www.intensedebate.com/js/genericLinkWrapperV2.js"></script>
            		</div>';
            } else {
                $output = '
            		<script type="text/javascript">
            		var idcomments_acct = "{account}";var idcomments_post_id = "{post-id}";var idcomments_post_url = "{post-url}";
            		</script>
            		<span id="IDCommentsPostTitle" style="display:none"></span>
            		<script type="text/javascript" src="http://www.intensedebate.com/js/genericCommentWrapperV2.js"></script>';
            }


        }

        $output = $this->_replaceText($output);


        if ($method == 'code')
        {
            $text = preg_replace($regex, $output, $text);
        } else
        {
            $text .= $output;
        }

        $this->_setContentText($row,$text);


        return;
    }

    protected function _replaceText($output) {

        $search = array('{subdomain}', '{post-id}', '{post-url}', '{post-path}', '{devmode}', '{rk-icon}', '{domain}', '{account}', '{width}', '{postcount}', '{host}');
        $replace = array($this->subdomain, $this->postid, $this->url, $this->path, $this->devmode, $this->commenticon, $this->domain, $this->account, $this->width, $this->postcount, $this->host);

        $output = str_replace($search, $replace, $output);
        return $output;
    }

    protected function _setContentText(&$row,$text)
    {
        if (!isset($row->text) && isset($row->introtext)) {
            $row->introtext = $text;
        } else {
            $row->text = $text;
        }
        return;
    }


    protected function _getChildCategories($catids)
    {
        $app = JFactory::getApplication();
		$appParams = $app->getParams();
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        // Get an instance of the generic categories model
        $categories = JModel::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
        $categories->setState('params', $appParams);
        $levels = 9999;
        $categories->setState('filter.get_children', $levels);
        $categories->setState('filter.published', 1);
        $categories->setState('filter.access', $access);
        $additional_catids = array();

        foreach ($catids as $catid)
        {
            $categories->setState('filter.parentId', $catid);
            $recursive = true;
            $items = $categories->getItems($recursive);

            if($items) {
                foreach ($items as $category) {
                    $condition = (($category->level - $categories->getParent()->level) <= $levels);
                    if ($condition) {
                        $additional_catids[] = $category->id;
                    }

                }
            }
        }

        $catids = array_unique(array_merge($catids, $additional_catids));
        return $catids;
    }
}
