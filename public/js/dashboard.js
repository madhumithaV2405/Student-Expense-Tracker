document.addEventListener("DOMContentLoaded", function () {

    const recentCard =
        document.getElementById("recentTransactionCard");

    const transactionSection =
        document.getElementById("transactions");


    if (recentCard && transactionSection) {

        recentCard.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                transactionSection.scrollIntoView({
                    behavior: "smooth"
                });

            }
        );

    }

});