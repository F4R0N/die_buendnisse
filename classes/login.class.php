<?php

session_start();

class login {

    private $Benutzername;
    private $Passwort;
    private $ID;

    function set_data($Benutzername, $Passwort) {
        $this->Benutzername = $Benutzername;

        $Passwort = crypt($Passwort, '$6$BR0WS3RG4M3');
        $Passwort = explode('$', $Passwort);
        $Passwort = $Passwort[3];

        $this->Passwort = $Passwort;
    }

    function login() {
        $mysql_connection = new mysql_connection();
        $mysql_connection->connect_MYSQL();

        if ($this->check_login_data()) {
            $this->doLogin();
            $mysql_connection->close_MYSQL();
            return true;
        } else {
            $mysql_connection->close_MYSQL();

            return false;
        }
    }

    function check_login_data() {

        $result = mysql_query("SELECT
                                    ID 
                               FROM 
                                    users 
                               WHERE
                                    username = '" . mysql_real_escape_string($this->Benutzername) . "' AND
                                    password     = '" . mysql_real_escape_string($this->Passwort) . "'") or die(mysql_error());



        if (mysql_num_rows($result) == 1) {
            $row = mysql_fetch_array($result);
            $this->ID = $row['ID'];
            return true;
        }
        return false;
    }

    function doLogin() {
        mysql_query("UPDATE 
                        users 
                     SET
                       last_login = " . time() . ",
                       last_action = " . time() . ",
                       session_ID    = '" . session_id() . "',
                       IP            = '" . $_SERVER['REMOTE_ADDR'] . "'          
                     WHERE
                       ID = " . $this->ID);
        $_SESSION['ID'] = $this->ID;
    }

    function logout() {
        $mysql_connection = new mysql_connection();
        $mysql_connection->connect_MYSQL();

        mysql_query("UPDATE 
                        users 
                     SET
                       last_action = " . time() . ",
                       session_ID    = NULL         
                     WHERE
                       ID = " . $_SESSION['ID']);
        $mysql_connection->close_MYSQL();
        session_destroy();
        return true;
    }

}