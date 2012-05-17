<?php
 /**
  * @version   $Id$
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */
 
class RokGallery_Link_Type_Article_Platform_Wordpress3 implements RokGallery_Link_Type_Article_Platform {
    public function &getArticleInfo($id)
    {
        $article_info = new RokGallery_Link_Type_Article_Info();
        $article_info->setId($id);
        $article_info->setLink(get_permalink( $id ));

        $post_info = get_post($id);
        $article_info->title = $post_info->post_title;

        return $article_info;
    }
}
