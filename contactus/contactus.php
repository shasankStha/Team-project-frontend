<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <link rel="stylesheet" href="../css/contactus.css">
    <title>CONTACT US</title>

</head>

<body class="bg-light">
    <?php
    include('../connection.php');
    session_start();

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }
    ?>

    <div class="my-2 px-4">
        <h2 class="fw-bold text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">

            Contact CleckShopHub for any inquiries, feedback, or to track your order. Feel free to use the form below or
            reach us by phone or email. We are dedicated to ensuring your shopping experience is seamless and enjoyable.
            <br>Thank you for shopping with CleckShopHub.
        </p>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">

                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.817147278988!2d85.31712757608553!3d27.692045826165188!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb190022762715%3A0xbd8f893a64dc355a!2sCleckShopHub!5e0!3m2!1sen!2snp!4v1711702498567!5m2!1sen!2snp" loading="lazy"></iframe>

                    <h5>Address</h5>
                    <p class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i> Thapathali,Kathmandu
                    </p>

                    <h5 class="mt-4">Call us</h5>
                    <p class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +977 9841234567
                    </p>

                    <h5 class="mt-4">Email</h5>
                    <p class="d-inline-block text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i> Cleckshophub@gmail.com
                    </p>

                    <h5 class="mt-4">Follow us</h5>
                    <!-- Social Media Links -->
                    <a href="https://twitter.com" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-twitter me-1"></i>
                    </a>
                    <a href="https://facebook.com" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="https://instagram.com" class="d-inline-block text-dark fs-5">
                        <i class="bi bi-instagram me-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Message</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require('../inc/footer.php'); ?>
    <?php
    if (isset($_POST['send'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $sql = "Insert into contact_us values(null,'$name','$email','$subject','$message')";
        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
    }
    ?>

</body>

</html>