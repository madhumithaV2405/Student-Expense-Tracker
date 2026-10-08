<?php

session_start();

/* Check whether login form was submitted */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    /* Check email and password */
    if ($email !== "" && $password !== "") {

        /* Save login information in session */
        $_SESSION["logged_in"] = true;
        $_SESSION["email"] = $email;

        /* Go to dashboard */
        header("Location: dashboard.php");
        exit();

    } else {

        $error = "Please enter email and password.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Expense Tracker</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="login-page">

<div class="login-screen">

    <div class="login-intro">

        <div class="brand">
            Expense<span>Track</span>
        </div>

        <h1>
            Welcome Back!
        </h1>

        <p>
            Login to manage your student expenses
            and financial activity.
        </p>

        <div class="login-decoration">
            ₹
        </div>

    </div>


    <div class="login-form-area">

        <div class="login-box">

            <h2>
                Student Expense Tracker
            </h2>

            <p class="form-subtitle">
                Login to continue
            </p>


            <?php if (isset($error)): ?>

                <div class="error-box">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST" action="login.php">

                <label for="email">
                    Email Address
                </label>

                <div class="input-wrapper">

                    <span>✉</span>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <label for="password">
                    Password
                </label>

                <div class="input-wrapper">

                    <span>🔒</span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="primary-button login-button"
                >
                    Login →
                </button>

            </form>


            <p class="demo-login">
                Demo login — any email and password
            </p>

        </div>

    </div>

</div>

</body>
</html>