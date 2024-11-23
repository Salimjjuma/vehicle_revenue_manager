<?php

require_once __DIR__ . '/../../../fxn/config.php';

// globally defined dbh connection. 
// define('CON', $dbh);
function getTheDetailsOfChartsOfAccounts($charts_of_accounts_id)
{
    global $dbh;
    $stmt = "SELECT acc_name, revenue_name, revenue_description, coa.isActive, 
         CONCAT('Ksh ', FORMAT(min_amount,0)) as min_amount, 
         CONCAT('Ksh ', FORMAT(max_amount,0)) as max_amount,
         coa.creation_date, ac.debit
         FROM charts_of_accounts coa
         LEFT JOIN accounts_type ac
         ON coa.acc_type = ac.acc_id
         LEFT JOIN charts_of_accounts_settings coas
         ON coa.charts_of_accounts_id = coas.charts_of_accounts
         WHERE charts_of_accounts_id = :charts_of_accounts_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":charts_of_accounts_id", $charts_of_accounts_id, PDO::PARAM_STR);

    $query->execute();
    $results = $query->fetch(PDO::FETCH_ASSOC);

    return $results;
}

// variables.
// charts_of_accounts_id : string of the id of the charts_of_accounts table
// $startDate : date of the entries in the revenue or expense table 
// $endDate : date of the end of the entries in the revenue or expense table
// isDebit : bool flag that checks if the account type is revenue or expense
function getTheBalancesOfTheChartsOfAccountsToPopulateTheChartJs($charts_of_accounts_id, $startDate, $endDate, $isDebit)
{
    global $dbh;

    if ($isDebit == 0) {
        $table = "vehicle_revenue";
        $column = "revenue_generated";
        $onColumn = "revenue_id";
    } else {
        $table = "vehicle_expenses";
        $column = "entry_incurred";
        $onColumn = "expense_id";
    }

    $stmt = "SELECT SUM($column) AS balance, entry_date, ca.revenue_name
             FROM $table  AS v
             LEFT JOIN charts_of_accounts ca
             ON ca.charts_of_accounts_id = v.$onColumn
             WHERE ca.charts_of_accounts_id =:charts_of_accounts_id
             GROUP BY entry_date
             ORDER BY entry_date ASC";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":charts_of_accounts_id", $charts_of_accounts_id, PDO::PARAM_STR);

    $query->execute();
    $balances = $query->fetchAll(PDO::FETCH_OBJ);

    return $balances;
}

function getTheBalancesOfTheChartsOfAccountsToPopulateTheDataTables($charts_of_accounts_id, $startDate, $endDate, $isDebit)
{
    global $dbh;

    if ($isDebit == 0) {
        $table = "vehicle_revenue";
        $column = "revenue_generated";
        $onColumn = "revenue_id";
    } else {
        $table = "vehicle_expenses";
        $column = "entry_incurred";
        $onColumn = "expense_id";
    }

    $stmt = "SELECT v.vehicle_name,  v.registration_number, category_name,
            vr.entry_date, company_name,
            CONCAT('Ksh ', FORMAT(vr.$column,2)) as balance
            FROM $table AS vr 
            LEFT JOIN vehicles v 
            ON vr.vehicle_id = v.vehicle_id 
            LEFT JOIN category c
            ON v.category = c.category_id 
            LEFT JOIN vehicle_company vc 
            ON v.vehicle_company = vc.vehicle_company_id    
            WHERE vr.$onColumn = :charts_of_accounts_id
            ORDER BY vr.entry_date ASC";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":charts_of_accounts_id", $charts_of_accounts_id, PDO::PARAM_STR);

    $query->execute();
    $balances = $query->fetchAll(PDO::FETCH_OBJ);

    return $balances;

}