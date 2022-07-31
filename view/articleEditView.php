<?php

require_once('pageView.php');

/**
 * Provides the edit view of the article.
 */
class ArticleEditView extends PageView
{
    public function render_specific_view()
    {
        $this->article_data = mysqli_fetch_assoc($this->article_data);
        $article_id = $this->get_article_id();
        $article_name = $this->get_article_name();
        $article_content = htmlspecialchars($this->get_article_content());

		print('<input type="hidden" name="id" value="' . $article_id . '">');
        print('<input id="nameInput" maxlength="32" required="required" type="text" name="name" value="' . $article_name . '">');
        print("<h2> Content </h2>");
        print('<textarea id="contentInput" maxlength="1024" name="content">' . $article_content . "</textarea>");
    }
}