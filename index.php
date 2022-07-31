<?php

require_once("dataStore.php");
require_once('view/articleListView.php');
require_once('view/articleDetailView.php');
require_once('view/articleEditView.php');

class FrontController
{
    private $view;
    private $dataStore;

    public function __construct()
    {
        // Try to establish a connection to the database.
        try
        {
            $this->dataStore = new DataStore();
        }
        catch (Exception $e)
        {
            //header("Location: 500.html")
            print("TODO CATCH EXCEPTION");
        }
    }

    public function dispatch()
    {
        $request_body = $this->parseRequest($_SERVER['REQUEST_URI']);

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE')
        {
            $id = htmlspecialchars(substr($request_body, strlen("article/")));
            $this->dataStore->delete_by_id($id);
        }

        // Handles storing the form data
        if (isset($_POST))
        {
            if ($this->update_form_is_valid($_POST))
            {
                $article_id = htmlspecialchars($_POST['id']);
                $article_name = htmlspecialchars($_POST['name']);
                $article_content = htmlspecialchars($_POST['content']);

                $this->dataStore->update_by_id($article_id, $article_name, $article_content);
            }
            else if ($this->create_form_is_valid($_POST))
            {
                $article_name = htmlspecialchars($_POST['name']); 
                $new_id = $this->dataStore->create_article($article_name);
                header("Location: ../article/${new_id}");
            }
        }

        $this->load_view($request_body);
        $this->view->render();
    }


    private function load_view($request_body)
    {
        if ($request_body === "articles/")
        {
            $this->view = new ArticleListView($this->dataStore->fetch());
        }
        else if ($this->str_starts_with($request_body, "article/"))
        {
            $this->load_article_detail($request_body);
        }
        else if ($this->str_starts_with($request_body, "article-edit/"))
        {
            $this->load_article_edit($request_body);
        }
        else
        {
            
            // http_response_code(404);
            exit();
        }
    }

    private function load_article_detail($request_body)
    {
        $article_detail = $this->get_by_id($request_body, "article/");
        $this->view = new ArticleDetailView($article_detail);
    }

    private function load_article_edit($request_body)
    {
        $article_edit = $this->get_by_id($request_body, "article-edit/");
        $this->view = new ArticleEditView($article_edit);
    }

    // HELPER FUNCTIONS BELOW.

    private function parseRequest($request)
    {
        $REQUEST_URI_PREFIX = '/~27992525/cms/';
        return trim(substr($request, strlen($REQUEST_URI_PREFIX)));
    }

    private function get_by_id($request_body, $request)
    {
        // Get the id:
        $id = htmlspecialchars(substr($request_body, strlen($request)));
        $article_data = $this->dataStore->fetch_by_id($id);

        if (is_bool($article_data) || mysqli_num_rows($article_data) === 0)
        {
            http_response_code(404);
            exit();
        }

        return $article_data;
    }

    private function update_form_is_valid($form_data)
    {
        $MAX_NAME_LEN = 32;
        $MAX_CONTENT_LEN = 1024;
        return (isset($form_data['id']) &&
            isset($form_data['name']) && !empty($form_data['name'] && strlen($form_data['name'] <= $MAX_NAME_LEN)) && 
            isset($form_data['content']) && strlen($form_data['content']) <= $MAX_CONTENT_LEN);
    }

    private function create_form_is_valid($form_data)
    {
        $MAX_NAME_LEN = 32;
        return isset($form_data['name']) && !empty($form_data['name'] && strlen($form_data['name'] <= $MAX_NAME_LEN));
    }

    /**
     * Not available in < php 8, so here it is ... 
     */
    private function str_starts_with($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}

$controller = new FrontController();
$controller->dispatch();
