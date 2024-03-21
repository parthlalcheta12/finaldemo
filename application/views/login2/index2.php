<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Login Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?=base_url('asset/all.css');?>">
    <style>
    #errorDiv {
        color: #dc3545;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .error-msg {
        margin-bottom: 5px;
    }
    </style>
</head>

<body>
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-sm-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                    <div class="text-center my-5">
                        <img src="<?=base_url('asset/logo.jpg');?>" alt="logo" width="100">
                    </div>
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
                            <div id="errorDiv" class="mt-3"></div>
                            <form id="loginform" method="POST" class="needs-validation" novalidate=""
                                autocomplete="off">
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" value="" required>
                                    <!-- <div class="error emailError"> <?php echo form_error('username'); ?></div> -->
                                    <span id="emailError" style="color: red;"></span>

                                    <!-- <span id="email_error"
                                        class="error text-danger"><?php echo form_error('email'); ?></span> -->
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="password">Passsword</label>
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    <!-- <div class="error passError"><?php echo form_error('password'); ?></div> -->
                                    <span id="passwordError" style="color: red;" class="error"></span>
                                    <span id="password_error"
                                        class="error text-danger"><?php echo form_error('password'); ?></span>

                                </div>

                                <div class="d-flex align-items-center">

                                    <button type="submit" id="submitBtn" class="btn btn-primary ms-auto">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer py-3 border-0">
                            <div class="text-center">
                                Don't have an account? <?=anchor('main/register', 'Create One');?>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5 text-muted">
                        Copyright &copy; 2017-2021 &mdash; Your Company
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
$(document).ready(function() {
    $('#loginform').submit(function(e) {
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        var isValid = true;
        // Clear previous error messages
        $('#emailError').empty();
        $('#passwordError').empty();

        // Client-side validation
        if (email.trim() == '') {
            $('#emailError').html('Please enter email').css("display", "block");
            isValid = false;
        } else if (!isValidEmail(email)) {
            $('#emailError').html('Please enter valid email').css("display", "block");
            isValid = false;
        } else {
            $('#emailError').text('');
        }
        if (password.trim() == '') {

            $('#passwordError').html('Please enter password').css("display", "block");
            isValid = false;
        } else {
            $('#passwordError').text('');
        }

        function isValidEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }
        //if no error then load server side validations
        if (isValid) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Main/User_login'); ?>",
                data: {
                    email: email,
                    password: password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.emailError != '') {
                        $('#emailError').html(response.emailError).css("display", "block");
                        $('#errorDiv').show();

                    } else {
                        $('#emailError').html('');
                    }
                    if (response.password_error != '') {
                        $('#password_error').html(response.password_error);
                        $('#errorDiv').show();
                        $('.error').show();
                    } else {
                        $('#password_error').html('');
                    }
                    if (response.success) {
                        
                        if (response.profile_logged) {
                            window.location.href =
                                '<?php echo base_url('Main/landing'); ?>';
                        } else {
                            window.location.href =
                                '<?php echo base_url('Main/profile'); ?>';
                        }
                    } else {
                        $('#errorDiv').html('<div class="mt-3 alert alert-danger">' +
                            response
                            .message + '</div>');
                    }
                }
            });
        }
    });
    $('#email').focus(function() {
        $('#emailError').hide();
        $('#email_error').hide();
        $('#errorDiv').hide();

    });
    $('#password').focus(function() {
        $('.error').hide();
        $('#password_error').hide();
        $('#errorDiv').hide();
    });

});
</script>

</html