<?php

namespace Phalapi\User\User\Model;
use PhalApi\Model\NotORMModel as NotORM;

class UserLogin extends NotORM {

    protected function getTableName($id) {
        return 'user_login_weixin';
    }

    public function getBindInfo($openId) {
        $rs = $this->getORM()->where('wx_openid', $openId)->fetch();
        return !empty($rs) ? $rs : array();
    }

    public function isFirstBind($openId) {
        $num = $this->getORM()->where('wx_openid', $openId)->count('id');
        return $num == 0 ? true : false;
    }

    public function getUserIdByWxOpenId($openId) {
        $rs = $this->getORM()->select('user_id')->where('wx_openid', $openId)->fetch();
        return !empty($rs) ? intval($rs['user_id']) : 0;
    }
}