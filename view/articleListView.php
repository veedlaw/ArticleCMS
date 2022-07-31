<?php

require_once('pageView.php');

class ArticleListView extends PageView
{
    public function render_specific_view()
    {
        if ($this->article_data)
        {
            while ($row = mysqli_fetch_assoc($this->article_data))
            {
                print("<tr id='{$row["id"]}'>
                            <td class='articleNameCol'>{$row["name"]}</td>
                            <td class='show' ><a href='../article/{$row["id"]}'>Show</a></td>
                            <td class='edit'><a href='../article-edit/{$row["id"]}'>Edit</a></td>
                            <td class='delete'><a href='javascript:void(0)' onclick='deleteArticle({$row["id"]})'>Delete</a></td> 
                        </tr>"
                    );
            } // NOTICE THE TODO ABOVE FOR THE DELETE!
        }
    }
}