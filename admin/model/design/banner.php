<?php

class ModelDesignBanner extends Model {
    public function addBanner($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");

        $banner_id = $this->db->getLastId();

        if (isset($data['language_id'])) {
            $this->editBannerLanguageId($banner_id, $data['language_id']);
        }

        if (isset($data['banner_image'])) {
            foreach ($data['banner_image'] as $banner_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" . $this->db->escape($banner_image['link']) . "', image = '" . $this->db->escape($banner_image['image']) ."', sort_order = '" . $this->db->escape($banner_image['sort_order'])."'");

                $banner_image_id = $this->db->getLastId();

                foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" . $this->db->escape($banner_image_description['title']) . "'");
                }
            }
        }
    }

    private function editBannerLanguageId($banner_id, $language_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "banner SET language_id = '" . (int)$language_id . "' WHERE banner_id = '" . (int)$banner_id  ."'");
    }

    public function editBanner($banner_id, $data) {

        $this->db->query("UPDATE " . DB_PREFIX . "banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE banner_id = '" . (int)$banner_id . "'");

        if (isset($data['language_id'])) {
            $this->editBannerLanguageId($banner_id, $data['language_id']);
        }

//		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");

        $saveImageIds = array();
        if (isset($data['banner_image'])) {
            foreach ($data['banner_image'] as $banner_image) {
                $banner_image_id = $banner_image['banner_image_id'];

                $saveData = array(
                    array('banner_id', (int)$banner_id, false),
                    array('link', $banner_image['link'], true, true),
                    array('image', $banner_image['image'], true, true),
                    array('sort_order', $banner_image['sort_order'], true, true),
                );

                if (empty($banner_image_id)) {
                    $banner_image_id = DbHelper::insert('banner_image', $saveData);
                } else {
                    $pkArray = array(
                        array('banner_image_id', (int)$banner_image_id, false),
                    );
                    DbHelper::update('banner_image', $pkArray, $saveData);
                }

                $saveImageIds[] = $banner_image_id;


                foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" . $this->db->escape($banner_image_description['title']) . "'");
                }
            }
        }

        if (!empty($saveImageIds)) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id={$banner_id} and banner_image_id not  in (" . join(',', $saveImageIds) . ")");

            $this->db->query("DELETE FROM " . DB_PREFIX . "banner_period_item WHERE period_id in(SELECT id FROM ". DB_PREFIX ."banner_period WHERE banner_id={$banner_id} ) and banner_image_id not in (" . join(',', $saveImageIds) . ")"); 
        
        }
    }

    public function deleteBanner($banner_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_period_item WHERE banner_image_id in(select banner_image_id from " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "')");
             
        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");
    }

    public function getBanner($banner_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");

        return $query->row;
    }

    public function getBanners($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "banner";

        $sort_data = array(
            'name',
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getBannerImages($banner_id) {
        $banner_image_data = array();

        $banner_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "' ORDER BY banner_image_id ASC");

        foreach ($banner_image_query->rows as $banner_image) {
            $banner_image_description_data = array();

            $banner_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner_id . "'");

            foreach ($banner_image_description_query->rows as $banner_image_description) {
                $banner_image_description_data[$banner_image_description['language_id']] = array('title' => $banner_image_description['title']);
            }

            $banner_image_data[] = array(
                'banner_image_id' => $banner_image['banner_image_id'],
                'banner_image_description' => $banner_image_description_data,
                'link' => $banner_image['link'],
                'image' => $banner_image['image'],
                'sort_order' => $banner_image['sort_order'],

            );
        }

        return $banner_image_data;
    }

    public function getTotalBanners() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "banner");

        return $query->row['total'];
    }
}

?>