<?php

require_once __DIR__ . "/../../../fxn/config.php";
require_once __DIR__ . "/../../../fxn/authenticate.php";

define("STATUS", 1);

// function used to get the details of the vehicle in view.
function to_get_details_of_vehicle($vehicle_id)
{
    global $dbh;

    $stmt = "SELECT v.vehicle_id, `vehicle_name`, `registration_number`, 
             c.category_name, c.category_id, 
              first_name, 
              second_name,
              last_name,
             vc.company_name, DATE_FORMAT(v.created_at, '%D, %M, %Y') as created_at, 
             v.isActive 
             FROM vehicles v 
             LEFT JOIN category c 
             ON c.category_id = v.category 
             LEFT JOIN vehicle_company vc 
             ON vc.vehicle_company_id = v.vehicle_company 
             LEFT JOIN `vehicle_owners` vo 
             ON v.vehicle_id = vo.vehicle_id 
             LEFT JOIN owner o 
             ON vo.owner_id = o.owner_id 
             WHERE v.vehicle_id =:vehicle_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);

    $query->execute();

    $result = $query->fetch(PDO::PARAM_STR);
    return $result;
}

// function to get revenue and expense of the vehicle in the datatables.
function to_get_revenue_and_expense($vehicle_id)
{

    global $dbh;

        $stmt = "SELECT 
                        entry_date, 
                        IFNULL(CONCAT('Ksh ', SUM(revenue_amount)), 'No revenue') AS total_revenue,
                        IFNULL(CONCAT('Ksh ', SUM(expense_amount)), 'No expense') AS total_expense,
                        IFNULL(CONCAT('Ksh ', SUM(revenue_amount) - SUM(expense_amount)), 'Input Revenue/Expense') AS difference
                    FROM ( 
                        SELECT entry_date, 
                            vehicle_revenue.revenue_generated AS revenue_amount, 
                            NULL AS expense_amount
                            
                        FROM vehicle_revenue
                        WHERE vehicle_id =:vehicle_id
                            
                        UNION ALL

                        SELECT entry_date, 
                        NULL AS revenue_amount, 
                            vehicle_expenses.entry_incurred AS expense_amount

                        FROM vehicle_expenses
                        WHERE vehicle_id =:vehicle_id
                        ) AS combined
                    GROUP BY entry_date ORDER BY entry_date";

    $query = $dbh->prepare($stmt);

    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

// function to add a transcation in the database. 
function to_add_a_transcation($chart_of_acc_id, $amount, $vehicle_id, $check_type, $date)
{

    try {
        global $dbh;

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = array();

        if (empty($chart_of_acc_id))
            throw new Exception("Chart of Accounts is Missing.");

        if (empty($amount))
            throw new Exception("Amount cannot be empty");

        if (empty($vehicle_id))
            throw new Exception("Vehicle cannot be empty");

        if (!isset($check_type))
            throw new Exception("Transcation type cannot be empty");

        if (empty($date))
            throw new Exception("Date cannot be empty");

        $id = bin2hex(random_bytes(30));

        if ($check_type === "0") {
            $stmt = "INSERT INTO `vehicle_revenue`
                          (`id`, `vehicle_id`, `revenue_id`, `revenue_generated`, `entry_date`) 
                          VALUES (:id, :v_id, :r_id, :amount, :date)";
        } elseif ($check_type === "1") {
            $stmt = "INSERT INTO `vehicle_expenses`
                        (`id`, `vehicle_id`, `expense_id`, `entry_incurred`, `entry_date`) 
                        VALUES (:id, :v_id, :r_id, :amount, :date)";
        } else {
            throw new Exception("You have provided, transcation_type which is of type" . gettype($check_type));
        }

        $query = $dbh->prepare($stmt);

        $query->bindParam(":id", $id, PDO::PARAM_STR);
        $query->bindParam(":v_id", $vehicle_id, PDO::PARAM_STR);
        $query->bindParam(":r_id", $chart_of_acc_id, PDO::PARAM_STR);
        $query->bindParam(":amount", $amount, PDO::PARAM_STR);
        $query->bindParam(":date", $date, PDO::PARAM_STR);

        $query->execute();

        $data['success'] = true;
        $data['message'] = "Transcation added succesfully";

    } catch (PDOException $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }

    return $data;
}

// function to get all the charts of accounts from the database. 
function to_get_all_the_chart_of_accounts($searchTerms)
{

    global $dbh;

    if (!isset($searchTerms)) {
        $stmt = "SELECT charts_of_accounts_id, revenue_name, debit
                        FROM charts_of_accounts
                        LEFT JOIN accounts_type at
                        ON at.acc_id = acc_type
                        WHERE isActive =" . STATUS;
        $query = $dbh->prepare($stmt);
    } else {
        $stmt = "SELECT charts_of_accounts_id, revenue_name, debit
                        FROM charts_of_accounts
                        LEFT JOIN accounts_type at
                        ON at.acc_id = acc_type
                        WHERE revenue_name 
                        LIKE :revenue_name AND
                        isActive =" . STATUS;
        $query = $dbh->prepare($stmt);
        $query->bindValue(":revenue_name", '%' . $searchTerms . '%', PDO::PARAM_STR);
    }

    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $charts_of_account = array();

    foreach ($result as $r) {
        $charts_of_account[] = array(
            "id" => $r['charts_of_accounts_id'],
            "text" => $r['revenue_name'],
            "isDebited" => $r['debit']
        );
    }
    ;


    return $charts_of_account;
}

// function to get revenue and expense of the vehicle in the datatables.
function to_get_revenue_and_expense_for_chartsJs($vehicle_id)
{

    global $dbh;

    $stmt = "SELECT 
                     entry_date, 
                     SUM(revenue_amount) AS total_revenue,
                     SUM(expense_amount) AS total_expense
                FROM ( 
                    SELECT entry_date, 
                        vehicle_revenue.revenue_generated AS revenue_amount, 
                        NULL AS expense_amount
                        
                    FROM vehicle_revenue
                    WHERE vehicle_id =:vehicle_id
                    AND MONTH(entry_date) = MONTH(NOW())
                        
                    UNION ALL

                    SELECT entry_date, 
                    NULL AS revenue_amount, 
                        vehicle_expenses.entry_incurred AS expense_amount

                    FROM vehicle_expenses
                    WHERE vehicle_id =:vehicle_id
                    AND MONTH(entry_date) = MONTH(NOW())
                    ) AS combined
                GROUP BY
                entry_date 
                ORDER BY
                entry_date;";

    $query = $dbh->prepare($stmt);

    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function to_get_the_revenue_of_a_vehicle($date, $vehicle_id)
{

    global $dbh;

    $stmt = "SELECT
                    'Revenue' AS type,
                    c.revenue_name AS chart_name,
                    vr.revenue_generated AS amount,
                    DATE_FORMAT(vr.entry_date, '%D-%M-%Y') as entry_date,
                    v.vehicle_id AS vehicle_id, vr.id AS id, at.debit
                FROM
                    vehicle_revenue vr
                JOIN
                    charts_of_accounts c ON vr.revenue_id = c.charts_of_accounts_id
                JOIN
                    vehicles v ON vr.vehicle_id = v.vehicle_id
                JOIN 
                    accounts_type AS at ON at.acc_id = c.acc_type
                WHERE
                    v.vehicle_id =:vehicle_id 
                    AND vr.entry_date =:entry_date

                UNION ALL

                SELECT
                    'Expense' AS type,
                    c.revenue_name AS chart_name,
                    ve.entry_incurred AS amount,
                    DATE_FORMAT(ve.entry_date, '%D-%M-%Y') as entry_date,
                    v.vehicle_id AS vehicle_id, ve.id AS id, at.debit
                FROM
                    vehicle_expenses ve
                JOIN
                    charts_of_accounts c ON ve.expense_id = c.charts_of_accounts_id
                JOIN
                    vehicles v ON ve.vehicle_id = v.vehicle_id
                JOIN 
                 	accounts_type AS at ON at.acc_id = c.acc_type
                WHERE
                    v.vehicle_id =:vehicle_id 
                    AND ve.entry_date =:entry_date";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->bindParam(":entry_date", $date, PDO::PARAM_STR);

    $flag = $query->execute();

    $revenue = $query->fetchAll(PDO::FETCH_OBJ);
    $errors = $query->errorInfo();

    if ($flag) {
        return $revenue;
    } else {
        return $errors;
    }

}


function to_edit_vehicle($vehicle_id, $vehicle_name, $registration_number, $category, $company_name)
{

    global $dbh;

    $data = array();
    $errors = array();

    if (empty($vehicle_id) || empty($vehicle_name) || empty($registration_number) || empty($category) || empty($company_name))
        $errors['error'] = "There are missing entries in your request";

    if (!empty($errors)) {
        $data['success'] = false;
        $data['message'] = $errors;
    } else {
        $stmt = "UPDATE `vehicles` SET 
                    vehicle_name = :vehicle_name,
                    registration_number = :registration_number,
                    category = :category,
                    vehicle_company = :company_name,
                    updated_at = CURRENT_TIMESTAMP()
                WHERE vehicle_id=:vehicle_id";

        $query = $dbh->prepare($stmt);

        $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
        $query->bindParam(":vehicle_name", $vehicle_name, PDO::PARAM_STR);
        $query->bindParam(":registration_number", $registration_number, PDO::PARAM_STR);
        $query->bindParam(":category", $category, PDO::PARAM_STR);
        $query->bindParam(":company_name", $company_name, PDO::PARAM_STR);

        $query->execute();

        if ($query->rowCount() > 0) {
            $data['success'] = true;
            $data['message'] = "Changes updated successfully";
        } else {
            $data['success'] = false;
            $data['message'] = "Something is missing";
        }
    }
    return $data;
}
function to_edit_a_transcation($debit_or_credit_flag, $id_of_revenue_xpense, $charts_of_account, $new_amount)
{

    try {
        // Create a PDO connection
        global $dbh;

        $data = array();

        // Set PDO to throw exceptions on error
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($debit_or_credit_flag == 0) {
            $sql = "UPDATE vehicle_revenue 
                    SET revenue_generated = :new_amount,
                    revenue_id = :new_chart_of_acc,
                    updated_at = CURRENT_TIMESTAMP()
                    WHERE id = :id";
        } else {
            $sql = "UPDATE vehicle_expenses 
                    SET entry_incurred = :new_amount,
                    expense_id = :new_chart_of_acc,
                    updated_at = CURRENT_TIMESTAMP()
                    WHERE id = :id";

        }

        $query = $dbh->prepare($sql);

        $query->bindParam(':new_amount', $new_amount, PDO::PARAM_STR);
        $query->bindParam(':id', $id_of_revenue_xpense, PDO::PARAM_STR);
        $query->bindParam(':new_chart_of_acc', $charts_of_account, PDO::PARAM_STR);

        $query->execute();

        $data['success'] = true;
        $data['message'] = "Changes updated successfully.";

    } catch (PDOException $e) {

        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }

    return $data;
}

// get vehicle driver details to populate the datatables.
// parameter is the vehicle_id
// table to use is the vehicle_driver_conductors
function to_get_the_vehicle_driver_conductors_for_a_specific_vehicle($vehicle_id)
{
    global $dbh;

    $stmt = "SELECT drivers_license_no, vdc.created_at, vehicle_driver_conductors,
                CONCAT(vd.first_name,' ',vd.second_name,' ',vd.last_name) AS driver, 
                CONCAT(c.first_name,' ',c.second_name,' ',c.last_name) AS conductor, 
                vdc.leaseStartDate, vdc.leaseExpiryDate,
                DATEDIFF(leaseExpiryDate, leaseStartDate) as date_diff
            FROM vehicle_driver_conductors vdc
            LEFT JOIN vehicle_drivers vd ON vdc.driver_id = vd.vehicle_driver_id 
            LEFT JOIN conductors c ON vdc.conductor_id = c.conductor_id
            WHERE vdc.vehicle_id =:vehicle_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_OBJ);

    return $results;
}

function to_get_all_the_drivers_for_select2($searchTerms)
{
    global $dbh;

    if (!isset($searchTerms)) {
        $sql = "SELECT `vehicle_driver_id`, 
                        `drivers_license_no`, 
                        `first_name`, 
                        `second_name`, 
                        `last_name` 
                FROM `vehicle_drivers`
                WHERE isActive =" . STATUS;
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = "SELECT `vehicle_driver_id`, 
                         `drivers_license_no`,
                         `first_name`, 
                         `second_name`, 
                         `last_name` 
                    FROM `vehicle_drivers`
                    WHERE first_name 
                    LIKE :first_name AND
                    isActive =" . STATUS;
        $sql = $dbh->prepare($query);
        $sql->bindValue(":first_name", '%' . $searchTerms . '%', PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    $driver = array();

    foreach ($result as $r) {
        $driver[] = array(
            "id" => $r['vehicle_driver_id'],
            "text" => $r['first_name'] . " " . $r['second_name'] . " " . $r['last_name'] . " (" . $r['drivers_license_no'] . ") "
        );
    }
    return $driver;
}

function to_get_all_the_conductors_for_select2($searchTerms)
{
    global $dbh;

    if (!isset($searchTerms)) {
        $sql = "SELECT `conductor_id`, 
                        `first_name`, 
                        `second_name`, 
                        `last_name` 
                FROM `conductors`
                WHERE isActive =" . STATUS;
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = "SELECT `conductor_id`,
                         `first_name`, 
                         `second_name`, 
                         `last_name` 
                    FROM `conductors`
                    WHERE first_name 
                    LIKE :first_name AND
                    isActive =" . STATUS;
        $sql = $dbh->prepare($query);
        $sql->bindValue(":first_name", '%' . $searchTerms . '%', PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    $conductors = array();

    foreach ($result as $r) {
        $conductors[] = array(
            "id" => $r['conductor_id'],
            "text" => $r['first_name'] . " " . $r['second_name'] . " " . $r['last_name']
        );
    }
    return $conductors;
}

function to_insert_vehicle_driver($leaseStartDate, $vehicle_conductor_select, $vehicle_driver_select, $vehicle_id)
{
    try {

        global $dbh;

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = array();
        $id = bin2hex(random_bytes(30));

        if (empty($leaseStartDate) || empty($vehicle_conductor_select) || empty($vehicle_driver_select) || empty($vehicle_id))
            throw new Exception("Missing Entries.");

        $stmt = "INSERT INTO `vehicle_driver_conductors`
                 (`vehicle_driver_conductors`, `vehicle_id`, `driver_id`, `conductor_id`, `leaseStartDate`) 
                 VALUES (:id, :vehicle_id, :driver_id, :conductor_id, :leaseStartDate)";

        $query = $dbh->prepare($stmt);

        $query->bindParam(":id", $id, PDO::PARAM_STR);
        $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
        $query->bindParam(":driver_id", $vehicle_driver_select, PDO::PARAM_STR);
        $query->bindParam(":conductor_id", $vehicle_conductor_select, PDO::PARAM_STR);
        $query->bindParam(":leaseStartDate", $leaseStartDate, PDO::PARAM_STR);

        $query->execute();

        $data['success'] = true;
        $data['message'] = "Driver and conductor assigned succesfully";

    } catch (PDOException $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }

    return $data;
}

function stopLeaseQuery($vehicle_driver_conductors_id_from_table, $email, $password)
{
    $obje = checkAuthentication($email, $password);

    $data = array();
    global $dbh;

    if ($obje['success'] == true) {

        $updateStmt = "UPDATE `vehicle_driver_conductors` 
                        SET `leaseExpiryDate`=CURRENT_DATE,`updated_at`= CURRENT_TIMESTAMP
                        WHERE `vehicle_driver_conductors`.`vehicle_driver_conductors`=:vehicle_driver_conductors";

        $updateQuery = $dbh->prepare($updateStmt);
        $updateQuery->bindParam(":vehicle_driver_conductors", $vehicle_driver_conductors_id_from_table, PDO::PARAM_STR);

        $updateQuery->execute();

        $data['success'] = true;
        $data['message'] = $obje['message'] . "Changes updated successfully.";

    } else {
        $data['success'] = $obje['success'];
        $data['message'] = $obje['message'];
    }

    return $data;
}
function toDeleteAVehicle($vehicle_id, $email, $password)
{

    global $dbh;
    $data = array();

    $obje = checkAuthentication($email, $password);

    try {

        if (empty($vehicle_id)) {
            throw new Exception("Vehicle id cannot be empty");
        } elseif ($obje['success'] == true) {
            $stmt = "DELETE FROM vehicles
                     WHERE vehicle_id =:vehicle_id";

            $query = $dbh->prepare($stmt);
            $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
            $query->execute();

            $data['success'] = true;
            $data['message'] = "Vehicle Deleted Successfully.";
        } else {
            $data['success'] = $obje['success'];
            $data['message'] = $obje['message'];
        }

    } catch (PDOException $e) {
        $data['success'] = false;
        $data['message'] = $e;
    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e;
    }

    return $data;
}