<?php
namespace Phalapi\User\User\Domain;
use Phalapi\User\User\Model\User as Model_User_User;
use Phalapi\User\User\Model\UserLogin as Model_User_UserLogin;
/**
 * 用户领域类
 *
 */

class Login {

    public function isFirstBind($openId) {
        $model = new Model_User_UserLogin();
        return $model->isFirstBind($openId);
    }

    public function getUserIdByWxOpenId($openId) {
        if (empty($openId)) {
            return array();
        }

        $model = new Model_User_UserLogin();
        return $model->getUserIdByWxOpenId($openId);
    }

    public function bindUser($userId, $openId, $token, $expiresIn) {
        $data = array();
        $data['wx_openid'] = $openId;
        $data['wx_token'] = $token;
        $data['wx_expires_in'] = $expiresIn;
        $data['user_id'] = $userId;

        $model = new Model_User_UserLogin();
        return $model->insert($data);
    }
}