<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileController extends CI_Controller
{

    public function set_user_profile()
    {
        if ($_FILES['imageFile']['name']) {
            $this->update_profile();
        } else {
            $this->edit_profile();
        }
    }
    /**
     * Get the available states on selected Country
     */
    public function get_states()
    {
        $country_id = $this->input->post('country_id');
        $states = $this->ProfileModel->get_states($country_id);
        echo json_encode($states);
    }
    /**
     * Get the available cities on selected states
     */
    public function get_cities()
    {
        $state_id = $this->input->post('state_id');
        $cities = $this->ProfileModel->get_cities($state_id);
        echo json_encode($cities);
    }
    /**
     * Get the mail address of logged in user to display at profile page
     */
    public function get_mail()
    {
        // Check if the user is logged in
        if ($this->session->userdata('logged_in')) {

            $user_id = $this->session->userdata('user_id');
            $data['user_email'] = $this->ProfileModel->get_email($user_id);

            $this->load->view('login2/profile.php', $data);
        } else {
            // Redirect to login page if user is not logged in
            redirect('login2/index2.php');
        }
    }
    /**
     * Update profile when user submited
     */

    public function update_profile(){
    // Check if user is logged in
    $user_id = $this->session->userdata('user_id');
    if (empty($user_id)) {
        echo json_encode(array('status' => 'error', 'message' => 'User not logged in'));
        return;
    }

    // Validate form fields
    $this->form_validation->set_rules('inputUsername', 'Username', 'required|min_length[2]|max_length[200]|callback_name_check');
    $this->form_validation->set_rules('inputPhone', 'Phone Number', 'required|exact_length[10]|numeric');
    $this->form_validation->set_rules('country', 'Country', 'required');
    $this->form_validation->set_rules('state', 'State', 'required');
    $this->form_validation->set_rules('city', 'City', 'required');
    $this->form_validation->set_rules('inputBirthday', 'Birthdate', 'required|callback_validate_birthdate');

    // Check if a new image is uploaded
    $new_image_uploaded = false;
    if ($_FILES['imageFile']['name']) {
        $new_image_uploaded = true;
    }

    if ($this->form_validation->run() == false) {
        // Validation failed
        $array = array(
            'error' => true,
            'inputUsername' => form_error('inputUsername'),
            'country' => form_error('country'),
            'state' => form_error('state'),
            'city' => form_error('city'),
            'inputPhone' => form_error('inputPhone'),
            'inputBirthday' => form_error('inputBirthday'),
            'imageFile' => form_error('imageFile'),
            'message' => 'Please fill the details',
        );
        echo json_encode($array);
        return;
    }

    /**
     * Checking whether the country, state, and city are valid or not
     */
    $country_id = $this->input->post('country');
    $state_id = $this->input->post('state');
    $city_id = $this->input->post('city');
    if (!$this->ProfileModel->check_country($country_id)) {
        echo json_encode(array('error' => true, 'message' => 'Invalid country'));
        return;
    }

    if (!$this->ProfileModel->check_state($country_id, $state_id)) {
        echo json_encode(array('error' => true, 'message' => 'Invalid state'));
        return;
    }

    if (!$this->ProfileModel->check_city($state_id, $city_id)) {
        echo json_encode(array('error' => true, 'message' => 'Invalid city'));
        return;
    }

    // If a new image is uploaded, delete the previously uploaded image
    if ($new_image_uploaded) {
        // Get the profile image file name
        $profile_image = $this->ProfileModel->get_profile_image($user_id);
        if ($profile_image) {
            $image_path = './uploads/' . $profile_image;
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the file
            }
        }
    }

    // Upload the new image
    if ($new_image_uploaded) {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2 * 1024; // 2MB max size
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('imageFile')) {
            echo json_encode(array('error' => true, 'message' => $this->upload->display_errors()));
            return;
        }

        $imgdata = $this->upload->data();
        $image_file = $imgdata['file_name'];
    } else {
        // If no new image is uploaded, use the existing image file name
        $image_file = $this->ProfileModel->get_profile_image($user_id);
    }

    // Form validation passed, proceed with updating the profile
    $data = array(
        'username' => $this->input->post('inputUsername'),
        'contact' => $this->input->post('inputPhone'),
        'dob' => $this->input->post('inputBirthday'),
        'country_id' => $this->input->post('country'),
        'state_id' => $this->input->post('state'),
        'city_id' => $this->input->post('city'),
        'img' => $image_file, // Use the new or existing image file name
    );

    // Update profile_logged flag in the database
    $profile_logged = 1; // Assuming you want to set profile_logged to 1
    $success = $this->ProfileModel->set_profile($user_id, $data, $profile_logged);

    // Send response to AJAX request
    if ($success) {
        echo json_encode(array('success' => true, 'message' => 'Profile updated successfully'));
    } else {
        echo json_encode(array('error' => true, 'message' => 'Failed to update profile'));
    }
}

    /**
     * Edit profile when user submited
     */

    public function edit_profile()
    {
        // Check if user is logged in
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo json_encode(array('status' => 'error', 'message' => 'User not logged in'));
            return;
        }

        // Validate form fields
        $this->form_validation->set_rules('inputUsername', 'Username', 'required|min_length[2]|max_length[200]|callback_name_check');
        $this->form_validation->set_rules('inputPhone', 'Phone Number', 'required|exact_length[10]|numeric');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('inputBirthday', 'Birthdate', 'required|callback_validate_birthdate');

        if ($this->form_validation->run() == false) {
            // Validation failed
            $array = array(
                'error' => true,
                'inputUsername' => form_error('inputUsername'),
                'country' => form_error('country'),
                'state' => form_error('state'),
                'city' => form_error('city'),
                'inputPhone' => form_error('inputPhone'),
                'inputBirthday' => form_error('inputBirthday'),
                'message' => 'Please fill the details',
            );
            echo json_encode($array);
            return;
        }

        // Check if provided country, state, and city are valid
        $country_id = $this->input->post('country');
        $state_id = $this->input->post('state');
        $city_id = $this->input->post('city');

        if (!$this->ProfileModel->check_country($country_id)) {
            echo json_encode(array('error' => true, 'message' => 'Invalid country'));
            return;
        }

        if (!$this->ProfileModel->check_state($country_id, $state_id)) {
            echo json_encode(array('error' => true, 'message' => 'Invalid state'));
            return;
        }

        if (!$this->ProfileModel->check_city($state_id, $city_id)) {
            echo json_encode(array('error' => true, 'message' => 'Invalid city'));
            return;
        }

        // Form validation passed, proceed with updating the profile
        $data = array(
            'username' => $this->input->post('inputUsername'),
            'contact' => $this->input->post('inputPhone'),
            'dob' => $this->input->post('inputBirthday'),
            'country_id' => $country_id,
            'state_id' => $state_id,
            'city_id' => $city_id,
        );

        // Update profile_logged flag in the database
        $profile_logged = 1; // Assuming you want to set profile_logged to 1
        $success = $this->ProfileModel->set_profile($user_id, $data, $profile_logged);

        // Send response to AJAX request
        if ($success) {
            echo json_encode(array('success' => true, 'message' => 'Profile updated successfully'));
        } else {
            echo json_encode(array('error' => true, 'message' => 'Failed to update profile'));
        }
    }

    /**
     * display profile photo  on landing page
     */
    public function getProfileImageName()
    {
        if ($this->session->userdata('logged_in')) {
            $userId = $this->session->userdata('user_id');
            // Fetch profile image name
            $profileImage = $this->ProfileModel->getUserProfileImage($userId);
            echo json_encode(array('img' => $profileImage));
        } else {
            // Return default profile image if user is not logged in or profile image not available
            echo json_encode(array('img' => 'avatar1.jpg'));
        }
    }
    /**
     * Get the logged in user details and display it to landing page
     */
    public function get_profile_details()
    {
        if ($this->session->userdata('logged_in')) {
            $userId = $this->session->userdata('user_id');

            $profile_details = $this->ProfileModel->get_profile_details($userId);

            if ($profile_details) {
                echo json_encode($profile_details);
            } else {
                echo json_encode(array('error' => 'No profile details found'));
            }
        } else {
            echo json_encode(array('Please Fill details'));
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
     * This function validates the birthdate of user
     */
    public function validate_birthdate($birthdate)
    {
        if (empty($birthdate)) {
            $this->form_validation->set_message('validate_birthdate', 'The birthdate field is required.');
            return false;
        }

        // Validate the date format (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) {
            $this->form_validation->set_message('validate_birthdate', 'Invalid birthdate format. Please use YYYY-MM-DD.');
            return false;
        }
        // Convert birthdate and current date to Unix timestamps
        $birthdate_timestamp = strtotime($birthdate);
        $current_date_timestamp = time();

        // Check if the birthdate is in the future (after the current date)
        if ($birthdate_timestamp > $current_date_timestamp) {
            $this->form_validation->set_message('validate_birthdate', 'Birthdate cannot be in the future.');
            return false;
        }
        return true; // Validation passed
    }

}
