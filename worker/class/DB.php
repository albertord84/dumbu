<?php

namespace dumbu\cls {

    class DB {

        const host = 'localhost';
        const port = '3128';
        const db = 'dumbudb';
        const user = 'root';
        const pass = '';

        private $connection = NULL;

        function __construct() {
            
        }

        function connect() {
            if (!$this->connection) {
                // Connect to DB
                $this->connection = mysqli_connect($this::host, $this::user, $this::pass, $this::db) or die("Cannot connect to database.");
            }
        }

        function get_clients_data() {
            $this->connect();
            $CLIENT = \dumbu\cls\user_role::CLIENT;
            $ACTIVE = \dumbu\cls\user_status::ACTIVE;
            $result = mysqli_query($this->connection, ""
                    . "SELECT * FROM users "
                    . "     INNER JOIN clients ON clients.user_id = users.id "
                    . "WHERE users.role_id = $CLIENT AND users.status_id = $ACTIVE;"
            );
            return $result;
        }

        function get_reference_profiles_data($client_id) {
            $this->connect();
            $result = mysqli_query($this->connection, ""
                    . "SELECT * FROM reference_profile "
                    . "WHERE reference_profile.client_id = $client_id;"
            );
            return $result;
        }

        function get_unfollow_data($client_id) {
            // Get profiles to unfollow today for this Client... 
            // (i.e the last followed)
            $Limit = $GLOBALS['sistem_config']::DIALY_REQUESTS_BY_CLIENT;
            $this->connect();
            $result = mysqli_query($this->connection, ""
                    . "SELECT * FROM followed "
                    . "WHERE followed.client_id = $client_id "
                    . "     AND followed.unfollowed = false"
                    . "ORDER BY followed.date ASC"
                    . "LIMIT $Limit;"
            );
            return $result;
        }

    }

}

?>