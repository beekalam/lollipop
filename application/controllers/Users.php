<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/BaseController.php");
require_once(APPPATH . 'libraries/jdf.php');
require_once(APPPATH . 'libraries/lib_date.php');


class Users extends BaseController
{


    function __construct()
    {
        parent::__construct();
        $this->load->model("Users_model");
        $this->load->model("Customers_model");
        $this->load->model("Settings_model");
        $this->load->library('Jalali_date');
    }

    public function profile()
    {

        // $data = file_get_contents("http://2.gravatar.com/avatar/kc8b6d0141330457d7b8e357520a0ccb?s=75&r=g");
        // $img=  base64_img_encode($data);
        // $this->db->set('img',$img);
        // $this->db->where('id',14);
        // $res = $this->db->update('users');

        // $res = $this->db->get_where('users',array('id'=>14))->result();
        // if(count($res)){
        //     $res = $res[0];
        //     echo "<img src='" . $res->img . "' />";
        //     exit;
        // }

        // echo "<img src='" . $img . "' />";
        // exit;

        $id  = $this->input->get('id');
        $row = $this->db->get_where("users", array("id" => $id))->result();

        $data["user"] = null;
        if (count($row) > 0) {
            $data["user"] = $row[0];
        }
        $this->view('users/profile', $data);
    }

    public function edit()
    {
        if (!$this->is_post_request()) return;


        $first_name = $this->input->post('first_name');
        $last_name  = $this->input->post('last_name');
        $id         = $this->input->post('id');
        $img        = $this->input->post('img_profile');

        // pre([$first_name,$last_name,$id]);

        $this->db->set('first_name', $first_name);
        $this->db->set('last_name', $last_name);
        if (!empty($img)) $this->db->set('img', $img);

        $this->db->where('id', $id);

        if ($this->db->update('users')) {
            $this->session->set_flashdata('msg', 'مشخصات با موفقیت تغییر یافت');
            if (!empty($img)) $this->session->set_userdata('profile_img', $img);
            $this->session->set_userdata('name', $first_name . " " . $last_name);
        } else {
            $this->session->set_flashdata('msg', 'خطا در تغییر مشخصات');
        }
        redirect("users/profile?id={$id}");
    }

    public function user_change_password()
    {
        if (!$this->is_post_request()) return;

        $id              = $this->input->post('id');
        $old_password    = $this->input->post('previous_password');
        $new_password    = $this->input->post('new_password');
        $repeat_password = $this->input->post('repeat_password');

        // pre([$id,$old_password,$new_password,$repeat_password]);

        $row = $this->db->get_where('users', array('id' => $id))->result();

        if (count($row) > 0) {
            $row = $row[0];
            if (sha1($old_password) != $row->password) {
                $this->session->set_flashdata('msg', 'پسورد قدیمی اشتباه وارد شده است.');
                redirect("users/profile?id={$id}");
            }

            if (empty($new_password) || $repeat_password != $new_password) {
                $this->session->set_flashdata('msg', 'پسورد و تکرار آن مشابه نمیباشند');
                redirect("users/profile?id={$id}");
            }

            $this->db->set('password', sha1($new_password));
            $this->db->where('id', $id);
            if ($this->db->update('users')) {
                $this->session->set_flashdata('msg', 'پسورد با موفقیت تغییر یافت');
                redirect("users/profile?id={$id}");
            } else {
                $this->session->set_flashdata('msg', 'خطا در تغییر پسورد');
                redirect("users/profile?id={$id}");
            }

        }

    }

    public function users()
    {
        if (!$this->check_perm("view_users")) return;


        $users = $this->db->get('users')->result();
        $roles = $this->db->get('roles')->result_array();
        if (count($roles) == 0) {
            $this->session->set_flashdata('msg', "قبل از ایجاد کاربر باید رول تعریف کنید");
            redirect('users/roles');
        }
        foreach ($users as &$user) {
            $user->role_name = "NA";
            foreach ($roles as $role) {
                if ($role["id"] == $user->role_id) {
                    $user->role_name = $role["name"];
                    break;
                }
            }
        }

        $data["users"]       = $users;
        $data['active_menu'] = 'm-manage-users';
        $data['roles']       = $roles;
        $this->view('users/users', $data);
    }


    public function delete_user()
    {
        if (!$this->is_post_request()) return;
        if (!$this->check_perm('delete_user')) return;

        $res = $this->db->delete('users', array("id" => $this->input->post('what')));
        if ($res) {
            $this->session->set_flashdata('msg', "کاربر با موفقیت حذف شد");
            return ejson(array('success' => 'true'));
        } else {
            return ejson(array('success' => 'false', 'error' => 'خطا در حذف کاربر'));
        }
    }


    public function change_password()
    {
        if (!$this->is_post_request()) return;

        if (isset($_POST['new-password']) && isset($_POST['user-id'])) {
            $this->db->set('password', sha1($this->input->post('new-password')));
            $this->db->where('id', $this->input->post('user-id'));
            if ($this->db->update('users')) {
                $this->session->set_flashdata("msg", "پسورد با موفقیت تغییر یافت");
                redirect("/users/users");
            } else {
                $this->session->set_flashdata("msg", "خطا در تغییر پسورد");
                redirect("/users/users");
            }
        }

        $this->session->set_flashdata("msg", "خطا در تغییر پسورد");
        redirect("/users/users");
    }


    public function new_user()
    {
        if (!$this->is_post_request()) return;
        if (!$this->check_perm('add_user')) return;

        $res = $this->db->insert('users', array(
            "first_name" => $this->input->post('first_name'),
            "last_name"  => $this->input->post('last_name'),
            "user_name"  => $this->input->post('username'),
            "password"   => $this->input->post('password'),
            "role_id"    => $this->input->post('role_type'),
            "isadmin"    => false));

        if ($res) {
            $this->session->set_flashdata("msg", "کاربر جدید ثبت شد");
            redirect("/users/users");
        }

        $this->session->set_flashdata("msg", "خطا در ثبت کاربر جدید");
        redirect("/users/users");
    }

    public function roles()
    {
        //todo allow admin only
        global $perms_descriptions;
        global $perms;
        $data['active_menu'] = 'm-manage-users';
        $data["roles"]       = $this->db->get('roles')->result();
        // pre($data);
        $data['roles_descriptions'] = $perms_descriptions;
        $data['default_perms']      = $perms;
        $this->view('users/roles', $data);
    }

    public function update_role()
    {
        if (!$this->is_admin()) return;
        if (!$this->is_post_request()) return;
        global $perms;
        $p = $perms;

        foreach ($p as $k => &$v) {
            $v = false;
            if (isset($_POST[$k]) && $_POST[$k] == true) {
                $v = true;
            }
        }

        $this->db->set("value", json_encode($p));
        $this->db->where('id', $_POST['role_id']);
        $res = $this->db->update('roles');
        if ($res) {
            $this->session->set_flashdata('msg', 'عملیات با موفقیت انجام شد');
            redirect('/users/roles/');
        }
    }

    public function delete_role()
    {
        // pre("in delete_role");
        if (!$this->is_admin()) return;
        if (!$this->is_post_request()) return;
        // pre("in dleete role");
        $role_id = $this->input->post('role_id');
        global $perms;

        $msg             = "";
        $users_with_role = $this->db->get_where('users', array('role_id' => $role_id))->result();

        if (count($users_with_role) != 0) {
            // return ejson(array("success"=>false,"error"=>"کاربرانی با این رول وجود دارند."));
            $msg = "error|" . "کاربرانی با این رول وجود دارند.";
        } else {

            $res = $this->db->delete('roles', array('id' => $role_id));
            if ($res) {
                $msg = "عملیات با موفقیت انجام شد";
            }
        }
        $this->session->set_flashdata("msg", $msg);
        redirect('/users/roles');
    }

    public function add_role()
    {
        global $perms;

        $msg = "عملیات با موفقیت انجام شد";

        if (isset($_POST['role_name']) && !empty($_POST['role_name'])) {
            $role_name = $this->input->post('role_name');
            //fixme check for unique role
            $res = $this->db->get_where('roles', array('name' => $role_name))->result();
            if (count($res) != 0) {
                $msg = "این نام دسته قبلا استفاده شده";
            } else {
                $res = $this->db->insert('roles', array('name' => $role_name, 'value' => json_encode($perms)));
                if (!$res) {
                    $msg = "خطا در ذخیره سازی رول";
                }
            }
        } else {
            $msg = "نام رول وارد شده معتبر نمی باشد.";
        }

        $this->session->set_flashdata("msg", $msg);
        redirect("/users/roles");
    }

    public function change_role()
    {
        $ok = isset($_POST['role_type']) && !empty($_POST['role_type']);
        $ok = $ok && isset($_POST['user_id']) && !empty($_POST['user_id']);

        if ($ok) {
            $role_id = $this->input->post('role_type');
            $user_id = $this->input->post('user_id');
            $this->db->set('role_id', $role_id);
            $this->db->where('id', $user_id);
            $ok = $this->db->update('users');
            if (!$ok) {
                $this->session->set_flashdata("msg", "error|" . "خطا در ذخیره سازی");
            } else
                $this->session->set_flashdata("msg", "success|" . "اطلاعات با موفقیت ثبت شد");
        } else {
            $this->session->set_flashdata("msg", "error|" . "مقدار نامعتبر");
        }

        redirect('users/users');
    }
}
