<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";


/* TOTAL INCOME */

$result = $conn->query("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM transactions
    WHERE type = 'income'
");

$totalIncome = $result->fetch_assoc()["total"];


/* TOTAL EXPENSE */

$result = $conn->query("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM transactions
    WHERE type = 'expense'
");

$totalExpense = $result->fetch_assoc()["total"];


/* BALANCE */

$balance = $totalIncome - $totalExpense;


/* RECENT TRANSACTIONS */

$recentTransactions = $conn->query("
    SELECT *
    FROM transactions
    ORDER BY transaction_date DESC, id DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Expense Tracker</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="dashboard-body">


<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="sidebar-brand">
        <span>◉</span>
        Expense<span>Track</span>
    </div>


    <nav class="sidebar-menu">

        <a href="dashboard.php"
           class="active">
            <span>⌂</span>
            Dashboard
        </a>

        <a href="add_transaction.php">
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


<!-- MAIN -->

<main class="dashboard-main">


    <header class="dashboard-header">

        <div>

            <h1>
                Student Expense Dashboard
            </h1>

            <p>
                Welcome back! Here's your financial summary.
            </p>

        </div>


        <a href="add_transaction.php"
           class="primary-button">

            ＋ Add Transaction

        </a>

    </header>


    <!-- SUMMARY CARDS -->

    <section class="summary-cards">


        <div class="summary-card income-summary">

            <div class="summary-icon">
                ₹
            </div>

            <div>

                <small>
                    Total Income
                </small>

                <h2>
                    ₹<?php
                    echo number_format(
                        $totalIncome,
                        2
                    );
                    ?>
                </h2>

                <span>
                    Money received
                </span>

            </div>

        </div>


        <div class="summary-card expense-summary">

            <div class="summary-icon">
                −
            </div>

            <div>

                <small>
                    Total Expenses
                </small>

                <h2>
                    ₹<?php
                    echo number_format(
                        $totalExpense,
                        2
                    );
                    ?>
                </h2>

                <span>
                    Money spent
                </span>

            </div>

        </div>


        <div class="summary-card balance-summary">

            <div class="summary-icon">
                ✓
            </div>

            <div>

                <small>
                    Balance
                </small>

                <h2>
                    ₹<?php
                    echo number_format(
                        $balance,
                        2
                    );
                    ?>
                </h2>

                <span>
                    Remaining amount
                </span>

            </div>

        </div>

    </section>


    <!-- QUICK ACTIONS -->

    <section>

        <div class="section-heading">
            <h2>Quick Actions</h2>
        </div>


        <div class="quick-actions">


            <a href="add_transaction.php"
               class="quick-card">

                <div class="quick-icon green">
                    ＋
                </div>

                <h3>
                    Add Transaction
                </h3>

                <p>
                    Record income or expense
                </p>

            </a>


            <a href="report.php"
               class="quick-card">

                <div class="quick-icon blue">
                    ▣
                </div>

                <h3>
                    Monthly Report
                </h3>

                <p>
                    View your financial report
                </p>

            </a>


            <a href="#transactions"
               class="quick-card"
               id="recentTransactionCard">

                <div class="quick-icon purple">
                    ↘
                </div>

                <h3>
                    Recent Transactions
                </h3>

                <p>
                    View latest transactions
                </p>

            </a>

        </div>

    </section>


    <!-- TRANSACTIONS -->

    <section id="transactions"
             class="transactions-section">


        <div class="section-heading">

            <h2>
                Recent Transactions
            </h2>

            <a href="report.php">
                View All →
            </a>

        </div>


        <div class="table-card">

            <table>

                <thead>

                    <tr>

                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (
                    $recentTransactions->num_rows > 0
                ): ?>

                    <?php while (
                        $transaction =
                        $recentTransactions->fetch_assoc()
                    ): ?>

                        <tr>

                            <td>
                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $transaction[
                                            "transaction_date"
                                        ]
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $transaction["category"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $transaction["description"]
                                );
                                ?>
                            </td>

                            <td>

                                <?php if (
                                    $transaction["type"]
                                    == "income"
                                ): ?>

                                    <span class="badge income-badge">
                                        Income
                                    </span>

                                <?php else: ?>

                                    <span class="badge expense-badge">
                                        Expense
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="<?php

                                echo $transaction["type"]
                                    == "income"
                                    ? "income-amount"
                                    : "expense-amount";

                            ?>">

                                <?php

                                if (
                                    $transaction["type"]
                                    == "income"
                                ) {

                                    echo "+ ₹" .
                                        number_format(
                                            $transaction["amount"],
                                            2
                                        );

                                } else {

                                    echo "- ₹" .
                                        number_format(
                                            $transaction["amount"],
                                            2
                                        );

                                }

                                ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5"
                            class="empty-row">

                            No transactions found.
                            Add your first transaction.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<script src="js/dashboard.js"></script>

</body>
</html>