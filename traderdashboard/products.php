<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products -CleckShopHub</title>
    <?php
    include ("traderdashboardheader.php");
    ?>

</head>

<body>
    <form method="POST" enctype="multipart/form-data" name="productForm">
        <fieldset>
            <legend>Add Product</legend>
            <label>Product Name:</label>
            <input type="text" name="pName">
            <br>
            <label>Price:</label>
            <input type="text" name="pPrice">
            <br>
            <label>Category:</label>
            <select name="pCategory">
                <option>--Select category--</option>
                <option value="Fish Monger">Fish Monger</option>
                <option value="Butchery">Butchery</option>
                <option value="Bakery">Bakery</option>
            </select>
            <br>
            <label>Image file:</label>
            <input type="text" name="imgName" placeholder="Image Name">
            <input type="file" name="pImage">
            <br>
            <input type="submit" value="Add" name="submitProduct">
        </fieldset>
    </form>

    <?php
    if (isset($_POST['submitProduct'])) {
        $pName = $_POST['pName'];
        $pPrice = $_POST['pPrice'];
        $pCat = $_POST['pCategory'];
        $pImgName = $_POST['imgName'];

        //capturing the file information
        $imgName = $_FILES["pImage"]["name"];
        $imgSize = $_FILES["pImage"]["size"];
        $imgType = $_FILES["pImage"]["type"];
        $tmp = $_FILES["pImage"]["tmp_name"];


        include ("../connection.php");


        if (empty($pName) || empty($pPrice) || empty($pCat)) {
            echo "Please fill all the fields.<br>";
        } else if (empty($imgName)) {
            echo "Please upload item image<br>";
        } else if ($imgType == "pImage/jpeg" || $imgType == "pImage/jpg" || $imgType == "pImage/gif" || $imgType == "pImage/png") {
            echo "Unsupported file format";
        } else if ($pImgName != $imgName) {
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

</body>

</html>