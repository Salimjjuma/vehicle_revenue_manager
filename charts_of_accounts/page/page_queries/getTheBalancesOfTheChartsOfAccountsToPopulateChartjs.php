<?php

require_once __DIR__ . '/index.php';

$charts_of_accounts_id = htmlentities($_GET['charts_of_accounts_id']);

// If the parameters are present, their values are used; otherwise, 
// default values are set to the first and last day of the current month, respectively.

$userStartDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$userEndDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

$isDebit = htmlentities($_GET['debit']);

echo json_encode(getTheBalancesOfTheChartsOfAccountsToPopulateTheChartJs($charts_of_accounts_id, $userStartDate, $userEndDate, $isDebit));