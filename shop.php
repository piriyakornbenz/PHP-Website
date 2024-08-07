<?php

session_start();
require('./backend/config.php');
include('data_index.php');

if (isset($_SESSION['login'])) {
    $id = $_SESSION['login'];
    $dataCustomer = $conn->prepare("SELECT * FROM customers WHERE id=:id");
    $dataCustomer->bindParam('id', $id);
    $dataCustomer->execute();
    $row = $dataCustomer->fetch(PDO::FETCH_ASSOC);
} else {
    header('location: login.php');
}

if (isset($_POST['reviews'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO section_reviews(name, email, message) VALUES(:name, :email, :message)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    if ($stmt->execute()) {
        header('location: shop.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .alert-success {
            width: 100%;
            color: #fff;
            font-size: 1.5rem;
            background-color: green;
            border-radius: 5px;
            padding: 15px;
        }
    </style>
</head>

<body>

    <!-- navbar -->
    <header>

        <input type="checkbox" id="toggler">
        <label for="toggler" class="fas fa-bars"></label>

        <a href="#" class="logo">flower<span>.</span></a>

        <div class="icons">
            <a style="font-size: 1.5rem;">welcome <?= $row['firstname'] ?></a>
            <a href="cart.php" class="fas fa-shopping-cart"><span id="cart-count">
                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?>
            </span></a>
            <div class="dropdown" id="dropdown">
                <button class="dropdown-toggle fas fa-user" type="button" onclick="toggleDropdown()"></button>
                <ul class="dropdown-menu">
                    <?php if (isset($_SESSION['login'])) { ?>
                        <li><a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a></li>
                        <li><a href="history.php"><i class="fa-solid fa-clipboard-check"></i> History</a></li>
                        <li><a href="logout.php"><i class="fas fa-right-to-bracket"></i> Logout</a></li>
                    <?php } else { ?>
                        <li><a href="login.php"><i class="fas fa-right-to-bracket"></i> Login</a></li>
                        <li><a href="register.php"><i class="fas fa-file-pen"></i> Register</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </header>

    <!-- product -->
    <section class="products" id="products" style="margin-top: 10rem;">

        <p id="text-alert"></p>

        <h1 class="heading"> lastest <span>Products</span> </h1>

        <?php if(isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>

        <div class="box-container">

            <?php foreach ($row4 as $result) : ?>
                <div class="box">
                    <?php if ($result['discount'] > 0) { ?>
                        <span class="discount">-<?= $result['discount']; ?>%</span>
                    <?php } else { ?>
                        <span style="display: none;"></span>
                    <?php } ?>
                    <div class="image">
                        <img src="./uploads/<?= $result['picture']; ?>" alt="">
                    </div>
                    <div class="content">
                        <h2 class="h2"><?= $result['heading']; ?></h2>
                        <?php if ($result['discount'] > 0) { ?>
                            <div class="price">฿<?= $result['price'] * (100 - $result['discount']) / 100; ?> <span>฿<?= $result['price']; ?></span> </div>
                        <?php } else { ?>
                            <div class="price">฿<?= $result['price'] * (100 - $result['discount']) / 100; ?> </div>
                        <?php } ?>
                    </div>
                    <div class="button-add">
                        <a href="cart.php?id=<?= $result['id'] ?>" class="btn"><i class="fa-solid fa-cart-shopping"></i> add to cart</a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    </section>

    <!-- footer -->
    <section class="footer">

        <div class="box-container">

            <div class="box">
                <h2 class="h2">quick links</h2>
                <a href="#products">products</a>
            </div>

            <div class="box">
                <h2 class="h2">extra links</h2>
                <a href="#">my account</a>
                <a href="#">my order</a>
                <a href="#">my favorite</a>
            </div>

            <div class="box">
                <h2 class="h2">location</h2>
                <a href="#">thailand</a>
                <a href="#">USA</a>
                <a href="#">japan</a>
            </div>

            <div class="box">
                <h2 class="h2">contact info</h2>
                <a href="#">+123-456-7890</a>
                <a href="#">example@email.com</a>
                <a href="#">Thailand</a>
                <i class="fa-brands fa-cc-paypal" style="color: #74C0FC;"></i>
                <i class="fa-solid fa-credit-card"></i>
            </div>

        </div>

        <?php foreach ($row6 as $result) : ?>
            <div class="credit"> <?= $result['footer'];  ?> </div>
        <?php endforeach; ?>

    </section>

    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("dropdown");
            dropdown.classList.toggle("active");
        }
    </script>

</body>

</html>