<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";


$income = $conn->query("
    SELECT COALESCE(SUM(amount),0) total
    FROM transactions
    WHERE type='income'
")->fetch_assoc()["total"];


$expense = $conn->query("
    SELECT COALESCE(SUM(amount),0) total
    FROM transactions
    WHERE type='expense'
")->fetch_assoc()["total"];


$balance = $income - $expense;


$transactions = $conn->query("
    SELECT *
    FROM transactions
    ORDER BY transaction_date DESC, id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Monthly Report</title>

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

        <a href="add_transaction.php">
            <span>＋</span>
            Add Transaction
        </a>

        <a href="report.php"
           class="active">
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


    <header class="dashboard-header">

        <div>

            <span class="small-heading">
                FINANCIAL REPORT
            </span>

            <h1>
                Monthly Report
            </h1>

            <p>
                Complete overview of your transactions.
            </p>

        </div>


        <a href="add_transaction.php"
           class="primary-button">
            ＋ Add Transaction
        </a>

    </header>


    <section class="summary-cards">


        <div class="summary-card income-summary">

            <div class="summary-icon">
                ₹
            </div>

            <div>

                <small>Total Income</small>

                <h2>
                    ₹<?php
                    echo number_format($income, 2);
                    ?>
                </h2>

            </div>

        </div>


        <div class="summary-card expense-summary">

            <div class="summary-icon">
                −
            </div>

            <div>

                <small>Total Expenses</small>

                <h2>
                    ₹<?php
                    echo number_format($expense, 2);
                    ?>
                </h2>

            </div>

        </div>


        <div class="summary-card balance-summary">

            <div class="summary-icon">
                ✓
            </div>

            <div>

                <small>Balance</small>

                <h2>
                    ₹<?php
                    echo number_format($balance, 2);
                    ?>
                </h2>

            </div>

        </div>

    </section>


    <section class="transactions-section">


        <div class="section-heading">

            <h2>
                All Transactions
            </h2>

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
                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (
                    $transactions->num_rows > 0
                ): ?>

                    <?php while (
                        $row =
                        $transactions->fetch_assoc()
                    ): ?>

                        <tr>

                            <td>
                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $row["transaction_date"]
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["category"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["description"]
                                );
                                ?>
                            </td>

                            <td>

                                <?php if (
                                    $row["type"]
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

                                echo $row["type"] == "income"
                                    ? "income-amount"
                                    : "expense-amount";

                            ?>">

                                <?php

                                echo $row["type"] == "income"
                                    ? "+ ₹" .
                                      number_format(
                                          $row["amount"],
                                          2
                                      )
                                    : "- ₹" .
                                      number_format(
                                          $row["amount"],
                                          2
                                      );

                                ?>

                            </td>


                            <td class="table-actions">

                                <a
                                    href="edit_transaction.php?id=<?php
                                    echo $row["id"];
                                    ?>"
                                    class="edit-button"
                                >
                                    Edit
                                </a>


                                <a
                                    href="delete_transaction.php?id=<?php
                                    echo $row["id"];
                                    ?>"
                                    class="delete-button"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="6"
                            class="empty-row"
                        >
                            No transactions available.
                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>

</body>
</html>