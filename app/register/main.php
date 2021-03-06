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
	public function get_access_rule()
	{
		$rule_action['rule_type'] = 'black';

		return $rule_action;
	}

	public function setup()
	{
		HTTP::no_cache_header();

		if ($this->user_id)
		{
			HTTP::redirect('/');
		}

		if (get_setting('register_type') == 'close')
		{
			H::redirect_msg(AWS_APP::lang()->_t('本站目前关闭注册'), '/');
		}
		else if (get_setting('register_type') == 'invite')
		{
			H::redirect_msg(AWS_APP::lang()->_t('本站只接受邀请注册'), '/');
		}
	}

	public function index_action()
	{
		$this->crumb(AWS_APP::lang()->_t('注册'));

		TPL::import_css('css/register.css');

		TPL::assign('captcha_required', $this->model('register')->is_captcha_required());

		TPL::output('account/register');
	}

	public function next_action()
	{
		if (!$_POST['agree'])
		{
			H::redirect_msg(AWS_APP::lang()->_t('你必需同意 %s 才能继续', get_setting('user_agreement_name')), '/register/');
		}

		$captcha_required = $this->model('register')->is_captcha_required();

		// 检查验证码
		if ($captcha_required)
		{
			if (!AWS_APP::captcha()->is_valid($_POST['captcha']))
			{
				H::redirect_msg(AWS_APP::lang()->_t('请填写正确的验证码'), '/register/');
			}
		}

		$this->crumb(AWS_APP::lang()->_t('注册'));

		TPL::import_css('css/register.css');

		TPL::import_js('js/bcrypt.js');

		TPL::assign('captcha_required', $captcha_required);
		TPL::assign('client_salt', $this->model('password')->generate_client_salt());

		TPL::output("account/register_next");
	}

}