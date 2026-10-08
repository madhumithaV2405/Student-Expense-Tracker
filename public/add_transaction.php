<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $type = $_POST["type"];
    $category = $_POST["category"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $transaction_date = $_POST["transaction_date"];


    $stmt = $conn->prepare("
        INSERT INTO transactions
        (type, category, amount, description, transaction_date)
        VALUES (?, ?, ?, ?, ?)
    ");


    $stmt->bind_param(
        "ssdss",
        $type,
        $category,
        $amount,
        $description,
        $transaction_date
    );


    if ($stmt->execute()) {

        header("Location: dashboard.php");
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

    <title>Add Transaction</title>

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

        <div>

            <span class="small-heading">
                TRANSACTION
            </span>

            <h1>
                Add Transaction
            </h1>

            <p>
                Enter your income or expense details below.
            </p>

        </div>

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
                        required
                    >

                    <span class="expense-option">
                        − &nbsp; Expense
                    </span>

                </label>

            </div>


            <label for="category">
                Category
            </label>

            <select
                name="category"
                id="category"
                required
            >

                <option value="">
                    Select Category
                </option>

                <option value="Food">
                    Food
                </option>

                <option value="Travel">
                    Travel
                </option>

                <option value="Education">
                    Education
                </option>

                <option value="Shopping">
                    Shopping
                </option>

                <option value="Bills">
                    Bills
                </option>

                <option value="Salary">
                    Salary
                </option>

                <option value="Pocket Money">
                    Pocket Money
                </option>

                <option value="Other">
                    Other
                </option>

            </select>


            <label for="amount">
                Amount
            </label>

            <div class="money-input">

                <span>₹</span>

                <input
                    type="number"
                    name="amount"
                    id="amount"
                    placeholder="Enter amount"
                    min="0"
                    step="0.01"
                    required
                >

            </div>


            <label for="description">
                Description
            </label>

            <input
                type="text"
                name="description"
                id="description"
                placeholder="Example: Part-time job payment"
            >


            <label for="transaction_date">
                Date
            </label>

            <input
                type="date"
                name="transaction_date"
                id="transaction_date"
                required
            >


            <div class="form-actions">

                <a href="dashboard.php"
                   class="secondary-button">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="primary-button"
                >
                    Save Transaction
                </button>

            </div>

        </form>

    </div>

</main>

</body>
</html>