<?php
namespace Phalapi\User\User\Domain;
use Phalapi\User\User\Model\User as Model_User_User;
/**
 * 用户领域类
 *
 */

class Info {

    public function getUserInfo($userId) {
        $rs = array();
        
        $userId = intval($userId);
        if ($userId <= 0) {
            return $rs;
        }

        $model = new Model_User_User();
        $rs = $model->get($userId, 'id, username, nickname, avatar'); //按需获取

        if (empty($rs)) {
            return $rs;
        }

        $rs['user_id'] = intval($rs['id']);
        unset($rs['id']);

        return $rs;
    }
}