<?php
$page_title = 'Taxes';
require_once('includes/load.php');
// Checking what level user has permission to view this page
page_require_level(2);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new tax value from the form
    $newTaxValue = $_POST['tax_value'];

    // Check if the new tax value is negative
    if ($newTaxValue < 0) {
        $session->msg('d', "Tax value cannot be negative.");
    } else {
        // Update the tax value in the database
        $sql = "UPDATE taxes SET value = {$newTaxValue} WHERE id = 1"; // Assuming the tax you want to update has ID 1
        if ($db->query($sql)) {
            $session->msg('s', "Tax updated successfully.");
            redirect('taxes.php'); // Redirect to the taxes page or change this to the appropriate page
        } else {
            $session->msg('d', "Failed to update tax.");
        }
    }
}

// Fetch the current tax value from the database
$tax = find_by_id('taxes', 1); // Assuming the tax you want to display has ID 1
$sql2 = "SELECT value FROM taxes WHERE id = 1";
$taxes = $conn->query($sql2);

if ($taxes) {
    $taxValue = $taxes->fetch_assoc()['value'];
} else {
    $taxValue = 0; // Default value if the query fails
}
?>

<?php include_once('layouts/header.php'); ?>

<script>
    function validateTaxValue() {
        var taxValue = parseFloat(document.getElementById('tax_value').value);
        if (taxValue < 0) {
            alert('Tax value cannot be negative.');
            document.getElementById('tax_value').value = <?php echo $tax['value']; ?>;
            return false;
        }
    }
</script>

<form method="post" onsubmit="return validateTaxValue();">

    <div class="form-group" style="margin-left:25%;"    >
        <label for="tax_value">Shipping taxes : <?php echo number_format($taxValue, 2); ?> L.E</label>
        <input style="width:20%;" type="number" step="0.01" class="form-control" id="tax_value" name="tax_value" >
    </div>
    <button type="submit" class="btn btn-primary" style="margin-left:25%;">Update</button>
</form>

<?php include_once('layouts/footer.php'); ?>
