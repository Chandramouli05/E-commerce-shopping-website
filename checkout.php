<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">

        <div style="text-align: center; padding: 10px; font-size: 14pt; color: white;">
            <img src="images/logo-small.png" style="height: 48px; width: auto;"><br>
            Online Checkout
        </div>

        <div class="content-wrapper">
            <div class="container">

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-sm">
                            <?php
                            if (isset($_SESSION['user'])) {

                                $data = $_GET;

                                if (isset($data['mode']) && !empty($data['mode'])) {
                                    $mode = $data['mode'];
                                    $modeText = "";
                                    $button = "";
                                    if ($mode == 'cod') {
                                        $modeText = "Cash on Delivery (COD)";
                                        $button = "<button type=\"button\" class=\"btn btn-success btn payment-option\"><i class=\"fa fa-shopping-cart icon-padding\"></i>Proceed with COD</button>";
                                    } else if ($mode == 'gateway') {
                                        $modeText = "Debit Card / Credit Card / Net Banking / UPI";
                                        $button = "<button type=\"button\" class=\"btn btn-success btn payment-option\"><i class=\"fa fa-shopping-cart icon-padding\"></i>Proceed to Pay</button>";
                                    } else {
                                        die('<center><h1 style="margin-top: 20px">Bad Request!</h1><br><p><button class="btn btn-info" onclick="location.href = \'index.php\'">Click here to return to the homepage</button></p></center>');
                                    }

                                    echo "
							<div class=\"row\">
								<div class=\"col-sm-12\">
									<div class='box box-solid'>
										<div class='box-header with-border'>
											<h3 class='box-title'><b>Checkout</b></h3>
										</div>"; ?>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <th>Photo</th>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th width="20%">Quantity</th>
                                                    <th>Subtotal</th>
                                                </thead>
                                                <tbody id="tbody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            <?php
                                    echo "<div class='box-body'>
											<p>
												<b>You have selected the following payment option:
												</b>
											</p>
											<p class='indent-left'>
												" . $modeText . "
											</p>
                                            <ul class='indent-left'>
													<li>You will be forwarded to our partner payment gateway to complete the purchase.
												</ul>
                                            <p align='center' style='margin-top: 20px;'>
                                                " . $button . "
                                            </p>                                                
										</div>
									</div>
								</div>
							</div>";
                                } else {
                                    die('<center><h1 style="margin-top: 20px">Bad Request!</h1><br><p><button class="btn btn-info" onclick="location.href = \'index.php\'">Click here to return to the homepage</button></p></center>');
                                }
                            } else {
                                die('<center><h1 style="margin-top: 20px">You are not logged in!</h1><br><p><button class="btn btn-info" onclick="location.href = \'index.php\'">Click here to return to the homepage</button></p></center>');
                            }
                            ?>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <?php $pdo->close(); ?>
        <?php include 'includes/footer.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script>
        $(function() {
            $(document).on('click', '.cart_delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'cart_delete.php',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.error) {
                            getDetails();
                            getCart();
                        }
                    }
                });
            });

            $(document).on('click', '.start-checkout', function(e) {
                e.preventDefault();
                let method = $('input[name="choose-payment"]:checked').val();
                if (method == undefined) {
                    alert("Select a payment method");
                } else {
                    location.href = "checkout.php?mode=" + method;
                }
            });

            $(document).on('click', '.minus', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var qty = $('#qty_' + id).val();
                if (qty > 1) {
                    qty--;
                }
                $('#qty_' + id).val(qty);
                $.ajax({
                    type: 'POST',
                    url: 'cart_update.php',
                    data: {
                        id: id,
                        qty: qty,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.error) {
                            getDetails();
                            getCart();
                        }
                    }
                });
            });

            $(document).on('click', '.add', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var qty = $('#qty_' + id).val();
                qty++;
                $('#qty_' + id).val(qty);
                $.ajax({
                    type: 'POST',
                    url: 'cart_update.php',
                    data: {
                        id: id,
                        qty: qty,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.error) {
                            getDetails();
                            getCart();
                        }
                    }
                });
            });

            getDetails();

        });

        function getDetails() {
            $.ajax({
                type: 'POST',
                url: 'cart_details.php?edit=no',
                dataType: 'json',
                success: function(response) {
                    $('#tbody').html(response);
                    getCart();
                }
            });
        }
    </script>
</body>

</html>