
<?php require_once('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php') ?>
<body>
<?php require_once('inc/topBarNav.php') ?>
</body>
</html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .center {
            text-align: center;
            margin: 0 auto;
            max-width: 600px; 
        }
        .contact-form {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #871975;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!--contact section start-->
    <div class="contact-form center">
        <h2 class="page-header"><strong>Contact Us</strong></h2>
        <h4>Ask us anything! Be it requesting pet products which are not yet available, bulk purchase matters, account issues and refunds...</h4>
        <form target="_blank" action="https://formsubmit.co/mayigasi@mailgolem.com" method="POST"> 
            <div class="form-group has-feedback">
                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="form-group has-feedback">
                <textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
            </div>
            <input type="submit" name="submit" class="btn btn-primary btn-block btn-flat" value="Send">
        </form>
    </div>
    <!--contact section end-->
</body>
</html>
<?php require_once('inc/footer.php') ?>