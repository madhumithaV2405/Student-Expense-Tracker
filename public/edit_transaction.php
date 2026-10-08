<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";


$id = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;


$result = $conn->query(
    "SELECT * FROM transactions WHERE id = $id"
);


if ($result->num_rows == 0) {

    header("Location: report.php");
    exit();

}


$row = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $type = $_POST["type"];
    $category = $_POST["category"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $transaction_date = $_POST["transaction_date"];


    $stmt = $conn->prepare("
        UPDATE transactions
        SET type=?,
            category=?,
            amount=?,
            description=?,
            transaction_date=?
        WHERE id=?
    ");


    $stmt->bind_param(
        "ssdssi",
        $type,
        $category,
        $amount,
        $description,
        $transaction_date,
        $id
    );


    if ($stmt->execute()) {

        header("Location: report.php");
        exit();

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Transaction</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="dashboard-body">


<aside class="sidebar">

    <div class="sidebar-brand">
        <span>◉</span>
        Expense<span>Track</span>
    </div>

    <nav class="sidebar-menu">

        <a href="dashboard.php">
            <span>⌂</span>
            Dashboard
        </a>

        <a href="add_transaction.php"
           class="active">
            <span>＋</span>
            Add Transaction
        </a>

        <a href="report.php">
            <span>▣</span>
            Report
        </a>

        <a href="login.php"
           class="logout-link">
            <span>↪</span>
            Logout
        </a>

    </nav>

</aside>


<main class="dashboard-main">


    <div class="page-title">

        <span class="small-heading">
            UPDATE
        </span>

        <h1>
            Edit Transaction
        </h1>

        <p>
            Update your transaction information.
        </p>

    </div>


    <div class="form-card">

        <form method="POST">


            <label>
                Transaction Type
            </label>


            <div class="transaction-types">

                <label class="type-option">

                    <input
                        type="radio"
                        name="type"
                        value="income"
                        <?php
                        echo $row["type"] == "income"
                            ? "checked"
                            : "";
                        ?>
                        required
                    >

                    <span class="income-option">
                        ₹ &nbsp; Income
                    </span>

                </label>


                <label class="type-option">

                    <input
                        type="radio"
                        name="type"
                        value="expense"
                        <?php
                        echo $row["type"] == "expense"
                            ? "checked"
                            : "";
                        ?>
                        required
                    >

                    <span class="expense-option">
                        − &nbsp; Expense
                    </span>

                </label>

            </div>


            <label>
                Category
            </label>

            <select
                name="category"
                required
            >

                <?php

                $categories = [
                    "Food",
                    "Travel",
                    "Education",
                    "Shopping",
                    "Bills",
                    "Salary",
                    "Pocket Money",
                    "Other"
                ];


                foreach ($categories as $category) {

                    $selected =
                        $row["category"] == $category
                        ? "selected"
                        : "";

                    echo "
                        <option
                            value='$category'
                            $selected
                        >
                            $category
                        </option>
                    ";

                }

                ?>

            </select>


            <label>
                Amount
            </label>

            <div class="money-input">

                <span>₹</span>

                <input
                    type="number"
                    name="amount"
                    value="<?php
                    echo $row["amount"];
                    ?>"
                    step="0.01"
                    required
                >

            </div>


            <label>
                Description
            </label>

            <input
                type="text"
                name="description"
                value="<?php
                echo htmlspecialchars(
                    $row["description"]
                );
                ?>"
            >


            <label>
                Date
            </label>

            <input
                type="date"
                name="transaction_date"
                value="<?php
                echo $row["transaction_date"];
                ?>"
                required
            >


            <div class="form-actions">

                <a
                    href="report.php"
                    class="secondary-button"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="primary-button"
                >
                    Update Transaction
                </button>

            </div>

        </form>

    </div>

</main>

</body>
</html>