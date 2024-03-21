<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="This is a Profile page template based on Bootstrap 5">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style type="text/css">
    body {
        margin-top: 20px;
        background-color: #f2f6fc;
        color: #69707a;
    }

    .img-account-profile {
        height: 10rem;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
    }

    .card .card-header {
        font-weight: 500;
    }

    .card-header:first-child {
        border-radius: 0.35rem 0.35rem 0 0;
    }

    .card-header {
        padding: 1rem 1.35rem;
        margin-bottom: 0;
        background-color: rgba(33, 40, 50, 0.03);
        border-bottom: 1px solid rgba(33, 40, 50, 0.125);
    }

    .form-control,
    .dataTable-input {
        display: block;
        width: 100%;
        padding: 0.875rem 1.125rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #69707a;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #c5ccd6;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .nav-borders .nav-link.active {
        color: #0061f2;
        border-bottom-color: #0061f2;
    }

    .nav-borders .nav-link {
        color: #69707a;
        border-bottom-width: 0.125rem;
        border-bottom-style: solid;
        border-bottom-color: transparent;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 0;
        padding-right: 0;
        margin-left: 1rem;
        margin-right: 1rem;
    }
    </style>
</head>

<body>
    <div class="container-xl px-4 mt-4">

        <nav class="nav nav-borders">

            <h1>Profile</h1>
        </nav>

        <form id="updateProfileForm" method="POST" class="needs-validation" novalidate="" autocomplete="off"
            enctype="multipart/form-data">
            <div id="errorDiv" class="mt-3"></div>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <!-- <?=base_url('asset/avatar1.png');?> -->

                            <label for="img"></label>
                            <img id="imagePreview" class="img-account-profile rounded-circle mb-2" src=""
                                alt="Image Preview"><br>
                            <input type="file" id="imageFile" name="imageFile" style="display: none;">
                            <button class="btn btn-primary" type="button" id="uploadImageButton">Upload
                                Image</button><br>
                            <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 2 MB</div>
                            <div id="errorMessage" style="color: red;"><?php echo form_error('imageFile');?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header">Account Details</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputUsername">Username (how your name will appear to
                                    other users on the site)</label>
                                <input class="form-control" id="inputUsername" name="inputUsername" type="text"
                                    placeholder="Enter your username">
                                <span id="usernameError" class="nameerror" style="color: red;"
                                    class="nameerror error"><?php echo form_error('inputUsername'); ?></span>
                                <!-- <?php echo form_error('inputUsername', '<span id="usernameError" class="nameerror error" style="color: red;">', '</span>'); ?> -->
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Country:</label>
                                        <select class="form-control country" id="country" name="country">
                                            <option value="">Select Country</option>
                                            <?php foreach ($countries as $country): ?>
                                            <option value="<?php echo $country->country_id; ?>">
                                                <?php echo $country->country; ?>
                                            </option>
                                            <?php endforeach;?>
                                            <!-- Populate with countries -->
                                        </select>
                                        <span id="countryError" style="color: red;"
                                            class="error"><?php echo form_error('country'); ?></span>
                                        <!-- <?php echo form_error('country', '<span id="countryError" class="error" style="color: red;">', '</span>'); ?> -->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State:</label>
                                        <select class="form-control state" id="state" name="state">
                                            <option value="">Select State</option>
                                            <!-- Populate with states -->
                                        </select>
                                        <span id="stateError" class="error" style="color: red; ">
                                            <?php echo form_error('state'); ?></span>
                                        <!-- <?php echo form_error('state', '<span id="stateError" class="error" style="color: red;">', '</span>'); ?> -->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City:</label>
                                        <select class="form-control city" id="city" name="city">
                                            <option value="">Select City</option>
                                            <!-- Populate with cities -->
                                        </select>
                                        <span id="cityError" class="error"
                                            style="color: red;"><?php echo form_error('city'); ?></span>
                                        <!-- <?php echo form_error('city', '<span id="cityError" class="error" style="color: red;">', '</span>'); ?> -->
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                <input class="form-control" id="inputEmailAddress" type="email"
                                    placeholder="Enter your email address" value="<?php echo $user_email[0]->email; ?>"
                                    disabled>
                            </div>

                            <div class="row gx-3 mb-3">

                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputPhone">Phone number</label>
                                    <input class="form-control" id="inputPhone" type="tel"
                                        placeholder="Enter your phone number" name="inputPhone">
                                </div>
                                <span id="phoneError" style="color: red;"><?php echo form_error('inputPhone'); ?></span>
                                <!-- <?php echo form_error('inputPhone', '<span id="phoneError" class="error" style="color: red;">', '</span>'); ?> -->

                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputBirthday">Birthday</label>
                                    <div class="input-group">
                                        <input class="form-control" id="inputBirthday" type="text" name="inputBirthday"
                                            placeholder="Enter your birthday">
                                    </div>
                                    <span id="birthdayError"
                                        style="color: red;"><?php echo form_error('inputBirthday'); ?></span>
                                    <!-- <?php echo form_error('inputBirthday', '<span id="birthdayError" class="error" style="color: red;">', '</span>'); ?> -->
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary " id="submitBtn">
                                Submit
                            </button>
        </form>
    </div>

    <!-- required scripts -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
    </script>
    <!-- required scripts -->

    <script>
    $(document).ready(function() {

        //Date picker
        $('#inputBirthday').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd', // Set your desired date format
            todayHighlight: true,
            todayBtn: "linked"
        });
        $('#country').select2();
        //Dropdown
        $('#country').change(function() {
            var country_id = $(this).val();
            if (country_id != '') {
                $.ajax({
                    url: "<?php echo base_url('ProfileController/get_states'); ?>",
                    method: "POST",
                    data: {
                        country_id: country_id
                    },
                    dataType: "json",
                    success: function(data) {
                        var html = '<option value="">Select State</option>';
                        $.each(data, function(key, value) {
                            html += '<option value="' + value.state_id + '">' +
                                value
                                .state + '</option>';
                        });
                        $('#state').html(html);
                        $('#city').html('<option value="">Select City</option>');
                    }
                });
            } else {
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');
            }
        });
        $('#state').select2();
        $('#city').select2();
        $('#state').change(function() {
            var state_id = $(this).val();
            if (state_id != '') {
                $.ajax({
                    url: "<?php echo base_url('ProfileController/get_cities'); ?>",
                    method: "POST",
                    data: {
                        state_id: state_id
                    },
                    dataType: "json",
                    success: function(data) {
                        var html = '<option value="">Select City</option>';
                        $.each(data, function(key, value) {
                            html += '<option value="' + value.city_id + '">' + value
                                .city + '</option>';
                        });

                        $('#city').html(html);
                    }
                });
            } else {
                $('#city').html('<option value="">Select City</option>');
            }
        });

        // When the "Upload Image" button is clicked
        $('#uploadImageButton').click(function() {
            // Trigger click event on the hidden file input
            $('#imageFile').click();
        });

        // When a file is selected
        $('#imageFile').change(function() {
            $('#errorMessage').hide();
            var file = this.files[0];

            // Check if a file is selected
            if (!file) {
                $('#imagePreview').hide();
                return;
            }

            // Check file size
            var maxSize = 2 * 1024 * 1024; // 2MB max size
            if (file.size > maxSize) {
                $('#errorMessage').text('File size exceeds the maximum allowed size (2MB)');
                $('#errorMessage').show();
                $('#imagePreview').hide();
                return;
            }

            // Check file type
            var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (allowedTypes.indexOf(file.type) === -1) {
                $('#errorMessage').text(
                    'Invalid file type. Only JPEG, PNG, and GIF files are allowed.');
                $('#errorMessage').show();
                $('#imagePreview').hide();
                return;
            }

            // Display image preview if validations pass
            var reader = new FileReader();
            reader.onload = function(event) {
                $('#imagePreview').attr('src', event.target.result);
                $('#imagePreview').show();
            };
            reader.readAsDataURL(file);
        });

        //FormData
        $('#submitBtn').click(function(e) {
            e.preventDefault();

            var username = $('#inputUsername').val();
            var country = $('#country').val();
            var state = $('#state').val();
            var city = $('#city').val();
            var phone = $('#inputPhone').val();
            var birthday = $('#inputBirthday').val();
            var file = $('#imagePreview').val();
            var today = new Date();
            var selectedDate = new Date(birthday);
            var phoneRegex = /^\d{10}$/;

            // Perform validation
            var isValid = true;

            if (!username) {
                $('#usernameError').text('Username is required').css("display", "block");
                isValid = false;
            }
            if (!/^[a-zA-Z\s]*$/.test(username)) {
                $('#usernameError').html('Please enter valid  name').css("display", "block");
                isValid = false;
            }
            else {
                $('#usernameError').html('');
            }
            if (username.length < 2 || username.length > 200) {
               $('#usernameError').html('Name can not be in single letter').css("display", "block");
                isValid = false;
            }
             else {
                $('#usernameError').html('');
            }
            if (!country) {
                $('#countryError').text('Country is required').css("display", "block");
                isValid = false;
            }
            if (!state) {
                $('#stateError').text('State is required').css("display", "block");
                isValid = false;
            }
            if (!city) {
                $('#cityError').text('City is required').css("display", "block");
                isValid = false;
            }
            if (!phone) {
                $('#phoneError').text('Phone number is required').css("display", "block");
                isValid = false;
            }
            if (phone.length > 10) {
                $('#phoneError').text('Phone number must be in 10 digit').css("display", "block");
                isValid = false;
            }
            if (!phoneRegex.test(phone)) {
                // Check if the phone number has exactly 10 digits
                if (phone.length !== 10) {
                    $('#phoneError').text('Invalid phone number').css("display", "block");
                } else {
                    $('#phoneError').text('Please enter a valid phone number.').css("display", "block");
                }
                isValid = false;
            }
            if (!birthday) {
                $('#birthdayError').text('Birthday is required').css("display", "block");
                isValid = false;
            }
            if (selectedDate >= today) {
                $('#birthdayError').text('Invalid BirthDate').css("display", "block");
                isValid = false;
            }

            // if (!file) {
            //     $('#errorMessage').text('Please select File ').css("display", "block");
            // }
            // If all fields are valid, submit the form
            if (isValid) {
                var formData = new FormData($('#updateProfileForm')[0]);
                // Send AJAX request
                $.ajax({
                    url: '<?php echo base_url("ProfileController/set_user_profile"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if (response.inputUsername != '') {
                            $('#usernameError').html(response.inputUsername).css(
                                "display",
                                "block");
                            $('#errorDiv').show();
                        } else {
                            $('#usernameError').html('');
                        }
                        if (response.country != '') {
                            $('#countryError').html(response.country).css("display",
                                "block");
                            $('#errorDiv').show();

                        } else {
                            $('#countryError').html('');
                        }
                        if (response.state != '') {
                            $('#stateError').html(response.state);
                            $('#errorDiv').show();
                            $('#stateError').show();
                        } else {
                            $('#stateError').html('');
                        }
                        if (response.city != '') {
                            $('#cityError').html(response.city);
                            $('#errorDiv').show();
                            $('#cityError').show();
                        } else {
                            $('#cityError').html('');
                        }
                        if (response.inputPhone != '') {
                            $('#phoneError').html(response.inputPhone);
                            $('#errorDiv').show();
                            $('#phoneError').show();
                        } else {
                            $('#errorconfirmpassword').html('');
                        }
                        if (response.inputBirthday != '') {
                            $('#birthdayError').html(response.inputBirthday);
                            $('#errorDiv').show();
                            $('#birthdayError').show();
                        } else {
                            $('#birthdayError').html('');
                        }
                        if (response.imageFile != '') {
                            $('#errorMessage').html(response.imageFile);
                            $('#errorDiv').show();
                            // $('#errorMessage').show();
                        } else {
                            $('#errorMessage').html('');
                        }
                        if (response.success) {
                            // Handle success
                            window.location.href =
                                '<?php echo base_url('Main/landing'); ?>';
                        }
                        if (response.error) {
                            // Handle error
                            $('#errorDiv').html(
                                '<div class="mt-3 alert alert-danger">' +
                                response
                                .message + '</div>').show();
                        }
                    }
                });
            } else {
                // Show danger div
                $('#errorDiv').html('Please correct the errors above').addClass(
                        'alert alert-danger')
                    .css("display", "block").show();

            }
        });
        $('#inputUsername').focus(function() {
            $('.nameerror').hide();
            $('#errorDiv').hide();
        });
        $('#country').focus(function() {
            $('#countryError').hide();
            $('#errorDiv').hide();
        });
        $('#state').focus(function() {
            $('#stateError').hide();
            $('#errorDiv').hide();
        });
        $('#city').focus(function() {
            $('#cityError').hide();
            $('#errorDiv').hide();
        });
        $('#inputPhone').focus(function() {
            $('#phoneError').hide();
            $('#errorDiv').hide();
        });
        $('#inputBirthday').focus(function() {
            $('#birthdayError').hide();
            $('#errorDiv').hide();
        });
        $('.select2-selection').on('focus', function() {
            $(this).closest('.form-group').find('.error').empty();
            $('#errorDiv').hide();
        });



        //If the detils are fill then display
        $.ajax({
            url: "<?php echo base_url('ProfileController/get_profile_details'); ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                var profilePhotoUrl = '<?php echo base_url("uploads/"); ?>' + response.img;
                if (response.error) {
                    console.log(response.error);
                } else {
                    // Populate text boxes with profile details
                    $('#inputUsername').val(response.username);
                    $('#country').val(response.country_id).trigger('change.select2');
                    $('#inputPhone').val(response.contact);
                    $('#inputBirthday').val(response.dob);
                    $('#imagePreview').attr('src', profilePhotoUrl);
                    // Populate other text boxes as needed
                    $.ajax({
                        url: "<?php echo base_url('ProfileController/get_states'); ?>",
                        type: "POST",
                        dataType: "json",
                        data: {
                            country_id: response.country_id
                        },
                        success: function(states) {
                            var html = '<option value="">Select State</option>';
                            $.each(states, function(id, state) {
                                html += '<option value="' + state.state_id +
                                    '">' +
                                    state
                                    .state + '</option>';
                            });
                            $('#state').html(html);
                            $('#state').val(response.state_id).trigger(
                                'change.select2');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }

                    });
                    $.ajax({
                        url: "<?php echo base_url('ProfileController/get_cities'); ?>",
                        type: "POST",
                        dataType: "json",
                        data: {
                            state_id: response.state_id
                        },
                        success: function(cities) {
                            var html = '<option value="">Select City</option>';
                            $.each(cities, function(id, city) {
                                html += '<option value="' + city.city_id +
                                    '">' + city
                                    .city + '</option>';
                            });
                            $('#city').html(html);
                            $('#city').val(response.city_id).trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });



    });
    </script>
</body>

</html>