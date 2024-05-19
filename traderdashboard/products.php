<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Panel - Products</title>


    <?php
    session_start();
    include("../connection.php");
    include("traderdashboardheader.php");
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

    ?>





</head>

<body>


    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
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
                                            <input type="number" class="form-control shadown-none" name="pMinOrder" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Max order</label>
                                            <input type="number" class="form-control shadown-none" name="pMaxOrder" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Category:</label>
                                            <select name="pCategory">
                                                <option>--Select category--</option>
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
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
        $productPrice = (int)$_POST['pPrice'];
        $productStock = (int)$_POST['pStock'];
        $productMinOrder = (int)$_POST['pMinOrder'];
        $productMaxOrder = (int)$_POST['pMaxOrder'];
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
            echo "Please upload item image<br>";
        } else if ($imgType == "pImage/jpeg" || $imgType == "pImage/jpg" || $imgType == "pImage/gif" || $imgType == "pImage/png") {
            echo "Unsupported file format";
        } else {
            $uploadfile = "productsImages/" . $imgName;
            if (move_uploaded_file($tmp, $uploadfile)) {
                echo "<script>alert('Product added successfully!!!');</script>";
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
        $sql = "insert into product values(null,'$productName','$imgName','$productDescription','$productPrice','$productStock', '$productMinOrder', '$productMaxOrder', '$productAllergyInfo', '1', '$shop_id', '$productCatid', null)";
        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
    }




    ?>



    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">MANAGE PRODUCTS</h5>
                            <button type="button" class="btn btn-dark shadown-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-s">
                                <i class="bi bi-pencil-square"></i> EDIT
                            </button>
                        </div>
                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">S.N</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Stock Available</th>
                                        <th scope="col">Min Order</th>
                                        <th scope="col">Max Order</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="room-data">
                                </tbody>
                            </table>
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
                                            <input type="text" class="form-control shadown-none" name="pName">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price:</label>
                                            <input type="text" class="form-control shadown-none" name="pPrice">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Category:</label>
                                            <select name="pCategory">
                                                <option>--Select category--</option>
                                                <option value="Fish Monger">Fish Monger</option>
                                                <option value="Butchery">Butchery</option>
                                                <option value="Bakery">Bakery</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image file:</label>
                                            <input type="text" class="form-control shadown-none" name="imgName" placeholder="Image Name">
                                            <input type="file" class="form-control shadown-none" name="pImage">
                                        </div>

                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                                    <button type="button" class="btn btn-primary">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>

</html>