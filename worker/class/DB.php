<?php

namespace dumbu\cls {

    class DB {

        const host = 'localhost';
        const port = '3128';
        const db = 'dumbudb';
        const user = 'root';
        const pass = 'csduo2004mysql';

        private $connection = NULL;

        public function __construct() {
            $this->connect();
        }

        public function connect() {
            if (!$this->connection) {
                // Connect to DB
                $this->connection = mysqli_connect($this::host, $this::user, $this::pass, $this::db) or die("Cannot connect to database.");
            }
        }

        public function get_clients_data() {
            try {
                $this->connect();
                $CLIENT   = user_role::CLIENT;
                $ACTIVE   = user_status::ACTIVE;
                $UNFOLLOW = user_status::UNFOLLOW;
                $result = mysqli_query($this->connection, ""
                        . "SELECT * FROM users "
                        . "     INNER JOIN clients ON clients.user_id = users.id "
                        . "WHERE users.role_id = $CLIENT "
                        . "     AND (users.status_id = $ACTIVE OR users.status_id = $UNFOLLOW);"
                );
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_reference_profiles_data($client_id) {
            try {
                $this->connect();
                $result = mysqli_query($this->connection, ""
                        . "SELECT * FROM reference_profile "
                        . "WHERE "
                        . "  (reference_profile.client_id = $client_id) AND "
                        . "  (reference_profile.deleted <> TRUE);"
                );
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_unfollow_work($client_id) {
            try {
                // Get profiles to unfollow today for this Client... 
                // (i.e the last followed)
                $Limit = $GLOBALS['sistem_config']::REQUESTS_AT_SAME_TIME;
                $Elapsed_time_limit = $GLOBALS['sistem_config']::UNFOLLOW_ELAPSED_TIME_LIMIT;
                $this->connect();
                $result = mysqli_query($this->connection, ""
                        . "SELECT * FROM followed "
                        . "WHERE followed.client_id = $client_id "
                        . "     AND followed.unfollowed = false "
                        . "     AND ((UNIX_TIMESTAMP(NOW()) - CAST(followed.date AS INTEGER)) DIV 60 DIV 60) > $Elapsed_time_limit "
                        . "ORDER BY followed.date ASC "
                        . "LIMIT $Limit;"
                );
		print "\nClient: $client_id " . mysqli_num_rows($result);
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function save_unfollow_work($Followeds_to_unfollow) {
            try {
                foreach ($Followeds_to_unfollow as $unfollowed) {
                    if ($unfollowed->unfollowed) {
                        $this->connect();
                        $result = mysqli_query($this->connection, ""
                                . "UPDATE followed "
                                . "SET followed.unfollowed = TRUE "
                                . "WHERE followed.id = $unfollowed->id; "
                        );
                    }
                }

                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function save_follow_work($Ref_profile_follows, $daily_work) {
            try {
                //daily work: reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
                foreach ($Ref_profile_follows as $follow) {
                    $this->connect();
                    $time = time();
                    $requested = ($follow->requested_by_viewer == 'requested') ? 'TRUE' : 'FALSE';
                    $sql = ""
                            . "INSERT INTO followed "
                            . "(followed_id, client_id, reference_id, requested, date, unfollowed) "
                            . "VALUES "
                            . "($follow->id, $daily_work->client_id, $daily_work->reference_id, $requested, $time, FALSE);";

                    $result = mysqli_query($this->connection, $sql);
                }

                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function insert_daily_work($ref_prof_id, $to_follow, $to_unfollow, $login_data) {
            try {
                $sql = ""
                        . "INSERT INTO daily_work "
                        . "(reference_id, to_follow, to_unfollow, cookies) "
                        . "VALUES "
                        . "($ref_prof_id, $to_follow, $to_unfollow, '$login_data');";

                $result = mysqli_query($this->connection, $sql);

                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function delete_daily_work($ref_prof_id) {
            try {
                $sql = ""
                        . "DELETE FROM daily_work "
                        . "WHERE reference_id = $ref_prof_id; ";
                $result = mysqli_query($this->connection, $sql);

                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function update_daily_work($ref_prof_id, $follows, $unfollows, $faults = 0) {
            try {
                $sql = ""
                        . "UPDATE daily_work "
                        . "SET daily_work.to_follow   = (daily_work.to_follow   - $follows), "
                        . "    daily_work.to_unfollow = (daily_work.to_unfollow - $unfollows) "
                        . "WHERE daily_work.reference_id = $ref_prof_id; ";

                $result = mysqli_query($this->connection, $sql);
                // Record Client last access and foults
                $time = time();
                $sql = ""
                        . "UPDATE clients "
                        . "INNER JOIN reference_profile ON clients.user_id = reference_profile.client_id "
                        . "SET clients.last_access = '$time', "
                        . "    clients.foults = clients.foults + $faults "
                        . "WHERE reference_profile.id = $ref_prof_id; ";

                $result = mysqli_query($this->connection, $sql);

                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function truncate_daily_work() {
            try {
                $sql = "TRUNCATE daily_work;";

                $result = mysqli_query($this->connection, $sql);

                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function reset_preference_profile_cursors() {
            try {
                $sql = ""
                        . "UPDATE reference_profile "
                        . "SET reference_profile.insta_follower_cursor = null;  ";
                $result = mysqli_query($this->connection, $sql);

                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function update_reference_cursor($reference_id, $end_cursor) {
            $end_cursor = $end_cursor ? "'" . $end_cursor . "'" : NULL;
            try {
                $sql = ""
                        . "UPDATE reference_profile "
                        . "SET reference_profile.insta_follower_cursor = $end_cursor "
                        . "WHERE reference_profile.id = $reference_id; ";

                $result = mysqli_query($this->connection, $sql);

                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_follow_work() {
            //$Elapsed_time_limit = $GLOBALS['sistem_config']::MIN_NEXT_ATTEND_TIME;
            try {
                $sql = ""
                        . "SELECT *, reference_profile.insta_id as rp_insta_id FROM daily_work "
                        . "INNER JOIN reference_profile ON reference_profile.id = daily_work.reference_id "
                        . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
                        . "INNER JOIN users ON users.id = clients.user_id "
                        . "WHERE ((daily_work.to_follow  > 0) "
                        . "   OR  (daily_work.to_unfollow  > 0)) "
                        . "   AND (reference_profile.deleted <> TRUE) "
                        //. "WHERE (now - daily_work.last_access) >= $Elapsed_time_limit "
                        . "ORDER BY clients.last_access ASC, "
                        . "         daily_work.to_follow DESC "
                        . "LIMIT 1;";

                $result = mysqli_query($this->connection, $sql);
                $object = $result->fetch_object();
                //$object->num_rows = mysql_num_rows($result);
                return $object;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

    }

}
