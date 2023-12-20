<?php
$page_title = 'Add Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');

// Check if the form is submitted
if (isset($_POST['add_product'])) {
    $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price', 'Description');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['buying-price']));
        $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
        $Description = remove_junk($db->escape($_POST['Description']));

        if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
            $media_id = '4';
        } else {
            $media_id = remove_junk($db->escape($_POST['product-photo']));
        }

        // Check if the product already exists in the database
        $stm = $pdo->prepare("SELECT COUNT(name) FROM products WHERE name = :p_name LIMIT 1");
        $stm->bindParam(':p_name', $p_name);
        $stm->execute();
        $result = $stm->fetchColumn();

        if ($result == 1) {
            // Product already exists, set a session message
            $session->msg('w', "Product already exists!");
        } else {
            // Product doesn't exist, create it
            doCreateProduct($p_name, $p_qty, $p_buy, $p_sale, $p_cat, $media_id, $pdo, $db, $session, $Description);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
    }
}

function doCreateProduct($name, $qty, $buy, $sale, $cat, $media, $pdo, $db, $session, $Description)
{
    $date = make_date();
    $query = "INSERT INTO products (";
    $query .= "name, quantity, buy_price, sale_price, categorie_id, Description, media_id, date";
    $query .= ") VALUES (";
    $query .= "'{$name}', '{$qty}', '{$buy}', '{$sale}', '{$cat}', '{$Description}', '{$media}', '{$date}'";
    $query .= ")";

    if ($db->query($query)) {
        $session->msg('s', "Product added");
        redirect('add_product.php', false);
    } else {
        $session->msg('d', 'Sorry failed to add!');
        redirect('product.php', false);
    }
}

?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row" style="margin-left:20%;">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Add New Product</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <form method="post" action="add_product.php" class="clearfix">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" >
                                    <i class="glyphicon glyphicon-th-large"></i>
                                </span>
                                <input type="text" class="form-control" name="product-title"
                                    placeholder="Product Title">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control" name="product-categorie">
                                        <option value="">Select Product Category</option>
                                        <?php foreach ($all_categories as $cat): ?>
                                        <option value="<?php echo (int)$cat['id'] ?>">
                                            <?php echo $cat['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" name="product-photo">
                                        <option value="">Select Product Photo</option>
                                        <?php foreach ($all_photo as $photo): ?>
                                        <option value="<?php echo (int)$photo['id'] ?>">
                                            <?php echo $photo['file_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p style="color:red;font-size:1px;">The photo must be jpg</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-shopping-cart"></i>
                                        </span>
                                        <input type="number" class="form-control" name="product-quantity"
                                            placeholder="Product Quantity">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-usd"></i>
                                        </span>
                                        <input type="number" class="form-control" name="buying-price"
                                            placeholder="Buying Price">
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                </div>
                                <div class="col-md-4" >
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-usd"></i>
                                        </span>
                                        <input type="number" class="form-control" name="saleing-price"
                                            placeholder="Selling Price">
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-th-large"></i>
                                </span>
                                <input type="text" class="form-control" name="Description"
                                    placeholder="Description">
                            </div>
                        </div>
                        <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
