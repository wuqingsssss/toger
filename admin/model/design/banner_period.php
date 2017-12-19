<?php

class ModelDesignBannerPeriod extends Model {
    public function all($bannerId) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select sp.*,count(pp.banner_image_id) itemsNum  "
            . "from {$DB_PREFIX}banner_period sp left join {$DB_PREFIX}banner_period_item pp on pp.period_id=sp.id "
            ."where sp.banner_id={$bannerId} "
            . "group by sp.id order by sp.start_date ";

        $q = $this->db->query($sql);
        return $q->rows;
    }

    public function get($id) {
        $pkArr = array(
            array('id', $id, false),
        );

        return DbHelper::get('banner_period', $pkArr);
    }

    public function create($data) {
        $params = array();
        $fieldName = 'banner_id';
        $params[$fieldName] = array($fieldName, $data[$fieldName], false, true);
        $fieldName = 'name';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'start_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'end_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'creator_id';
        $params[$fieldName] = array($fieldName, $data[$fieldName], false, false);

        $fieldName = 'created_at';
        $params[$fieldName] = array($fieldName, 'now()', false, false);


        $id = DbHelper::insert('banner_period', $params);

        $imageIds = json_decode(str_replace('&quot;', '"', $data['imageIds']));
        $this->updateItems($id, $imageIds);

//        $this->refreshBannerPeriods();
    }

    private function insertItems($periodId, $imageIds) {
        $colDefines = array(
            array('banner_image_id', false, false),
            array('period_id', false, false)
        );
        $data = array();
        foreach ($imageIds as $pid) {
            $data[] = array($pid, $periodId);
        }
        DbHelper::bulkInsert('banner_period_item', $colDefines, $data);
    }

    private function removeItems($periodId, $imageIds) {
        $DB_PREFIX = DB_PREFIX;
        foreach ($imageIds as $pid) {
            $sql = "delete from {$DB_PREFIX}banner_period_item where banner_image_id={$pid} and period_id={$periodId} ";
            $this->db->query($sql);
        }
    }

    private function updateItems($periodId, $imageIds) {
        $oldPids = $this->getOldItemIds($periodId);
        $deletedPids = array_diff($oldPids, $imageIds);
        $addedPids = array_diff($imageIds, $oldPids);

        if (!empty($addedPids)) {
            $this->insertItems($periodId, $addedPids);
        }

        if (!empty($deletedPids)) {
            $this->removeItems($periodId, $deletedPids);
        }

//        $this->refreshBannerPeriods();
    }

    public function update($id, $data) {
        $params = array();
        $fieldName = 'name';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);
        $fieldName = 'start_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $fieldName = 'end_date';
        $params[$fieldName] = array($fieldName, $data[$fieldName], true, true);

        $pkArr = array(
            array('id', $id, false),
        );
        DbHelper::update('banner_period', $pkArr, $params);

        $imageIds = json_decode(str_replace('&quot;', '"', $data['imageIds']));
        $this->updateItems($id, $imageIds);
    }

    public function delete($id) {
        $DB_PREFIX = DB_PREFIX;
        $id = (int)$id;

        $sql = "delete from {$DB_PREFIX}banner_period where id={$id}";
        $this->db->query($sql);

//        auto delete by foreign key cascade
        $sql = "delete from {$DB_PREFIX}banner_period_item where period_id={$id}";
        $this->db->query($sql);
    }

    public function getItems($periodId) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select * from {$DB_PREFIX}banner_period_item p "
            . "where p.period_id={$periodId} ";
        $q = $this->db->query($sql);
        return $q->rows;
    }

    private function getOldItemIds($periodId) {
        $DB_PREFIX = DB_PREFIX;
        $sql = "select pp.banner_image_id from {$DB_PREFIX}banner_period_item pp where pp.period_id=" . $periodId;
        $q = $this->db->query($sql);
        $oldPids = array();
        foreach ($q->rows as $item) {
            $oldPids[] = $item['banner_image_id'];
        }
        return $oldPids;
    }

    private function refreshBannerPeriods() {
        $DB_PREFIX = DB_PREFIX;

        $now = new DateTime();
        $nowStr = ($now->format('Y-m-d')) . ' 00:00:00';
        $sql = "select sp.id from {$DB_PREFIX}banner_period sp "
            . "where sp.start_date<='{$nowStr}' and sp.end_date>='{$nowStr}' "
            . "order by sp.start_date limit 1 ";
        $activePeriodId = DbHelper::getSingleValue($sql, null);

        $updateSql = null;
        if (is_null($activePeriodId)) {
            $updateSql = "update {$DB_PREFIX}banner p set p.`status`='0' where p.banner_id in "
                . "(select pp.banner_id from {$DB_PREFIX}banner_period_item pp) ";
        } else {
            $updateSql = "update {$DB_PREFIX}banner p, {$DB_PREFIX}banner_period_item pp,{$DB_PREFIX}banner_period sp "
                . "set p.`status`='1' , p.date_available=sp.start_date "
                . "where p.banner_id=pp.banner_id and pp.period_id={$activePeriodId} and sp.id=pp.period_id ";
        }
        $this->db->query($updateSql);
    }

}

?>