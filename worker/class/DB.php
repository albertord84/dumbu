<?php

namespace dumbu\cls {

    class DB {
        const host = 'localhost';
        const port = '3128';
        const db   = 'dumbudb';
        const user = 'root';
        const pass = '';
        
        private $connection = NULL;

        function __construct() {
            
        }

        function connect() {
            if (!$this->connection) {
                // Connect to DB
                $this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->db) or die("Cannot connect to database.");
            }
        }
        
        function get_clients() {
            $this->connect();
            $result = mysqli_query($this->connection, "SELECT * FROM clients WHERE username = '" . $username . "' AND password = '" . $password . "';");
        }
    }

}

?>