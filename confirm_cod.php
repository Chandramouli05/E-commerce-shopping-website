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

                                echo "
							<div class=\"row\">
								<div class=\"col-sm-12\">
									<div class='box box-solid'>
										<div class='box-header with-border'>
											<h3 class='box-title'><b>Confirm Cash on Delivery Purchase</b></h3>
										</div>";
                            ?>
                            <?php
                                echo "<div class='box-body'>
											<p align='center' style='margin-top: 30px; font-size: 14pt'>
												<b>An OTP has been sent to your email address. Please enter it here to confirm your order.</b>
											</p>
											<p class='indent-left'>
												
											</p>
                                            <p align='center' style='margin-top: 20px;'>
                                                <div class='row'>
                                                    <center>
                                                        <input placeholder='Enter your OTP' type='text' id='userOTP' class='form-control cod-field' maxlength='6'>
                                                        <button type='button' style='height: 48px; vertical-align: middle' class='btn btn-success'><i style='font-size: 14pt' class='fa fa-arrow-circle-o-right' aria-hidden='true'></i></button>
                                                    </center>
                                                </div>
                                            </p>                                                
										</div>
									</div>
								</div>
							</div>";
                            } else {
                                die('<center><h1 style="margin-top: 20px">Bad Request!</h1><br><p><button class="btn btn-info" onclick="location.href = \'index.php\'">Click here to return to the homepage</button></p></center>');
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