<?php

require_once __DIR__ . '/index.php';

$charts_of_accounts_id = htmlentities($_GET['charts_of_accounts_id']);

echo json_encode(getTheDetailsOfChartsOfAccounts($charts_of_accounts_id));