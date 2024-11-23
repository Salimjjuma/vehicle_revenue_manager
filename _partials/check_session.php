<?php

session_start();
// check to see if the session has been started and unset the active session.
if(isset($_SESSION['username']) || isset($_SESSION['user_id'])){
  unset($_SESSION['username']);
  unset($_SESSION['last_login_timestamp']);
  unset($_SESSION['user_id']);
  session_destroy(); // destroy session
}