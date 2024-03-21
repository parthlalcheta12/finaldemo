<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Register</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?=base_url('asset/all.css');?>">
</head>

<body>
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-sm-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                    <div class="text-center my-5">
                        <img src="<?=base_url('asset/reg2.jpeg');?>" alt="logo" width="100">
                    </div>
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <h1 class="fs-4 card-title fw-bold mb-4">Register</h1>
                            <form id="registerfrom" method="POST" class="needs-validation" novalidate=""
                                autocomplete="off">
                                <div id="errorDiv" class="mt-3"></div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="name">Full Name</label>
                                    <input id="name" name="name" type="text" class="form-control" value="" autofocus>
                                    <span id="errorname" style="color: red;">
                                        <?php echo form_error('name', '<span id="errorname" class="error" style="color: red;">', '</span>'); ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                                    <input id="email" name="email" type="email" class="form-control" value="" autofocus>
                                    <span id="emailError" style="color: red;">
                                        <?php echo form_error('email', '<span id="emailError" class="error" style="color: red;">', '</span>'); ?>
                                    </span>
                                </div>

                                <div class="mb-3">

                                    <label class="mb-2 text-muted" for="password">Passsword</label>
                                    <input id="password" name="password" type="password" class="form-control">
                                    <span id="errorpassword" style="color: red;">
                                        <?php echo form_error('password'); ?>
                                    </span>
                                </div>

                                <div class="mb-3">

                                    <label class="mb-2 text-muted" for="password">Confirm Passsword</label>
                                    <input id="confirmpassword" type="password" class="form-control"
                                        name="confirmpassword">
                                    <span id="errorconfirmpassword" style="color: red;">
                                        <?php echo form_error('confirmpassword', '<span id="errorconfirmpassword" style="color: red;">', '</span>'); ?>
                                    </span>
                                </div>

                                <div class="d-flex align-items-center">

                                    <button type="submit" class="btn btn-primary ">
                                        Register
                                    </button>
                                </div>

                            </form>
                        </div>
                        <div class="card-footer py-3 border-0">
                            <div class="text-center">
                                Have an account ? <?=anchor('main/login', 'Login');?>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5 text-muted">
                        Copyright &copy; Lorem. &mdash; Lorem.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    $(document).ready(function() {
        $('#registerfrom').submit(function(e) {
            e.preventDefault();
            var name = $('#name').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var confirmPassword = $('#confirmpassword').val();

            // Clear previous error messages
            $('#errorname').empty();
            $('#emailError').empty();
            $('#errorpassword').empty();
            $('#errorconfirmpassword').empty();

            // Client-side validation
            var isValid = true;
            if (name.trim() == '') {
                $('#errorname').html('Please enter name').css("display", "block");
                isValid = false;
            } else {
                $('#errorname').html('');
            }
            if (!/^[a-zA-Z\s]*$/.test(name)) {
                $('#errorname').html('Please enter valid  name').css("display", "block");
                isValid = false;
            }
            else {
                $('#errorname').html('');
            }
            if (name.length < 2 || name.length > 200) {
               $('#errorname').html('Name can not be in single letter').css("display", "block");
                isValid = false;
            }
             else {
                $('#errorname').html('');
            }
            if (email.trim() == '') {
                $('#emailError').html('Please enter email').css("display", "block");
                isValid = false;
            } else if (!isValidEmail(email)) {
                $('#emailError').html('Please enter valid email').css("display", "block");
                isValid = false;
            } else {
                $('#emailError').html('');
            }
            if (password.trim() == '') {
                $('#errorpassword').html('Please enter password').css("display", "block");
                isValid = false;
            } else if (password.length < 8) {
                $('#errorpassword').html('Password must greater then 8 letters').css("display", "block");

                isValid = false;
            } else {
                $('#errorpassword').html('');
            }
            if (confirmPassword.trim() == '') {
                $('#errorconfirmpassword').html('Please confirm your password').css("display", "block");
                isValid = false;
            } else if (password !== confirmPassword) {
                $('#errorconfirmpassword').html('Your password is does not match with above password').css("display", "block");
                isValid = false;
            } else {
                $('#errorconfirmpassword').html('');
            }
            if (isValid) {
                // Send data to server for further validation
                $.ajax({
                    url: '<?php echo base_url('Main/user_registration'); ?>',
                    method: 'POST',
                    data: {
                        name: name,

                        email: email,
                        password: password,
                        confirmPassword: confirmPassword
                    },
                    dataType: 'json',
                    success: function(response) {

                        // Handle server response
                        if (response.name != '') {
                            $('#errorname').html(response.name).css("display",
                                "block");
                            $('#errorDiv').show();
                        } else {
                            $('#errorname').html('');
                        }
                        if (response.email_error != '') {
                            $('#emailError').html(response.email_error).css("display",
                                "block");
                            $('#errorDiv').show();

                        } else {
                            $('#emailError').html('');
                        }
                        if (response.password_error != '') {
                            $('#errorpassword').html(response.password_error);
                            $('#errorDiv').show();
                            $('#errorpassword').show();
                        } else {
                            $('#errorpassword').html('');
                        }
                        if (response.confirmPassword != '') {
                            $('#errorconfirmpassword').html(response.confirmPassword);
                            $('#errorDiv').show();
                            $('#errorconfirmpassword').show();
                        } else {
                            $('#errorconfirmpassword').html('');
                        }
                        if (response.success) {
                            $('#errorDiv').html(response.message).addClass(
                                    'alert alert-success')
                                .css(
                                    "display", "block");
                            $('#errorDiv').show();
                            setTimeout(function() {
                                // Your code to execute after the timeout
                                window.location.href =
                                    '<?php echo base_url('Main/login'); ?>';
                            }, 2000);


                        } else {
                            $('#errorDiv').html('<div class="mt-3 alert alert-danger">' +
                                response
                                .message + '</div>');
                        }
                    }
                });
            } else {
                // Show danger div
                $('#errorDiv').html('Please correct the errors above').addClass('alert alert-danger')
                    .css("display", "block");
            }
            // Email validation
            function isValidEmail(email) {
                var re = /\S+@\S+\.\S+/;
                return re.test(email);
            }
        });
        $('#name').focus(function() {
            $('#errorname').hide();
            $('#errorDiv').hide();
        });
        $('#email').focus(function() {
            $('#emailError').hide();
            $('#errorDiv').hide();
        });
        $('#password').focus(function() {
            $('#errorpassword').hide();
            $('#errorDiv').hide();
        });
        $('#confirmpassword').focus(function() {
            $('#errorconfirmpassword').hide();
            $('#errorDiv').hide();
        });
    });
    </script>
</body>

</html>