<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileModel extends CI_Model
{
    //get countries
    public function get_countries()
    {
        $query = $this->db->get('Countries');
        return $query->result();
    }
    /**
     * Check existing countries
     */
    public function check_country($country_id)
    {
        $this->db->where('country_id', $country_id);
        $query = $this->db->get('Countries');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //get states
    public function get_states($country_id)
    {
        $this->db->where('country_id', $country_id);
        $query = $this->db->get('States');
        return $query->result();
    }
    /**
     * Check existing states
     */

    public function check_state($country_id, $state_id)
    {
        $this->db->where('country_id', $country_id);
        $this->db->where('state_id', $state_id);
        $query = $this->db->get('States');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //get cities
    public function get_cities($state_id)
    {
        $this->db->where('state_id', $state_id);
        $query = $this->db->get('Cities');
        return $query->result();
    }
    /**
     * Check existing countries
     */
    public function check_city($state_id, $city_id)
    {
        $this->db->where('state_id', $state_id);
        $this->db->where('city_id', $city_id);
        $query = $this->db->get('Cities');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get email
    public function get_email($user_id)
    {
        $this->db->select('email');
        $this->db->where('id', $user_id);
        $query = $this->db->get('Users');
        return $query->result();
    }
    //Update profile
    public function set_profile($user_id, $data, $profile_logged)
    {
        $this->db->where('id', $user_id);
        $this->db->set($data);
        $this->db->set('profile_logged', $profile_logged);
        return $this->db->update('Users');
    }

    /**
     * Display the profile from landing page 
     */
    public function get_profile_details($userId)
    {
        // Assuming you have a table named 'users' with columns username, country, state, city, phone_number, birthdate, and profile_photo_url
        $this->db->where('id', $userId);
        $query = $this->db->get('Users');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;

        }

    }
    //disply profile on landing
    public function getUserProfileImage($userId)
    {
        $this->db->select('img');
        $this->db->where('id', $userId);
        $query = $this->db->get('Users');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->img;
        } else {
            return 'avatar1.jpg'; // Default profile image if not found
        }
    }
    /**
     * Get the user profile image
     */

     public function get_profile_image($user_id) {
        $this->db->select('img');
        $this->db->where('id', $user_id);
        $query = $this->db->get('Users');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->img;
        }
        return false;
    }

}
