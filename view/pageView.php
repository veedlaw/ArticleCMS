<?php

/**
 * Provides the abstract base for different views of
 * the application.
 * 
 * All views refer to their respective template files for
 * displaying information on the page.
 * 
 * The render method contains code that is common to all view rendering.
 * 
 * The render_specific method is responsible for showing all data that is
 * dynamic and unique to the view.
 */
abstract class PageView
{
    protected $article_data;
    protected $HEADER_FILE = "_header.php";
    protected $FOOTER_FILE = "_footer.php";

    private $VIEW_DIR = null;
    private $LIST_VIEW_DIR = "listView/";
    private $DETAIL_VIEW_DIR = "detailView/";
    private $EDIT_VIEW_DIR = "editView/";

    /**
     * Common base directory of templates for PageView subclasses.
     */
    protected $TEMPLATES_DIR = "templates/";

    /**
     * Provides a common constructor for all PageView subclasses.
     * Each view is constructed with some pre-given data.
     * 
     * The view does not make ouytside requests and only operates on 
     * the data given to it by the parameter $article_data.
     */
    public function __construct($article_data)
    {
        $this->article_data = $article_data;

        if ($this instanceof ArticleListView)
        {
            $this->VIEW_DIR = $this->LIST_VIEW_DIR;
        }
        else if ($this instanceof ArticleDetailView)
        {
            $this->VIEW_DIR = $this->DETAIL_VIEW_DIR;
        }
        else if ($this instanceof ArticleEditView)
        {
            $this->VIEW_DIR = $this->EDIT_VIEW_DIR;
        }
        else
        {
            print("else");
        }
    }

    /**
     * Handles page rendering / information population.
     */
    public function render()
    {
        try
        {
            require_once __DIR__ . "/../" . $this->TEMPLATES_DIR . $this->VIEW_DIR . $this->HEADER_FILE;      

            $this->render_specific_view();

            require_once __DIR__ . "/../" . $this->TEMPLATES_DIR . $this->VIEW_DIR . $this->FOOTER_FILE;
        }
        catch (Exception $e)
        {
            http_response_code($this->HTTP_INTERNAL_SERVER_ERROR);
            return;
        } 
    }

    protected function get_article_id()
    {
        return $this->article_data['id'];
    }

    /**
     * Returns the article name.
     * Does not involve outside querying.
     * Only refers to the class field $this->article data for
     * retrieving the article name.
     */
    protected function get_article_name()
    {
        return $this->article_data['name'];
    }

    /**
     * Returns the article content.
     * Does not involve outside querying.
     * Only refers to the class field $this->article data for
     * retrieving the article content.
     */
    protected function get_article_content()
    {
        return $this->article_data['content'];
    }

    /**
     * Is responsible for rendering all information that is unique to 
     * a specific view.
     */
    protected abstract function render_specific_view();
}
