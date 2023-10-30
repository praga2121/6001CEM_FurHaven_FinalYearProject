<?php 
 $products = $conn->query("SELECT * FROM `products`  where md5(id) = '{$_GET['id']}' ");
 if($products->num_rows > 0){
     foreach($products->fetch_assoc() as $k => $v){
         $$k= stripslashes($v);
     }
    $upload_path = base_app.'/uploads/product_'.$id;
    $img = "";
    if(is_dir($upload_path)){
        $fileO = scandir($upload_path);
        if(isset($fileO[2]))
            $img = "uploads/product_".$id."/".$fileO[2];
        // var_dump($fileO);
    }
    $inventory = $conn->query("SELECT * FROM inventory where product_id = ".$id);
    $inv = array();
    while($ir = $inventory->fetch_assoc()){
        $inv[] = $ir;
    }
 }
?>
<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0" loading="lazy" id="display-img" src="<?php echo validate_image($img) ?>" alt="..." />
                <div class="mt-2 row gx-2 gx-lg-3 row-cols-4 row-cols-md-3 row-cols-xl-4 justify-content-start">
                    <?php 
                        foreach($fileO as $k => $img):
                            if(in_array($img, array('.', '..')))
                                continue;
                    ?>
                    <div class="col">
                        <a href="javascript:void(0)" class="view-image <?php echo $k == 2 ? "active" : '' ?>"><img src="<?php echo validate_image('uploads/product_' . $id . '/' . $img) ?>" loading="lazy" class="img-thumbnail" alt=""></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="display-5 fw-bolder border-bottom border-primary pb-1"><?php echo $title ?></h1>
                <p class="m-0"><small><b>Brand: </b><?php echo $brand ?></small></p>
                <div class="fs-5 mb-5">
                    <b>RM: </b><span id="price"><?php echo number_format($inv[0]['price'],2) ?></span><br>
                    <span><small><b>Available Stock:</b> <span id="avail"><?php echo $inv[0]['quantity'] ?></span></small></span>
                </div>
                <form action="" id="add-cart">
                    <div class="d-flex">
                        <input type="hidden" name="price" value="<?php echo $inv[0]['price'] ?>">
                        <input type="hidden" name="inventory_id" value="<?php echo $inv[0]['id'] ?>">
                        <input class="form-control text-center me-3" id="inputQuantity" type="number" min="1" max="<?php echo $inv[0]['quantity'] ?>" value="1" style="max-width: 4rem" name="quantity" />
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit" <?php echo ($inv[0]['quantity'] < 1) ? 'disabled' : '' ?>>
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    </div>
                    <?php if ($inv[0]['quantity'] > 0): ?>
                        <div class="mt-2 text-danger small">
                            <?php echo "Maximum available stock: {$inv[0]['quantity']}"; ?>
                        </div>
                    <?php else: ?>
                        <div class="mt-2 text-danger small">
                            <?php echo "This product is out of stock."; ?>
                        </div>
                    <?php endif; ?>
                </form>
                <p class="lead"><?php echo stripslashes(html_entity_decode($description)) ?></p>
            </div>
        </div>
    </div>
</section>

   <!-- Reviews Section Display -->
    <style>
    .star-rating {
        color: #ff9900;
    }
    .review-form {
        width: 50%; 
        margin: 0 auto; 
        background-color: #f0f0f0; 
        padding: 20px; 
    }
    .form-group {
        margin-bottom: -5px; 
    }
    .section-box {
        background-color: #e0e0e0; 
        border: 1px solid #ccc; 
        padding: 10px; 
        margin-bottom: 10px; 
    }
    .rating-section {
    display: flex; 
    align-items: center; 
    margin-bottom: 5px; 
    }
    .rating-label {
        flex: 0 0 auto; 
        margin-right: 5px; 
    }
    .star-rating {
        flex: 0 0 auto; 
    }
    #comment {
        width: 100%; 
    }
    </style>
    <section class="py-5">
    <div class="container">
        <h2>Product Reviews</h2>
        <?php
        // Retrieve and display product reviews
        $product_id = $_GET['id'];
        $reviews_query = $conn->query("SELECT * FROM reviews WHERE product_id = '$product_id'");

        if ($reviews_query->num_rows > 0) {
            while ($row = $reviews_query->fetch_assoc()) {
                $username = $row['username'];
                $rating = $row['rating']; // Get the numerical rating
                $comment = $row['comment'];

                // Convert numerical rating to star representation
                $stars = str_repeat('&#9733;', $rating);

                // Display the review
                echo "<div class='section-box'>";
                echo "<div class='form-group'>";
                echo "<p><strong>Username:</strong> $username</p>";
                echo "</div>";
                echo "<div class='rating-section'>"; // Wrap Rating label and stars here
                echo "<div class='form-group rating-label'>";
                echo "<p><strong>Rating:</strong></p>";
                echo "</div>";
                echo "<div class='star-rating'>$stars</div>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<p><strong>Comment:</strong> $comment</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-reviews'>No reviews available for this product.</p>";
        }
        ?>
    </div>
    
    </section>
    <!-- Review Section - Form -->
    <form action="process_review.php" method="POST" class="review-form">
        <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
        <div class='form-group'>
            <label for="username">Your Name:</label>
            <input type="text" name="username" id="username" placeholder="Your Name here" required>
        </div>
        <div class='form-group'>
            <label for="rating">Rating:</label>
            <select name="rating" id="rating">
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Average</option>
                <option value="4">4 - Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <div class='form-group'>
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" rows="4" placeholder="Type comments here" required></textarea>
        </div>
        <input type="submit" value="Submit Review" class="submit-button">
    </form>
    <br>
    </div>
            
    </section>
    <!-- Related items section-->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="fw-bolder mb-4">Related Products You May Like</h2>
            <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php 
                $products = $conn->query("SELECT * FROM `products` WHERE status = 1 AND sub_category_id = '{$sub_category_id}' AND id != '{$id}' LIMIT 4");
                while($row = $products->fetch_assoc()):
                    $upload_path = base_app.'/uploads/product_'.$row['id'];
                    $img = "";
                    if(is_dir($upload_path)){
                        $fileO = scandir($upload_path);
                        if(isset($fileO[2]))
                            $img = "uploads/product_".$row['id']."/".$fileO[2];
                        // var_dump($fileO);
                    }
                    $inventory = $conn->query("SELECT * FROM inventory WHERE product_id = ".$row['id']);
                    $_inv = array();
                    foreach($row as $k=> $v){
                        $row[$k] = trim(stripslashes($v));
                    }
                    while($ir = $inventory->fetch_assoc()){
                        $_inv[] = number_format($ir['price']);
                    }
            ?>
                <div class="col mb-5">
                    <div class="card h-100 product-item">
                        <!-- Product image-->
                        <img class="card-img-top w-100" src="<?php echo validate_image($img) ?>" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo $row['title'] ?></h5>
                                <!-- Product price-->
                                <?php foreach($_inv as $k=> $v): ?>
                                    <span><b>Price: </b><?php echo $v ?></span>
                                <?php endforeach; ?>
                                <p class="m-0"><small>Brand: <?php echo $row['brand'] ?></small></p>
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-flat btn-primary "   href=".?p=view_product&id=<?php echo md5($row['id']) ?>">View</a>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>


<script>
    var inv = $.parseJSON('<?php echo json_encode($inv) ?>');
    $(function(){
        $('.view-image').click(function(){
            var _img = $(this).find('img').attr('src');
            $('#display-img').attr('src',_img);
            $('.view-image').removeClass("active")
            $(this).addClass("active")
        })
        $('.p-size').click(function(){
            var k = $(this).attr('data-id');
            $('.p-size').removeClass("active")
            $(this).addClass("active")
            $('#price').text(inv[k].price)
            $('[name="price"]').val(inv[k].price)
            $('#avail').text(inv[k].quantity)
            $('[name="inventory_id"]').val(inv[k].id)

        })

        $('#add-cart').submit(function(e){
            e.preventDefault();
            if('<?php echo $_settings->userdata('id') ?>' <= 0){
                uni_modal("","login.php");
                return false;
            }
            start_loader();
            $.ajax({
                url:'classes/Master.php?f=add_to_cart',
                data:$(this).serialize(),
                method:'POST',
                dataType:"json",
                error:err=>{
                    console.log(err)
                    alert_toast("an error occured",'error')
                    end_loader()
                },
                success:function(resp){
                    if(typeof resp == 'object' && resp.status=='success'){
                        alert_toast("Product added to cart.",'success')
                        $('#cart-count').text(resp.cart_count)
                    }else{
                        console.log(resp)
                        alert_toast("an error occured",'error')
                    }
                    end_loader();
                }
            })
        })
    })
</script>