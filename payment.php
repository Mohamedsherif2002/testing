<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="libs/css/main.css">
    <title>Payment</title>
</head>
<body>
<?php

$page_title = 'Payment';
require_once('includes/load.php');
include_once('layouts/header.php');
?>

<?php

if ( isset($_SESSION['finalTotal'])) {
    
    $finalTotal = $_SESSION['finalTotal']; // Retrieve the value from the session
} else {
    echo "Final total not set!";
}
$tax = "SELECT value FROM taxes WHERE id = 1";
$taxes = $conn->query($tax);

if ($taxes) {
    $taxValue = $taxes->fetch_assoc()['value'];
} else {
    $taxValue = 0; // Default value if the query fails
}
?>
<form method="post" action="process_payment.php">
<section class="h-100 h-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card3">
                    <div class="card3-body p-4">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="card4 bg-primary text-white rounded-3">
                                    <div class="card-body">
                                        <h2 style="margin-bottom:20px;">Choose Payment Method</h2>

                                        <div class="d-flex justify-content-between">
                                            <p class="mb-2">Subtotal : <?php echo number_format($finalTotal, 2); ?> $</p>
                                            <p class="mb-2"></p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                        <p class="mb-2">Shipping : <?php echo number_format($taxValue, 2); ?> $</p>
                                        </div>
                                        <div class="d-flex justify-content-between mb-4">
                                            <p class="mb-2">Total ( Include Taxes ) : <?php echo number_format($finalTotal + $taxValue, 2); ?> $</p>
                                        </div>

                                        <hr>        
                       
                                            <div class="d-flex justify-content-between align-items-center mb-6" >
                                                <h4 class="mb-0">Card details (optional)</h4>
                                            </div>
                                                <div class="form-outline form-white mb-4"  >
                                                <input type="text" id="typeName" name="cardholder_name" class="form-control" size="17" placeholder="Cardholder's Name" />
                                                    <label class="form-label" for="typeName">Cardholder's Name</label>
                                                </div>

                                                <div class="form-outline form-white mb-4"  >
                                                <input type="text" id="typeText" name="card_number" class="form-control form-control-lg" size="16" placeholder="1234 5678 9012 3457" minlength="16" maxlength="16" />
                                                    <label class="form-label" for="typeText">Card Number</label>
                                                </div>
                                                <div class="row mb-4">
                                                    <div class="col-md-6"  >
                                                        <div class="form-outline form-white">
                                                        <input type="text" id="typeExp" name="card_expiration" class="form-control form-control-lg" placeholder="MM/YYYY" size="7" id="exp" minlength="7" maxlength="7" />
                                                            <label class="form-label" for="typeExp">Expiration</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6"  >
                                                        <div class="form-outline form-white">
                                                        <input type="password" id="typeText" name="card_cvv" class="form-control form-control-lg" placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" maxlength="3" />
                                                            <label class="form-label" for="typeText">CVV</label>
                                                        </div>
                                                    </div>
                                                </div>
<hr>
                                                <div class="row">
                                                    <div class="col-md-8 order-md-1">
                                                        <h4 class="mb-0">Billing address</h4>
                                                        <form class="needs-validation" novalidate="">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="firstName">First name</label>
                                                                    <input type="text" class="form-control" id="firstName" name="first_name" placeholder="" value="" maxlength="10" required />
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="lastName">Last name</label>
                                                                    <input type="text" class="form-control" id="lastName" name="last_name" placeholder="" value="" maxlength="10" required />
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="mb-3">
                                                                <label for="number">Number <span class="text-muted"></span></label>
                                                                <input type="text" class="form-control" id="number" name="number" placeholder="" pattern="[0-9]{11}" required/>
                                                            </div>
                                                            <br>
                                                            <div class="mb-3">
                                                                <label for="email">Email <span style="color:black;">(Optional)</span></label>
                                                                <input type="email" class="form-control" id="email" name="email" placeholder="" />
                                                                <div class="invalid-feedback"> Please enter a valid email address for shipping updates.
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="mb-3">
                                                                <label for="address">Address</label>
                                                                <input type="text" class="form-control" id="address" name="address" placeholder=""  required/>
                                                                <div class="invalid-feedback"> Please enter your shipping address.
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="mb-3">
                                                                <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                                                                <input type="text" class="form-control" id="address2" name="address2" placeholder="" />
                                                            </div>
                                                            
                                                            <br>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="final_total" value="<?php echo $finalTotal; ?>">                                        
                                        <button class="btn btn-primary" type="submit" name="checkout_product">Checkout</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</section>
</form>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const cardholderName = document.getElementById("typeName");
    const cardNumber = document.getElementById("typeText");
    const cardExpiration = document.getElementById("typeExp");
    const cardCVV = document.getElementById("typeCVV");

    cardholderName.addEventListener("input", toggleRequired);
    cardNumber.addEventListener("input", toggleRequired);
    cardExpiration.addEventListener("input", toggleRequired);
    cardCVV.addEventListener("input", toggleRequired);

    function toggleRequired() {
        // Check if any of the card fields has a value
        const anyFieldHasValue =
            cardholderName.value.trim() !== "" ||
            cardNumber.value.trim() !== "" ||
            cardExpiration.value.trim() !== "" ||
            cardCVV.value.trim() !== "";

        // Add or remove the 'required' attribute based on the presence of a value
        cardholderName.required = anyFieldHasValue;
        cardNumber.required = anyFieldHasValue;
        cardExpiration.required = anyFieldHasValue;
        cardCVV.required = anyFieldHasValue;
    }
});
</script>

<?php include_once('layouts/footer.php');
?>
</body>
</html>
