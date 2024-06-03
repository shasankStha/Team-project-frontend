<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Panel - Products</title>


    <?php
    session_start();
    if (!isset($_SESSION["traderUser"])) {
        header("Location: ../login/login.php");
        exit;
    }
    include("../connection.php");
    include("traderdashboardheader.php");
    $shopID = $_SESSION['shopID'];
    $traderUser = $_SESSION["traderUser"];
    $sql = "SELECT t.status FROM \"USER\" u inner join trader t on t.user_id = u.user_id where u.username = '$traderUser' or u.email='$traderUser'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $status = null;
    if ($row = oci_fetch_assoc($stid)) {
        $status = $row['STATUS'];
        // echo "<script>alert('" . addslashes($status) . "');</script>";
    }
    if ($status != 1) {
        echo "<script>alert('You have not yet been approved by admin.')</script>";
        echo "<script>window.location.href = 'dashboard.php';</script>";
        exit;
    }

    // Handle delete action
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $productID = $_GET['id'];
        $newProductName = "delete " . $productID;
        $sql = "UPDATE product SET STATUS = '-1', name = '$newProductName' WHERE PRODUCT_ID = :productID";
        $stid = oci_parse($connection, $sql);
        oci_bind_by_name($stid, ':productID', $productID);
        oci_execute($stid);
        echo "<script>alert('Product deleted successfully!');</script>";
        header("Location: products.php");
    }


    ?>
    <?php
    // require('inc/links.php') 
    // Fetch categories from the database
    $categories = [];
    $sql = "SELECT category_name FROM PRODUCT_CATEGORY"; // Adjust this query according to your table structure
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_assoc($stid)) {
        $categories[] = $row['CATEGORY_NAME'];
    }
    oci_free_statement($stid);
    oci_close($connection);

    $selectedProductName = null;
    $selectedProductPrice = null;
    $selectedProductStock = null;
    $selectedProductMinOrder = null;
    $selectedProductMaxOrder = null;
    $selectedProductCat = null;
    $selectedProductDescription = null;
    $selectedProductAllergyInfo = null;
    $shop_id = null;

    ?>





</head>

<body>


    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>Products</h3>

                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">ADD PRODUCTS</h5>
                            <button type="button" class="btn btn-dark shadown-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-s">
                                <i class="bi bi-plus-square"></i> Add
                            </button>

                        </div>

                    </div>
                </div>


                <div class="modal fade" id="add-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" enctype="multipart/form-data" name="productForm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Product</h5>
                                </div>
                                <div class="modal-body">
                                    <fieldset>
                                        <legend>Product Details</legend>

                                        <div class="mb-3">
                                            <label class="form-label">Product Name:</label>
                                            <input type="text" class="form-control shadown-none" name="pName" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price:</label>
                                            <input type="text" class="form-control shadown-none" name="pPrice" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stock Available</label>
                                            <input type="number" class="form-control shadown-none" name="pStock" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Min Order</label>
                                            <input type="number" class="form-control shadown-none" name="pMinOrder" min="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Max order</label>
                                            <input type="number" class="form-control shadown-none" name="pMaxOrder" min="1" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Category:</label>
                                            <select name="pCategory">
                                                <option>--Select category--</option>
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?php echo htmlspecialchars($category); ?>">
                                                        <?php echo htmlspecialchars($category); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image file:</label>
                                            <input type="file" class="form-control shadown-none" name="pImage">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <input type="text" class="form-control shadown-none" name="pDescription" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Allergy Information</label>
                                            <input type="text" class="form-control shadown-none" name="pAllergyInfo" required>
                                        </div>

                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                                    <button type="submit" name="add" class="btn btn-primary">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <?php
    include("../connection.php");
    // echo "<script>alert('" . addslashes($traderUser) . "');</script>";


    if (isset($_POST["add"])) {
        $sql = "select shop_id from shop s inner join \"USER\" u on u.user_id = s.user_id where u.username = '$traderUser' or u.email='$traderUser' ";
        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
        // echo "<script>alert($username)</script>";
        if ($row = oci_fetch_assoc($stid)) {
            $shop_id = $row['SHOP_ID'];
            // echo "<script>alert('" . addslashes($shop_id) . "');</script>";
        }
        $productName = $_POST['pName'];
        $productPrice = (float) $_POST['pPrice'];
        $productStock = (int) $_POST['pStock'];
        $productMinOrder = (int) $_POST['pMinOrder'];
        $productMaxOrder = (int) $_POST['pMaxOrder'];
        $productCat = $_POST['pCategory'];
        $productDescription = $_POST['pDescription'];
        $productAllergyInfo = $_POST['pAllergyInfo'];

        //capturing the file information
        $imgName = $_FILES["pImage"]["name"];
        $imgSize = $_FILES["pImage"]["size"];
        $imgType = $_FILES["pImage"]["type"];
        $tmp = $_FILES["pImage"]["tmp_name"];

        // echo "<script>alert('" . addslashes($productCat) . "');</script>";

        if (empty($productName) || empty($productPrice) || empty($productStock || empty($productMinOrder) || empty($productMaxOrder) || empty($productCat) || empty($productDescription) || empty($productAllergyInfo))) {
            echo "Please fill all the fields.<br>";
        } else if (empty($imgName)) {
            // echo "Please upload item image<br>";
        } else if ($imgType == "pImage/jpeg" || $imgType == "pImage/jpg" || $imgType == "pImage/gif" || $imgType == "pImage/png") {
            echo "Unsupported file format";
        } else {
            $uploadfile = "productsImages/" . $imgName;
            if (move_uploaded_file($tmp, $uploadfile)) {
                // echo "Image Uploaded<br>";
                // echo "<img src=productsImages/$imgName width=200px height=200px>";
            }
        }
        // echo "<script>alert('" . addslashes($productCat) . "');</script>";

        $sql = "select CATEGORY_ID from PRODUCT_CATEGORY where CATEGORY_NAME = '$productCat'";
        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
        $productCatid = null;
        if ($row = oci_fetch_assoc($stid)) {

            $productCatid = $row['CATEGORY_ID'];
        }
        $sql = "INSERT INTO product values(null,'$productName','$imgName','$productDescription','$productPrice','$productStock', '$productMinOrder', '$productMaxOrder', '$productAllergyInfo', null, 1,'$shop_id', '$productCatid')";
        $stid = oci_parse($connection, $sql);
        if (oci_execute($stid)) {
            echo "<script>alert('Product added successfully!!!');</script>";
        } else {
            echo "<script>alert('The product should be unique.');</script>";
            echo "<script>window.location.href=window.location.href;</script>";
        }
    }
    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">MANAGE PRODUCTS</h5>

                        </div>
                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">S.N</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Picture</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Stock Available</th>
                                        <th scope="col">Min Order</th>
                                        <th scope="col">Max Order</th>
                                        <th scope="col">DISCOUNT</th>
                                        <th scope="col">EDIT</th>
                                        <th scope="col">Action</th>

                                    </tr>
                                </thead>
                                <tbody id="users-data">
                                    <?php
                                    $query = "SELECT PRODUCT_ID, NAME, IMAGE, PRICE, STOCK_AVAILABLE, MIN_ORDER, MAX_ORDER, DISCOUNT, DESCRIPTION, ALLERGY_INFORMATION FROM PRODUCT WHERE SHOP_ID = '$shopID' and status = '1' order by product_id";
                                    $stid = oci_parse($connection, $query);
                                    oci_execute($stid);

                                    $sn = 1;  // Serial Number counter
                                    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                                        echo "<tr>\n";
                                        echo "    <td>" . htmlspecialchars($sn++) . "</td>\n";
                                        echo "    <td>" . htmlspecialchars($row['NAME']) . "</td>\n";
                                        echo "    <td><img src='productsImages/" . htmlspecialchars($row['IMAGE']) . "' alt='Product Image' style='width:220px; height:160px;'></td>\n";
                                        echo "    <td>£ " . htmlspecialchars($row['PRICE']) . "</td>\n";
                                        echo "    <td>" . htmlspecialchars($row['STOCK_AVAILABLE']) . "</td>\n";
                                        echo "    <td>" . htmlspecialchars($row['MIN_ORDER']) . "</td>\n";
                                        echo "    <td>" . htmlspecialchars($row['MAX_ORDER']) . "</td>\n";
                                        echo "    <td>" . htmlspecialchars($row['DISCOUNT']) . "</td>\n";
                                        echo "    <td><button type='button' class='btn btn-dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-modal' 
                                                    data-name='" . htmlspecialchars($row['NAME']) . "' 
                                                    data-price='" . htmlspecialchars($row['PRICE']) . "'
                                                    data-stock='" . htmlspecialchars($row['STOCK_AVAILABLE']) . "'
                                                    data-minorder='" . htmlspecialchars($row['MIN_ORDER']) . "'
                                                    data-maxorder='" . htmlspecialchars($row['MAX_ORDER']) . "'
                                                    data-discount='" . htmlspecialchars($row['DISCOUNT']) . "'
                                                    data-description='" . htmlspecialchars($row['DESCRIPTION']) . "'
                                                    data-allergyinfo='" . htmlspecialchars($row['ALLERGY_INFORMATION']) . "'
                                                    data-image='" . htmlspecialchars($row['IMAGE']) . "'
                                                    data-id='" . htmlspecialchars($row['PRODUCT_ID']) . "'> 
                                                    <i class='bi bi-pencil-square'></i> EDIT</button></td>\n";
                                        echo "    <td><a class='btn btn-danger' href='?action=delete&id=" . htmlspecialchars($row['PRODUCT_ID']) . "' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a></td>\n";
                                        // echo "    <td><button type='button' class='btn btn-dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#review-modal'> <i class='bi bi-pencil-square'></i> View Reviews</button></td>\n";
                                        echo "</tr>\n";
                                    }
                                    ?>
                            </table>
                        </div>
                    </div>
                </div>
                <form method="POST" enctype="multipart/form-data" name="productForm">
                    <div class="modal fade" id="edit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <fieldset>
                                        <legend>Product Details</legend>
                                        <input type="hidden" name="productId">
                                        <input type="hidden" name="existingImage">
                                        <div class="mb-3">
                                            <label class="form-label">Product Name:</label>
                                            <input type="text" class="form-control shadow-none" name="pName">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price:</label>
                                            <input type="text" class="form-control shadow-none" name="pPrice">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stock Available:</label>
                                            <input type="text" class="form-control shadow-none" name="pStock">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Min Order:</label>
                                            <input type="number" class="form-control shadow-none" name="pMinOrder" min="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Max Order:</label>
                                            <input type="number" class="form-control shadow-none" name="pMaxOrder" min="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image file:</label>
                                            <input type="file" class="form-control shadow-none" name="pImage">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Discount:</label>
                                            <input type="number" class="form-control shadow-none" name="pDiscount">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description:</label>
                                            <input type="text" class="form-control shadow-none" name="pDescription" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Allergy Information:</label>
                                            <input type="text" class="form-control shadow-none" name="pAllergyInfo" required>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                                    <button type="submit" name="updateProduct" class="btn btn-primary">SUBMIT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="modal fade" id="review-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Reviews</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    </div>

    <?php
    // After your existing form processing code
    if (isset($_POST['updateProduct'])) {
        // Get the form data
        $pName = $_POST['pName'];
        $pPrice = $_POST['pPrice'];
        $pStock = $_POST['pStock'];
        $pMinOrder = $_POST['pMinOrder'];
        $pMaxOrder = $_POST['pMaxOrder'];
        $pDiscount = $_POST['pDiscount'];
        $pDescription = $_POST['pDescription'];
        $pAllergyInfo = $_POST['pAllergyInfo'];
        $pImage = $_FILES['pImage']['name'];
        $existingImage = $_POST['existingImage'];

        // Assuming you have a hidden field for the product ID
        $productId = $_POST['productId'];

        // Handle file upload if a new image is provided
        if ($pImage) {
            $targetDir = "productsImages/";
            $targetFile = $targetDir . basename($pImage);
            if (move_uploaded_file($_FILES['pImage']['tmp_name'], $targetFile)) {
                echo "New image uploaded: $targetFile<br>";
            } else {
                echo "Error uploading image.<br>";
            }
        } else {
            // Use existing image if no new image is provided
            $pImage = $existingImage;
        }

        // Update the product in the database
        $query = "UPDATE PRODUCT SET 
                NAME = :name, 
                PRICE = :price, 
                STOCK_AVAILABLE = :stock, 
                MIN_ORDER = :minOrder, 
                MAX_ORDER = :maxOrder, 
                DISCOUNT = :discount, 
                DESCRIPTION = :description, 
                ALLERGY_INFORMATION = :allergyInfo, 
                IMAGE = :image 
            WHERE PRODUCT_ID = :productId";

        $stid = oci_parse($connection, $query);

        // Binding variables
        oci_bind_by_name($stid, ':name', $pName);
        oci_bind_by_name($stid, ':price', $pPrice);
        oci_bind_by_name($stid, ':stock', $pStock);
        oci_bind_by_name($stid, ':minOrder', $pMinOrder);
        oci_bind_by_name($stid, ':maxOrder', $pMaxOrder);
        oci_bind_by_name($stid, ':discount', $pDiscount);
        oci_bind_by_name($stid, ':description', $pDescription);
        oci_bind_by_name($stid, ':allergyInfo', $pAllergyInfo);
        oci_bind_by_name($stid, ':image', $pImage);
        oci_bind_by_name($stid, ':productId', $productId);

        // Execute the query and check for errors
        if (oci_execute($stid)) {
            echo "Product updated successfully!<br>";
            // Redirect or display a success message
            echo "<script>window.location.href = window.location.href + '?success=1';</script>";
            exit;
        } else {
            $e = oci_error($stid);  // For oci_execute errors pass the statement handle
            echo htmlentities($e['message']);
        }
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('edit-modal');
            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var name = button.getAttribute('data-name');
                var price = button.getAttribute('data-price');
                var stock = button.getAttribute('data-stock');
                var minOrder = button.getAttribute('data-minorder');
                var maxOrder = button.getAttribute('data-maxorder');
                var discount = button.getAttribute('data-discount');
                var description = button.getAttribute('data-description');
                var allergyInfo = button.getAttribute('data-allergyinfo');
                var image = button.getAttribute('data-image');
                var productId = button.getAttribute('data-id');

                var modal = this;
                modal.querySelector('input[name="pName"]').value = name;
                modal.querySelector('input[name="pPrice"]').value = price;
                modal.querySelector('input[name="pStock"]').value = stock;
                modal.querySelector('input[name="pMinOrder"]').value = minOrder;
                modal.querySelector('input[name="pMaxOrder"]').value = maxOrder;
                modal.querySelector('input[name="pDiscount"]').value = discount;
                modal.querySelector('input[name="pDescription"]').value = description;
                modal.querySelector('input[name="pAllergyInfo"]').value = allergyInfo;
                modal.querySelector('input[name="productId"]').value = productId;
                modal.querySelector('input[name="existingImage"]').value = image;
            });
        });
    </script>



</body>

</html>