<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegisterModel extends CI_Model
{
    public function insertUser($email, $password, $username, $verification_token)
    {
        $data = array(
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'username' => $username,
            'token' => $verification_token,
            'updated_at' => null,
            'deleted_at' => null,
        );

        // Insert data into the 'Users' table
        $this->db->insert('Users', $data);

        // Check if the insert was successful
        if ($this->db->affected_rows() > 0) {
            return true; // Insert successful
        } else {
            return false; // Insert failed
        }
    }
    public function get_user_by_verification_token($verification_token)
    {
        $query = $this->db->get_where('Users', array('token' => $verification_token));
        return $query->row();
    }

    public function activate_user($user_id)
    {
        $data = array('user_status' => 1, 'token' => null);
        $this->db->where('id', $user_id);
        $this->db->update('Users', $data);
    }
    /**
     * Get the user email to checj wheather it exist or not 
     */
    public function check_email_exists($email) {
        $query = $this->db->get_where('Users', array('email' => $email));
        return $query->num_rows() > 0;
    }

}