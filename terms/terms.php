<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions | CleckShopHub</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }

        header {


            padding: 10px 20px;
            text-align: center;
        }

        main {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            color: #333;
        }

        h2 {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        p {
            margin: 20px 0;
        }

        a {
            color: #0066cc;
        }

        a:hover {
            text-decoration: none;
        }

        footer {
            text-align: center;
            padding: 10px 0;
            background: #222;
            color: white;
            position: absolute;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>

<body>

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
    <header>
        <h1>Terms and Conditions</h1>
    </header>
    <main>
        <section>
            <p>Welcome to CleckShopHub, also known as "we" or "us". CleckShopHub is an online ecommerce platform operated by Team4. Before using our website (cleckshophub.com), please carefully read and understand the following terms and conditions ("terms"). By accessing or using CleckShopHub, you agree to comply with these terms.</p>
            <p>These terms govern your use of CleckShopHub, and all services offered through our platform. This includes browsing products, making purchases, interacting with other users, and accessing any content or information provided by CleckShopHub.</p>
        </section>

        <section>
            <h2>Conditions of Use</h2>
            <p>By accessing or using CleckShopHub, you certify that you have read, understood, and agree to comply with these terms and conditions. If you do not agree with any part of these terms, you are advised to refrain from using our website and services.</p>
            <p>CleckShopHub grants the use of the website, products, and services only to those individuals who have accepted these terms.</p>
        </section>

        <section>
            <h2>Privacy Policy</h2>
            <p>Before continuing to use our website, we advise you to read our privacy policy regarding our collection of user data. This will help you better understand our practices.</p>

        </section>

        <section>
            <h2>Age Restriction</h2>
            <p>You must be at least 18 years of age to use this website. By using this website, you warrant that you are at least 18 years old and legally capable of adhering to this Agreement. CleckShopHub assumes no responsibility for liabilities related to misrepresentation of age.</p>
        </section>

        <section>
            <h2>Intellectual Property</h2>
            <p>You acknowledge and agree that all materials, products, and services provided on this website are the property of CleckShopHub, its affiliates, directors, officers, employees, agents, suppliers, or licensors, including all copyrights, trade secrets, trademarks, patents, and other intellectual property. You further agree that you will not reproduce or redistribute CleckShopHub intellectual property in any way without prior written consent.</p>
            <p>By uploading and publishing content on CleckShopHub, you grant CleckShopHub a royalty-free, non-exclusive license to display, use, copy, transmit, and broadcast the content. If you have any issues regarding intellectual property claims, please contact us to resolve the matter.</p>
        </section>

        <section>
            <h2>User Accounts</h2>
            <p>As a user of CleckShopHub, you may be required to register with us and provide personal information. You are responsible for ensuring the accuracy of this information and maintaining the safety and security of your account credentials. You are also responsible for all activities conducted under your account. If you suspect any security issues with your account, please notify us immediately.</p>
        </section>

        <section>
            <h2>Applicable Law</h2>
            <p>By using CleckShopHub, you agree that the laws of [Jurisdiction], without regard to principles of conflict laws, will govern these terms and conditions and any disputes that may arise between CleckShopHub and you or its business partners and associates.</p>
        </section>

        <section>
            <h2>Delivery Options</h2>
            <p>CleckShopHub does not offer delivery services for customers. Customers must collect their orders from the designated collection slot on Wednesdays, Thursdays and Fridays of each week.</p>
        </section>

        <section>
            <h2>Disputes</h2>
            <p>Any dispute related to your use of CleckShopHub, or products purchased from us shall be arbitrated by state or federal courts, and you consent to the exclusive jurisdiction and venue of such courts.</p>
        </section>

        <section>
            <h2>Indemnification</h2>
            <p>You agree to indemnify CleckShopHub and its affiliates and hold us harmless against any legal claims or demands arising from your use or misuse of our services. We reserve the right to select our own legal counsel.</p>
        </section>

        <section>
            <h2>Limitation of Liability</h2>
            <p>CleckShopHub is not liable for any damages resulting from your misuse of our website.</p>
        </section>

        <section>
            <h2>Changes to Agreement</h2>
            <p>CleckShopHub reserves the right to edit, modify, and change this Agreement at any time. We will notify users of these changes via email. This Agreement supersedes all prior agreements regarding the use of our website.</p>
        </section>
    </main>

    <?php require('../inc/footer.php'); ?>


</body>

</html>