<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{

    /**
     * This method is called for validating user while log in
     */
    public function validate_user($email, $password)
    {

        $query = $this->db->get_where('Users', array('email' => $email));
        $user = $query->row();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        } else {
            return "Invalid username or password";
        }

    }

}
