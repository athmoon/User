<?php
namespace Phalapi\User\User\Model;
use PhalApi\Model\NotORMModel as NotORM;

class User extends NotORM {

    protected function getTableName($id) {
        return 'user';
    }

    public function getInfo($userId) {
        return $this->getORM()->select('*')->where('id = ?', $userId)->fetch();
    }

    /**
     * 批量获取用户快照，并进行反转，以便外部查找
     */
    public function getSnapshotByUserIds(array $userIds)
    {
        $rs = array();
        if (empty($userIds)) {
            return $rs;
        }

        $rows =self::getORM()
            ->select('id,nickname,avatar')
            ->where('id', $userIds)
            ->fetchAll();

        foreach ($rows as $row) {
            $rs[$row['id']] = $row;
        }

        return $rows;
    }
}