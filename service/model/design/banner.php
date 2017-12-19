<?php

class ModelDesignBanner extends Model {
    private function getFilterImageIds($banner_id) {
        $DB_PREFIX = DB_PREFIX;
        $now = new DateTime();
        $nowStr = ($now->format('Y-m-d')) . ' 00:00:00';

        $sql = "(select i.banner_image_id from ts_banner_period p join {$DB_PREFIX}banner_period_item i on p.id=i.period_id "
            . "where p.banner_id={$banner_id} and p.start_date<='{$nowStr}' and p.end_date>='{$nowStr}' ) "
            ;
          /*
           *   . "UNION "
            . "(select i.banner_image_id from {$DB_PREFIX}banner_image i "
            . "WHERE i.banner_id={$banner_id} and i.banner_image_id not in "
            . "(select  i2.banner_image_id from {$DB_PREFIX}banner_period_item i2) ) ";*/

        $query = $this->db->query($sql);

        $rows = $query->rows;

        $ids = array();
        if (!empty($rows)) {
            foreach ($rows as $row)
                $ids[] = $row['banner_image_id'];
        }
        return $ids;
    }


    public function getBanner($banner_id) {
        $imageIds = $this->getFilterImageIds($banner_id);
        
        if(!empty($imageIds)&&$imageIds){
        $imageIdsFilter = " and bi.banner_image_id in (" . join(',', $imageIds) . ") ";
        $sql = "SELECT * FROM " . DB_PREFIX
            . "banner_image bi LEFT JOIN " . DB_PREFIX
            . "banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) LEFT JOIN " . DB_PREFIX
            . "banner b ON (b.banner_id  = bi.banner_id) WHERE b.status=1 AND (b.language_id=0 OR b.language_id='" . (int)$this->config->get('config_language_id') . "') AND  bi.banner_id = '" . (int)$banner_id
            . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id'). "' "
            .$imageIdsFilter
            . " ORDER BY bi.sort_order ASC,bi.banner_image_id DESC";

        $query = $this->db->query($sql);
        return $query->rows;
        }
        else{
        	return array();
        }
       
    }
}

?>