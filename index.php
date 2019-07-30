
<?php
require 'includes/config.inc.php';
require MYSQL;
require_once 'includes/Utility.php';

if (filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
    $id = $_GET['id'];

    $_SESSION['id'] = $id;

    $q = "SELECT caller_number,eligible_amount,propose_amount,ticket_id, created_date, claimed_on from email where userkey = $id ";

    $result = mysqli_query($link, $q);
    $row    = mysqli_fetch_array($result);

    if (!empty($row)) {

        if (!empty($row['claimed_on'])) {
            include 'includes/header.php';
            include './claimed.php';
            include 'includes/footer.php';
            exit();
        }

        /**
         * Check if link has expired
         */
        $expiryCheckQuery = "SELECT caller_number,eligible_amount,propose_amount,ticket_id,created_date, claimed_on from email where userkey = $id AND created_date > DATE_SUB(NOW(), INTERVAL 168 HOUR)";

        $r1  = mysqli_query($link,$expiryCheckQuery);
        $rw1 = mysqli_fetch_array($r1);

        if (empty($rw1)) {
            include 'includes/header.php';
            include './expired.php';
            include 'includes/footer.php';
            exit();
        }

        /**
         * Good to go....
         */

        include 'includes/header.php';
        include './includes/form.php';
        include 'includes/footer.php';

        die();
    }

}

/**
 * Invalid link
 */
include 'includes/header.php';
include './error.php';
include 'includes/footer.php';
