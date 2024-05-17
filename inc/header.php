<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 10px 20px;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 200px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            margin-left: auto;
            margin-right: 50px;
        }

        .search-bar input {
            padding: 8px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 20px;
            flex-grow: 1;
        }

        .search-button {
            padding: 6px 10px;
            background: transparent;
            border: none;
            color: black;
            cursor: pointer;
        }

        .search-button i {
            font-size: 16px;
        }

        .menu {
            display: flex;
            align-items: center;
        }

        .menu a {
            color: black;
            padding: 10px;
            text-decoration: none;
            font-size: 16px;
            margin-left: 10px;
            transition: color 0.3s;
        }

        .menu a:hover {
            color: white;
            background-color: black;
            border-radius: 20px;
        }

        .toggle-button {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .toggle-button .bar {
            height: 3px;
            width: 25px;
            background-color: black;
            margin: 3px 0;
        }

        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                align-items: stretch;
                padding: 10px;
            }

            .logo img {
                width: 100px;
            }

            .search-bar {
                display: none;
                margin-right: 20px;
            }

            .menu {
                width: 100%;
                display: none;
                flex-direction: column;
                align-items: center;
            }

            .menu.active,
            .search-bar.active {
                display: flex;
            }

            .toggle-button {
                display: flex;
                position: absolute;
                right: 10px;
                top: 10px;
            }
        }

        .sub-navbar {
            display: flex;
            justify-content: space-around;
            background-color: #2b2f33;
            color: white;
            padding: 10px 0;
            margin-bottom: 20px;
            text-align: center;
        }

        .sub-navbar a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 0 5px;
            padding: 8px 16px;
        }

        .sub-navbar a:hover {
            background-color: #f0f0f0;
            color: black;
            border-radius: 30px;
        }

        .user-icon {
            position: relative;
            display: inline-block;
        }

        #dropdown-menu {
            display: none;
            /* Make sure it's not visible until hovered */
            flex-direction: column;
            position: absolute;
            background-color: #2b2f33;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            /* z-index: 1; */
        }

        .user-icon:hover #dropdown-menu {
            display: flex;
            position: fixed;
            /* Display dropdown on hover */
        }

        #dropdown-menu a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        #dropdown-menu a:hover {
            background-color: #f0f0f0;
            color: black;
            border-radius: 30px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../Images/Logo/just_logo.png" alt="CleckShopHub">
        </div>
        <div class="toggle-button">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <div class="menu">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
                <button class="search-button"><i class="fa fa-search"></i></button>
            </div>
            <a href="#login">Login</a>
            <a href="#register">Register</a>
        </div>
    </nav>
    <div class="sub-navbar">
        <a href="../index.php">Home</a>
        <div class="user-icon">
            <a href="#">Shop</a>
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="../shops/shoppage.php">Fishmonger</a>
                <a href="../shops/shoppage.php">Butcher</a>
                <a href="../shops/shoppage.php">Greengrocer</a>
                <a href="../shops/shoppage.php">Bakery</a>
                <a href="../shops/shoppage.php">Delicatessen</a>
            </div>
        </div>
        <a href="../contactus/contactus.php">Contact us</a>
        <a href="../aboutus/aboutus.php">About us</a>
    </div>
    <script>
        document.querySelector('.toggle-button').addEventListener('click', function() {
            document.querySelector('.menu').classList.toggle('active');
            document.querySelector('.search-bar').classList.toggle('active');
        });
    </script>
</body>

</html>