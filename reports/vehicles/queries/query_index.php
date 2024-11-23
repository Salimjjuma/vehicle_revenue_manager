<?php

try {

    if (file_exists(__DIR__ . "/../../../fxn/config.php")) {
        require(__DIR__ . "/../../../fxn/config.php");
    } else {
        throw new Exception("Error Processing Request. File Template not Found", 1);
    }
} catch (Exception $e) {
    echo 'Exception Caught ', $e->getMessage(), "\n";
}

function to_get_all_the_vehicles()
{

    global $dbh;

    $stmt = "SELECT vehicle_name, registration_number, category_name, company_name, 
            v.isActive, DATE_FORMAT(v.created_at, '%d-%m-%Y') as created_at, 
            IFNULL(first_name, '_') as first_name, 
            IFNULL(second_name, '_') as second_name,
            IFNULL(last_name, '_') as last_name
            FROM vehicles v
            LEFT JOIN category c
            ON c.category_id = v.category
            LEFT JOIN vehicle_company vc
            ON vc.vehicle_company_id = v.vehicle_company
            LEFT JOIN vehicle_owners vo
            ON  vo.vehicle_id = v.vehicle_id
            LEFT JOIN owner o 
            ON o.owner_id = vo.owner_id
            ORDER BY v.created_at";

    $query = $dbh->prepare($stmt);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

// function to get the details of a vehicle using
// id parameter
function to_get_individual_vehicle_details($vehicle_id)
{

    global $dbh;

    $stmt = "SELECT vehicle_name, registration_number, 
                DATE_FORMAT(v.created_at, '%D, %M, %Y') as created_at, 
                c.category_name, 
                IFNULL(CONCAT(o.first_name, ' ', o.second_name, ' ', o.last_name), 'Not Assigned') AS owner_name, 
                vc.company_name, v.isActive
                FROM vehicles v
                LEFT JOIN vehicle_owners vo 
                ON vo.vehicle_id = v.vehicle_id
                LEFT JOIN owner o 
                ON o.owner_id =  vo.owner_id
                LEFT JOIN category c 
                ON c.category_id = v.category
                LEFT JOIN vehicle_company vc 
                ON vc.vehicle_company_id = v.vehicle_company
                WHERE v.vehicle_id =:vehicle_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->execute();

    $vehicle = $query->fetch();

    return $vehicle;
}

// get the individual revenue and expenses of a particular vehicle.
// parameters used are, $vehicle_id and $date
function to_get_individual_vehicle_revenue_and_expenses($vehicle_id, $date)
{

    global $dbh;


    $stmt = "SELECT
                    'Revenue' AS type,
                    c.revenue_name AS chart_name,
                    CONCAT('Ksh ', vr.revenue_generated) AS amount,
                    DATE_FORMAT(vr.entry_date, '%D-%M-%Y') as entry_date,
                    v.vehicle_id AS vehicle_id
                FROM
                    vehicle_revenue vr
                JOIN
                    charts_of_accounts c ON vr.revenue_id = c.charts_of_accounts_id
                JOIN
                    vehicles v ON vr.vehicle_id = v.vehicle_id
               WHERE
                    v.vehicle_id =:vehicle_id 
                    AND vr.entry_date =:date

                UNION ALL

                SELECT
                    'Expense' AS type,
                    c.revenue_name AS chart_name,
                    CONCAT('Ksh ', ve.entry_incurred) AS amount,
                    DATE_FORMAT(ve.entry_date, '%D-%M-%Y') as entry_date,
                    v.vehicle_id AS vehicle_id
                FROM
                    vehicle_expenses ve
                JOIN
                    charts_of_accounts c ON ve.expense_id = c.charts_of_accounts_id
                JOIN
                    vehicles v ON ve.vehicle_id = v.vehicle_id
                WHERE
                    v.vehicle_id =:vehicle_id 
                    AND ve.entry_date =:date";

    $query = $dbh->prepare($stmt);

    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->bindParam(":date", $date, PDO::PARAM_STR);

    $query->execute();
    $errors = $query->errorInfo();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function to_get_total_revenue_and_expenses_for_individual_report($vehicle_id, $date)
{

    global $dbh;

    // $dateObject = DateTime::createFromFormat('d-F-Y', $date);
    // $convertedDate = $dateObject->format('Y-m-d');

    $stmt = "SELECT 
                    DATE_FORMAT(entry_date, '%d-%M-%Y') AS entry_date, 
                     IFNULL(CONCAT('Ksh ', SUM(revenue_amount)), 'No revenue') AS total_revenue,
                     IFNULL(CONCAT('Ksh ', SUM(expense_amount)), 'No expense') AS total_expense,
                     IFNULL(CONCAT('Ksh ', SUM(revenue_amount) - SUM(expense_amount)), 'Input revenue') AS difference
                FROM ( 
                    SELECT entry_date, 
                        vehicle_revenue.revenue_generated AS revenue_amount, 
                        NULL AS expense_amount
                        
                    FROM vehicle_revenue
                    WHERE vehicle_id =:vehicle_id
                    AND entry_date =:date
                        
                    UNION ALL

                    SELECT entry_date, 
                    NULL AS revenue_amount, 
                        vehicle_expenses.entry_incurred AS expense_amount

                    FROM vehicle_expenses
                    WHERE vehicle_id =:vehicle_id 
                    AND entry_date =:date
                    ) AS combined";

    $query = $dbh->prepare($stmt);

    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->bindParam(":date", $date, PDO::PARAM_STR);

    $query->execute();
    $result = $query->fetch();

    return $result;
}

function get_vehicle_custom_date_revenue_amounts($vehicle_id, $startDate, $endDate)
{

    global $dbh;

    $stmt = "SELECT
                    'Revenue' AS type,
                    c.revenue_name AS chart_name,
                    CONCAT('+ Ksh ', vr.revenue_generated) AS amount,
                    entry_date,
                    v.vehicle_id AS vehicle_id
                FROM
                    vehicle_revenue vr
                JOIN
                    charts_of_accounts c ON vr.revenue_id = c.charts_of_accounts_id
                JOIN
                    vehicles v ON vr.vehicle_id = v.vehicle_id
               WHERE
                    v.vehicle_id =:vehicle_id
                    AND vr.entry_date BETWEEN :startDate AND :endDate
                    ORDER BY entry_date ASC";

    $query = $dbh->prepare($stmt);

    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->bindParam(":startDate", $startDate);
    $query->bindParam(":endDate", $endDate);

    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;

}

function get_vehicle_custom_date_expense_amounts($vehicle_id, $startDate, $endDate)
{

    global $dbh;

    $stmt = "SELECT
                    'Expense' AS type,
                    c.revenue_name AS chart_name,
                    CONCAT('- Ksh ', ve.entry_incurred) AS amount,
                    entry_date,
                    v.vehicle_id AS vehicle_id
                FROM
                    vehicle_expenses ve
                JOIN
                    charts_of_accounts c ON ve.expense_id = c.charts_of_accounts_id
                JOIN
                    vehicles v ON ve.vehicle_id = v.vehicle_id
                WHERE
                    v.vehicle_id =:vehicle_id
                    AND ve.entry_date BETWEEN :startDate AND :endDate
                ORDER BY entry_date ASC";

    $query = $dbh->prepare($stmt);

    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->bindParam(":startDate", $startDate);
    $query->bindParam(":endDate", $endDate);

    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;

}

function to_get_total_revenue_and_expenses_for_individual_report_for_a_period_of_time($vehicle_id, $startDate, $endDate)
{

    global $dbh;

    $stmt = "SELECT 
                    DATE_FORMAT(entry_date, '%d-%M-%Y') AS entry_date, 
                     IFNULL(CONCAT('Ksh ', SUM(revenue_amount)), 'No revenue') AS total_revenue,
                     IFNULL(CONCAT('Ksh ', SUM(expense_amount)), 'No expense') AS total_expense,
                     IFNULL(CONCAT('Ksh ', SUM(revenue_amount) - SUM(expense_amount)), 'Input revenue / Input Expense') AS difference
                FROM ( 
                    SELECT entry_date, 
                        vehicle_revenue.revenue_generated AS revenue_amount, 
                        NULL AS expense_amount
                        
                    FROM vehicle_revenue
                    WHERE vehicle_id =:vehicle_id
                        AND entry_date BETWEEN :startDate AND :endDate
                        
                    UNION ALL

                    SELECT entry_date, 
                    NULL AS revenue_amount, 
                        vehicle_expenses.entry_incurred AS expense_amount

                    FROM vehicle_expenses
                    WHERE vehicle_id =:vehicle_id 
                        AND entry_date BETWEEN :startDate AND :endDate
                    ) AS combined";

    $query = $dbh->prepare($stmt);

    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->bindParam(":startDate", $startDate, PDO::PARAM_STR);
    $query->bindParam(":endDate", $endDate, PDO::PARAM_STR);

    $query->execute();
    $result = $query->fetch();

    return $result;
}