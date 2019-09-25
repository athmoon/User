<?php
namespace PhalApi\User;
use PhalApi\Translator;

class Lite {

public function __construct($debug = false) {
        $this->init($debug);
    }

    protected function init($debug) {
        DI()->loader->addDirs('./Library/User/User');
        Translator::addMessage(API_ROOT . '/vendor/phalapi/user/src');
    }

    /**
     * 登录检测
     * @param boolean $isExitIfNotLogin 是否抛出异常以便让接口错误返回
     * @return boolean
     * @throws PhalApi_Exception_BadRequest
     */
    public function check($isExitIfNotLogin = false) {
        $userId = DI()->request->get('user_id');
        $token = DI()->request->get('token');

        //是否缺少必要参数
        if (empty($userId) || empty($token)) {
            DI()->logger->debug('user not login', array('userId' => $userId, 'token' => $token));

            if ($isExitIfNotLogin) {
                throw new PhalApi_Exception_BadRequest(T('user not login'), 1);
            }
            return false;
        }

        $model = new Model_User_UserSession();
        $expiresTime = $model->getExpiresTime($userId, $token);

        //是否已过期
        if ($expiresTime <= $_SERVER['REQUEST_TIME']) {
            DI()->logger->debug('user need to login again', 
                array('expiresTime' => $expiresTime, 'userId' => $userId, 'token' => $token));

            if ($isExitIfNotLogin) {
                throw new PhalApi_Exception_BadRequest(T('user need to login again'), 1);
            }
            return false;
        }

        return true;
    }

    /**
     * 退出登录
     */
    public function logout() {
        $this->_renewalTo($_SERVER['REQUEST_TIME']);
    }

    /**
     * 心跳
     *
     * - 自动续期
     */
    public function heartbeat() {
        $this->_renewalTo($_SERVER['REQUEST_TIME'] + Domain_User_User_Session::getMaxExpireTime());
    }

    /**
     * 为用户生成一个会话
	 * @param int $userId 用户ID
	 * @param string $client 客户端设备标识，默认为空
	 * @return string 会话token，返回给客户保存，以便后续请求传递此token作登录态验证
     */
    public function generateSession($userId, $client = '') {
        return Domain_User_User_Session::generate($userId, $client);
    }

    /**
     * 续期
     *
     * - 当有效期为当前时间时，即退出
     */
    protected function _renewalTo($newExpiresTime) {
        $userId = DI()->request->get('user_id');
        $token = DI()->request->get('token');

        if (empty($userId) || empty($token)) {
            return;
        }
        
        $model = new Model_User_UserSession();
        $model->updateExpiresTime($userId, $token, $newExpiresTime);
    }
}
}
