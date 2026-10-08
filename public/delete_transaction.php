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


$transaction = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $stmt = $conn->prepare(
        "DELETE FROM transactions WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    header("Location: report.php");

    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Delete Transaction</title>

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


    <div class="delete-page">

        <div class="delete-box">

            <div class="delete-icon">
                🗑
            </div>

            <h1>
                Are you sure?
            </h1>

            <p>
                Do you want to delete this transaction?
            </p>

            <p class="warning-text">
                This action cannot be undone.
            </p>


            <div class="delete-preview">

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $transaction["category"]
                    );
                    ?>
                </strong>

                <span>
                    ₹<?php
                    echo number_format(
                        $transaction["amount"],
                        2
                    );
                    ?>
                </span>

            </div>


            <form method="POST">

                <div class="delete-actions">

                    <button
                        type="submit"
                        class="danger-button"
                    >
                        Delete
                    </button>

                    <a
                        href="report.php"
                        class="secondary-button"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</main>

</body>
</html>