<?php

require_once('pageView.php');

/**
 * Provides the detailed view of the article.
 */
class ArticleDetailView extends PageView
{
    public function render_specific_view()
    {
        $this->article_data = mysqli_fetch_assoc($this->article_data);

        $article_name = $this->get_article_name();
        $article_content = $this->get_article_content();
        
        print("<h1>$article_name</h1>");
        print("<p>$article_content</p>");
    }

}