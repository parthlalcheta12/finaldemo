<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('PHPMailer_lib');
    }
    public function index()
    {
        $this->load->view('login2/index2.php');
    }
    public function register()
    {
        $this->load->view('login2/register.php');
    }
    public function login()
    {
        $this->load->view('login2/index2.php');
    }
    public function landing()
    {
        $this->load->view('login2/landing.php');
    }
    public function profile()
    {
        $data['countries'] = $this->ProfileModel->get_countries();
        $data['user_email'] = $this->get_email();

        $this->load->view('login2/profile.php', $data);

    }

    /**
     * This method is called for resigter user form submit user_registration
     */

    public function user_registration()
    {
        /**
         * this is validation part for check register user form submission validation
         */
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[2]|max_length[200]|callback_name_check');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == false) {
            // Validation failed, reload the registration form with errors
            $array = array(
                'error' => true,
                'name' => form_error('name'),
                'email_error' => form_error('email'),
                'password_error' => form_error('password'),
                'confirmPassword' => form_error('confirmPassword'),
                'message' => 'Please fill the details',
            );
            echo json_encode($array);
        } else {
            // Retrieve data from POST request
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $username = $this->input->post('name');
            $verification_token = md5(uniqid(rand(), true));

            // Call the model function to insert user
            $inserted = $this->RegisterModel->insertUser($email, $password, $username, $verification_token);
            if ($inserted) {
                //Sending Verification mail
                $this->send_verification_email($email, $verification_token);
                // Insert successful
                echo json_encode(array('success' => true, 'message' => 'User inserted successfully !! Verification mail is sent to your email address', 'profile_logged' => false));

            } else {
                echo 'Registration failed';
            }
        }
    }

    /**
     * This method is called for sending email to registered users
     */

    private function send_verification_email($to_email, $verification_token)
    {
        $email = $this->phpmailer_lib->load();
        // SMTP Configuration
        $email->isSMTP();
        $email->Host = 'smtp.gmail.com'; // Your SMTP host
        $email->SMTPAuth = true;
        $email->Username = 'lalchetaparth@gmail.com'; // Your SMTP username
        $email->Password = 'oxwfxppvvktielbh'; // Your SMTP password
        $email->SMTPSecure = 'tls';
        $email->Port = 587;

        // Email Settings
        $email->setFrom('lalchetaparth@gmail.com', 'Parth lalcheta');
        $email->addAddress($to_email); // Add a recipient
        $email->isHTML(true);
        $verification_link = base_url('Main/verify/' . $verification_token);
        $email->Subject = 'Account Verification';
        $email->Body = 'Welcome! Please verify your account by clicking the following link: ' . $verification_link;
        $email->send();

    }
    /**
     * This method is called for sending a verification token to user 
     */

    public function verify($verification_token)
    {
        $user = $this->RegisterModel->get_user_by_verification_token($verification_token);
        if ($user) {
            // Activate user account
            $this->RegisterModel->activate_user($user->id);
            $this->login();
        } else {
            echo 'Invalid verification token.';
        }
    }
    /**
     * This function validates the name of user
     */
    public function name_check($str)
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $str)) {
            $this->form_validation->set_message('name_check', 'The {field} field can only contain letters, apostrophes, dashes, and spaces.');
            return false;
        } else {
            return true;
        }
    }
    /**
     * This function will check wheater email already exist in database
     */

    public function email_check($email)
    {
        // Check if email exists in the database
        $email_exists = $this->RegisterModel->check_email_exists($email);

        if ($email_exists) {
            $this->form_validation->set_message('email_check', 'The {field} already exists.');
            return false;
        } else {
            return true;
        }
    }
    /**
     * This is a main login function
     */
    public function User_login()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        if ($this->form_validation->run() == false) {
            $array = array(
                'error' => true,
                'emailError' => form_error('email'),
                'password_error' => form_error('password'),
                'message' => 'Please fill the details',
            );
            echo json_encode($array);
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $user = $this->UserModel->validate_user($email, $password);
            if ($user) {
                //user verified or not
                if ($user->user_status == 0) {
                    echo json_encode(array('success' => false, 'message' => 'User Status is inactive'));
                    return false;

                }

                //user status condition
                if ($user->token) {
                    echo json_encode(array('success' => false, 'message' => 'User is not verified'));
                    return false;

                }

                //user delete

                if ($user->profile_logged == 1) {
                    // Redirect to profile pag
                    $user_data = array(
                        'user_id' => $user->id,
                        'logged_in' => true,
                    );
                    $this->session->set_userdata($user_data);
                    echo json_encode(array('success' => true, 'message' => 'login sucessful', 'profile_logged' => true));
                } else {
                    // Redirect to landing page
                    $user_data = array(
                        'user_id' => $user->id,
                        'logged_in' => true,
                    );
                    $this->session->set_userdata($user_data);
                    echo json_encode(array('success' => true, 'message' => 'login sucessful', 'profile_logged' => false));

                }
            } else {
                echo json_encode(array('success' => false, 'message' => 'Invalid email or password or else verify email address'));
            }
        }
    }

    /**
     * This is a Gets the eamil of the logged in user
     */
    public function Get_email()
    {
        if ($this->session->userdata('logged_in')) {

            // Get the user's email using the logged-in user's ID
            $user_id = $this->session->userdata('user_id');
            $data = $this->ProfileModel->get_email($user_id);

            // Load the profile view and pass the data
            return $data;
        } else {
            // Redirect to login page if user is not logged in
            redirect('login2/index2.php');
        }
    }
    // -----------------------------------------------LOAD DATA IN PROFILE-----------------------------------------

   /**
    * method to display user data to profile when user is logged_in
    */
    public function getUserDetails()
    {
        if ($this->session->userdata('logged_in')) {
            $userId = $this->session->userdata('user_id');
            $userData = $this->ProfileModel->getUserDetailsById($userId);
            $data['userData'] = $userData;
            return $data;
        } else {
            // User is not logged in, send empty data
            $data['userData'] = array();
        }

        // Load the view with user data
        $this->load->view('login2/profile.php', $data);
    }

    /**
     * Method to logout and destory the user session 
     */
    public function logout()
    {
        //This code for destroy the profile
        $this->session->sess_destroy();
        // Send response
        $response['success'] = true;
        echo json_encode($response);
    }
}
