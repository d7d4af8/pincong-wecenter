<?php
/*
+--------------------------------------------------------------------------
|   WeCenter [#RELEASE_VERSION#]
|   ========================================
|   by WeCenter Software
|   © 2011 - 2014 WeCenter. All Rights Reserved
|   http://www.wecenter.com
|   ========================================
|   Support: WeCenter@qq.com
|
+---------------------------------------------------------------------------
*/

if (!defined('IN_ANWSION'))
{
	die;
}

class main extends AWS_CONTROLLER
{
	public function setup()
	{
		HTTP::no_cache_header();
	}

	public function index_action()
	{
		HTTP::redirect('/profile/');
	}

	public function logout_action($return_url = '/')
	{
		if ($_GET['key'] != md5(session_id()))
		{
			H::redirect_msg(AWS_APP::lang()->_t('正在准备退出, 请稍候...'), '/account/logout/?key=' . md5(session_id()));
		}

		$this->model('login')->logout();

		$this->model('admin')->admin_logout();

		{
			HTTP::redirect($return_url);
		}
	}

	public function change_password_action()
	{
		$this->crumb(AWS_APP::lang()->_t('修改密码'));

		TPL::import_css('css/register.css');

		TPL::import_js('js/bcrypt.js');
		if ($this->user_info['password_version'] < 2)
		{
			TPL::import_js('js/md5.js');
		}

		TPL::assign('client_salt', $this->model('password')->generate_client_salt());

		TPL::output("account/change_password");
	}

	public function password_updated_action()
	{
		$url = '/';

		H::redirect_msg(AWS_APP::lang()->_t('密码修改成功, 请您妥善保管新密码'), $url);
	}

}