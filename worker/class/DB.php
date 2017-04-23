<?php

namespace dumbu\cls {

    class DB {

        protected $host = 'localhost';
        protected $db = 'dumbudb';
        //protected $port = '3128';
        protected $user = 'root';
        protected $pass = 'csduo2004mysql';
        
        private $connection = NULL;

        public function __construct() {
            $this->connect();
        }

        public function connect() {
            if (!$this->connection) {
                // Connect to DB
                $config = parse_ini_file(dirname(__FILE__) . "/../../../CONFIG.INI", true);
                $this->host = $config["database"]["host"];
                $this->db = $config["database"]["db"];
                //$this->port = $GLOBALS['sistem_config']->DB_PORT;
                $this->user = $config["database"]["user"];
                $this->pass = $config["database"]["pass"];
                $this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->db) or die("Cannot connect to database.");
            }
        }

        public function get_clients_by_status($user_status, $uid = 0) {
            try {
                $this->connect();
                $sql = ""
                        . "SELECT * FROM users "
                        . "     INNER JOIN clients ON clients.user_id = users.id "
                        . "     INNER JOIN plane ON plane.id = clients.plane_id "
                        . "WHERE users.status_id = $user_status AND user_id > $uid; ";
                
                $result = mysqli_query($this->connection, $sql);
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_clients_data() {
            try {
                $this->connect();
                $CLIENT = user_role::CLIENT;
                $ACTIVE = user_status::ACTIVE;
                $PENDING = user_status::PENDING;
                $VERIFY_ACCOUNT = user_status::VERIFY_ACCOUNT;
                $BLOCKED_BY_INSTA = user_status::BLOCKED_BY_INSTA;
                $BLOCKED_BY_TIME = user_status::BLOCKED_BY_TIME;
                //$UNFOLLOW = user_status::UNFOLLOW;
                $sql = ""
                        . "SELECT * FROM users "
                        . "     INNER JOIN clients ON clients.user_id = users.id "
                        . "     INNER JOIN plane ON plane.id = clients.plane_id "
                        . "WHERE users.role_id = $CLIENT "
                        . "     AND clients.unfollow_total <> 1 "
                        . "     AND (users.status_id = $ACTIVE OR "
                        . "          users.status_id = $PENDING OR "
                        . "          users.status_id = $VERIFY_ACCOUNT OR "
                        . "          users.status_id = $BLOCKED_BY_INSTA OR "
                        . "          users.status_id = $BLOCKED_BY_TIME);";
                $result = mysqli_query($this->connection, $sql);
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_unfollow_clients_data() {
            try {
                $this->connect();
                $CLIENT = user_role::CLIENT;
                $ACTIVE = user_status::ACTIVE;
                $PENDING = user_status::PENDING;
                //$UNFOLLOW = user_status::UNFOLLOW;
                $sql = ""
                        . "SELECT * FROM users "
                        . "     INNER JOIN clients ON clients.user_id = users.id "
                        . "     INNER JOIN plane ON plane.id = clients.plane_id "
                        . "WHERE users.role_id = $CLIENT "
                        . "     AND clients.unfollow_total = 1 "
                        . "     AND (users.status_id = $ACTIVE OR "
                        . "          users.status_id = $PENDING "
                        . "          );";
                $result = mysqli_query($this->connection, $sql);
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_client_data($client_id) {
            try {
                $this->connect();
                $result = mysqli_query($this->connection, ""
                        . "SELECT * FROM users "
                        . "     INNER JOIN clients ON clients.user_id = users.id "
                        . "WHERE users.id = $client_id; "
                );
                return $result ? $result->fetch_object() : NULL;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function set_client_status($client_id, $status_id) {
            try {
                $this->connect();
                $status_date = time();
                $sql = "UPDATE users "
                        . "SET "
                        . "      users.status_id   = $status_id, "
                        . "      users.status_date = '$status_date' "
                        . "WHERE users.id = $client_id; ";

                $result = mysqli_query($this->connection, $sql);
                if ($result)
                    print "<br>Update client_status! status_date: $status_date <br>";
                else
                    print "<br>NOT UPDATED client_status!!!<br> $sql <br>";
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        /**
         * 
         * @param type $client_id
         * @param type $profile_data Data from this client as profile
         * @return type
         */
        public function insert_client_daily_report($client_id, $profile_data) {
            try {
                $this->connect();
                $date = time();
                $sql = "INSERT INTO daily_report "
                        . "(client_id, followings, followers, date) "
                        . "VALUES "
                        . "($client_id, '$profile_data->following', '$profile_data->follower_count', '$date');";

                $result = mysqli_query($this->connection, $sql);
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function set_client_cookies($client_id, $cookies) {
            try {
                $this->connect();
                $sql  = "UPDATE clients "
                        . "SET ";
                $sql .= $cookies? " clients.cookies   = '$cookies' " : " clients.cookies   = NULL ";
                $sql .= "WHERE clients.user_id = $client_id; ";

                $result = mysqli_query($this->connection, $sql);
                if ($result)
                    print "<br>Update client_cookies! <br>";
                else
                    print "<br>NOT UPDATED client_cookies!!!<br> $sql <br>";
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
                        . "  (reference_profile.client_id = $client_id); "
//                        . "  (reference_profile.client_id = $client_id) AND "
//                        . "  (reference_profile.deleted <> TRUE);"
                );
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_reference_profiles_follows($ref_prof_id) {
            try {
                $this->connect();
//                $result = mysqli_query($this->connection, ""
//                        . "SELECT COUNT(*) as total FROM followed "
//                        . "WHERE "
//                        . "  followed.reference_id = $ref_prof_id;"
//                );
                $result = mysqli_query($this->connection, ""
                        . "SELECT follows as total FROM reference_profile "
                        . "WHERE  id = $ref_prof_id; "
                );
                $data = \mysqli_fetch_assoc($result);
                return $data['total'];
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_follow_work() {
            //$Elapsed_time_limit = $GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME;
            try {
                // Get daily work
                $sql = ""
                        . "SELECT *, "
                        . "   daily_work.cookies as cookies, "
                        . "   users.id as users_id, "
                        . "   clients.cookies as client_cookies, "
                        . "   reference_profile.insta_id as rp_insta_id, "
                        . "   reference_profile.type as rp_type, "
                        . "   reference_profile.id as rp_id "
                        . "FROM daily_work "
                        . "INNER JOIN reference_profile ON reference_profile.id = daily_work.reference_id "
                        . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
                        . "INNER JOIN users ON users.id = clients.user_id "
                        . "WHERE ((daily_work.to_follow  > 0) "
                        . "   OR  (daily_work.to_unfollow  > 0)) "
                        . "   AND (reference_profile.deleted <> TRUE || daily_work.to_unfollow  > 0) "
                        //. "WHERE (now - daily_work.last_access) >= $Elapsed_time_limit "
                        . "ORDER BY clients.last_access ASC, "
                        . "         daily_work.to_follow DESC "
                        . "LIMIT 1;";

                $result = mysqli_query($this->connection, $sql);
                $object = $result->fetch_object();

                // Update daily work time
                if ($object) {
                    //$ref_prof_id = $object->rp_insta_id;
                    $time = time();
                    $sql2 = ""
                            . "UPDATE clients "
                            . "SET clients.last_access = '$time' "
                            . "WHERE clients.user_id = $object->users_id; ";
                    $result2 = mysqli_query($this->connection, $sql2);
                    if (!$result2) {
                        var_dump($sql2);
                    }
                }
                return $object;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_unfollow_work($client_id) {
            try {
                // Get profiles to unfollow today for this Client... 
                // (i.e the last followed)
                $Limit = $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME;
                $Elapsed_time_limit = $GLOBALS['sistem_config']->UNFOLLOW_ELAPSED_TIME_LIMIT;
                $this->connect();
                $result = mysqli_query($this->connection, ""
                        . "SELECT * FROM followed "
                        . "WHERE followed.client_id = $client_id "
                        . "     AND followed.unfollowed = false "
                        . "     AND ((UNIX_TIMESTAMP(NOW()) - CAST(followed.date AS INTEGER)) DIV 60 DIV 60) > $Elapsed_time_limit "
                        . "ORDER BY followed.date ASC "
                        . "LIMIT $Limit;"
                );
                //print "\nClient: $client_id " . mysqli_num_rows($result) . "  ";
                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        /**
         * True is it was followed by this client
         * @param type $client_id
         * @param type $followed_id
         * @return type
         */
        public function is_profile_followed($client_id, $followed_id) {
            try {
                $result = mysqli_query($this->connection, ""
                    . "SELECT * FROM followed "
                    . "WHERE followed.client_id   = $client_id "
                    . "  AND followed.followed_id = $followed_id; "
                );
                //print "\nClient: $followed_id " . mysqli_num_rows($result) . "  ";
                return mysqli_num_rows($result);
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

                // TODO: UNCOMMENT
//                $sql = ""
//                        . "DELETE FROM followed "
//                        . "WHERE id = $unfollowed->id; ";
//                $result = mysqli_query($this->connection, $sql);

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

                $f_count = count($Ref_profile_follows);
                $sql = ""
                        . "UPDATE reference_profile "
                        . "	SET reference_profile.follows = reference_profile.follows + $f_count "
                        . "WHERE reference_profile.id = $daily_work->reference_id; ";
                $result = mysqli_query($this->connection, $sql);

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

        public function delete_daily_work_client($client_id) {
            try {
                $sql = ""
                        . "DELETE FROM daily_work WHERE daily_work.reference_id IN "
                        . "(SELECT reference_profile.id "
                        . "FROM reference_profile "
                        . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
                        . "WHERE clients.user_id = $client_id); ";
                $result = mysqli_query($this->connection, $sql);

                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function update_daily_work($ref_prof_id, $follows, $unfollows, $faults = 0) {
            try {
//                if ($follows == 0)
//                    $follows = 1; // To priorize others RP in the next time... avoiding select this RP ever...
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
                //$affected = mysqli_num_rows($result);
                if ($result)
                    print "<br>Update daily_work! follows: $follows | unfollows: $unfollows <br>";
                else
                    print "<br>NOT UPDATED daily_work!!!<br> $sql <br>";
                return TRUE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function truncate_daily_work() {
            try {
                $sql = "TRUNCATE daily_work;";

                $result = mysqli_query($this->connection, $sql);

                return $result;
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

                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function update_reference_cursor($reference_id, $end_cursor) {
            $date = ($end_cursor == '' || $end_cursor == NULL) ? time() : NULL;
            try {
                $sql = ""
                        . "UPDATE reference_profile "
                        . "SET "
                        . "     reference_profile.insta_follower_cursor = '$end_cursor', "
                        . "     reference_profile.end_date = '$date' "
                        . "WHERE reference_profile.id = $reference_id; ";

                $result = mysqli_query($this->connection, $sql);

//                if ($result)
//                    print "<br>Updated reference_cursor! reference_id: $reference_id <br>";
//                else
//                    print "<br>NOT UPDATED reference_cursor!!!<br> $sql <br>";

                return $result;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_system_config_vars() {
            try {
                $this->connect();
                $sql = "SELECT * FROM dumbu_system_config;";
                $result = mysqli_query($this->connection, $sql);
//                return $result ? $result->fetch_object() : NULL;
                return $result ? $result : NULL;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

    }

}
