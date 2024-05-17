<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Panel - Products</title>


    <?php
    session_start();
    include("traderdashboardheader.php");

    include("../connection.php");
    ?>
    <?php require('inc/links.php') ?>
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
                                    <button type="button" name="submit" class="btn btn-primary">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <?php
    if (isset($_POST['submit'])) {
        echo "<script>alert(123)</script>";
        $username = $_SESSION["trader"];
        $sql = "select shop_id from shop s inner join \"USER\" u on u.user_id = s.user_id where u.username = '$username'";
        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
        if ($row = oci_fetch_assoc($stid)) {
            $shop_id = $row['SHOP_ID'];
            echo "<script>alert($shop_id)</script>";
        }
        $productName = $_POST['pName'];
        $productPrice = $_POST['pPrice'];
        $productCat = $_POST['pCategory'];
        $productImgName = $_POST['imgName'];

        //capturing the file information
        $imgName = $_FILES["pImage"]["name"];
        $imgSize = $_FILES["pImage"]["size"];
        $imgType = $_FILES["pImage"]["type"];
        $tmp = $_FILES["pImage"]["tmp_name"];




        if (empty($productName) || empty($productPrice) || empty($productCat || empty($productImgName))) {
            echo "Please fill all the fields.<br>";
        } else if (empty($imgName)) {
            echo "Please upload item image<br>";
        } else if ($imgType == "pImage/jpeg" || $imgType == "pImage/jpg" || $imgType == "pImage/gif" || $imgType == "pImage/png") {
            echo "Unsupported file format";
        } else if ($productImgName != $imgName) {
            echo "The image you selected do not match with the image name you provided.<br>";
        } else {
            $uploadfile = "productsImages/" . $imgName;
            if (move_uploaded_file($tmp, $uploadfile)) {
                echo "Image Uploaded<br>";
                echo "<img src=productsImages/$imgName width=200px height=200px>";
            }
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
                            <button type="button" class="btn btn-dark shadown-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-s">
                                <i class="bi bi-pencil-square"></i> EDIT
                            </button>
                        </div>
                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Status</th>
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
                                    <button type="SUBMIT" class="btn btn-primary">SUBMIT</button>
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