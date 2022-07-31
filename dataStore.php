<?php

/**
 * Provides an interface for interacting with the 
 * articles database.
 */
class DataStore
{
    private $connection;

    private $TABLE_NAME = "articles";
    private $TABLE_COLUMNS = ["id", "name", "content"];
    private $ID_FETCH_QUERY = null;
    private $ID_UPDATE_QUERY = null;
    private $ID_DELETE_QUERY = null;
    private $CREATE_QUERY = null;

    /**
     * Tries to establish the connection to our database.
     * An exception is thrown upon failure.
     */
    public function __construct()
    {
        $DB_CONFIG_FILE = "db_config.php";
        require_once($DB_CONFIG_FILE);
        
        $this->connection = mysqli_connect(...array_values($db_config));
        if (!$this->connection)
        {
            throw new Exception("No database connection established");
        }

        $this->ID_FETCH_QUERY = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($this->ID_FETCH_QUERY, "SELECT * FROM $this->TABLE_NAME WHERE id=?");

        $this->ID_UPDATE_QUERY= mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($this->ID_UPDATE_QUERY, "UPDATE $this->TABLE_NAME SET name=?, content=? WHERE id=?");

        $this->ID_DELETE_QUERY = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($this->ID_DELETE_QUERY, "DELETE FROM $this->TABLE_NAME WHERE id=?");

        $this->CREATE_QUERY = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($this->CREATE_QUERY, "INSERT INTO $this->TABLE_NAME (name) VALUES (?)");
    }

    /**
     * Returns the result of a query asking for the content
     * of the articles data of the database.
     */
    public function fetch()
    {
        $query = "SELECT * FROM $this->TABLE_NAME";
        return mysqli_query($this->connection, $query);
    }

    /**
     * Fetches a record from a database based on it's id.
     */
    public function fetch_by_id($id)
    {
        // for future reference if needed:
        // https://www.php.net/manual/en/class.mysqli-stmt.php
        mysqli_stmt_bind_param($this->ID_FETCH_QUERY, "i", $id);
        mysqli_stmt_execute($this->ID_FETCH_QUERY);
        $query_result = $this->ID_FETCH_QUERY->get_result();

        return $query_result;
    }

    public function update_by_id($id, $name, $content)
    {
        mysqli_stmt_bind_param($this->ID_UPDATE_QUERY, "ssi", $name, $content, $id);
        mysqli_stmt_execute($this->ID_UPDATE_QUERY);
    }

    public function delete_by_id($id)
    {
        mysqli_stmt_bind_param($this->ID_DELETE_QUERY, "i", $id);
        mysqli_stmt_execute($this->ID_DELETE_QUERY);
    }

    public function create_article($article_name)
    {
        mysqli_stmt_bind_param($this->CREATE_QUERY, "s", $article_name);
        mysqli_stmt_execute($this->CREATE_QUERY);
        return mysqli_insert_id($this->connection);
    }
}