<?php

require_once 'Zend/Db/Adapter/Mysqli.php';

class ZendExtended_Db extends Zend_Db_Adapter_Mysqli {

    /**
     * $table ile belirtilen tabloya $bind ifadesi ile verilen alanlarla yeni kayıt ekler
     * 
     * @param mixed         $table yeni kayıt eklenecek  tablo
     * @param array         $bind yani kaydın alanları
     * @return int          En son eklenen kayıt idsi
     * @throws Zend_Db_Adapter_Exception
     */
    public function insert($table, array $bind) {

        $result = parent::insert($table, $bind);
        $last_insert_id = $this->lastInsertId();

        $islemler = array(
            "yapilan_islem" => YT_INSERT,
            "tablo_adi" => $table,
            "tablo_id" => $last_insert_id,
            "yeni_veriler" => serialize($bind),
            "eski_veriler" => NULL
        );

        log::islem_kaydi($islemler);

        return $last_insert_id;
    }

    /**
     * $table ile belirtilen tabloda ,
     * $where ifadesi ile belirlenen satırlarda , $bind ifadesi ile verilen alanları günceller
     *
     * @param  mixed        $table güncellenek tablo
     * @param  array        $bind  güncellenecek alanlar
     * @param  mixed        $where Güncelleme işlemine ait WHERE bilgisi
     * @return int          Etkilenen satır sayısı
     * @throws Zend_Db_Adapter_Exception
     */
    public function update($table, array $bind, $where = '') {

        //güncellenecek satırlardaki eski veriyi al
        $oldDataArray = $this->get_old_data($table, $where);


        if (!empty($oldDataArray)) {

            $oldData = array();
            $newData = array();
            $indexData = array();

            switch (count($oldDataArray) > 1) {
                case 1:
                    foreach ($oldDataArray as $value) {
                        foreach ($bind as $key => $val) {
                            if ($val != $value[$key]) {
                                $oldData[][$key] = $value[$key];
                                $newData[$key] = $val;
                                $indexData[] = $value[array_keys($value)[0]];
                            }
                        }
                    }

                    break;

                default:
                    foreach ($bind as $key => $value) {
                        if ($value != $oldDataArray[0][$key]) {
                            $oldData[$key] = $oldDataArray[0][$key];
                            $newData[$key] = $value;
                        }
                    }

                    $keys = array_keys($oldDataArray[0]);
                    $indexData[] = $oldDataArray[0][$keys[0]];
                    break;
            }
        }


        //fonksiyondan dönecek değer  Hiçbir veri değişikliği yoksa , güncelleme yapılmayacak 0 değeri dönderilecek
        $queryResult = 0;

        // Güncellenecek veri varsa 
        if (!empty($newData)) {

            // Güncelleme işlemini yap
            $queryResult = parent::update($table, $newData, $where);


            // Güncelleme işlemini loga ekle
            $islemler = array(
                "yapilan_islem" => YT_UPDATE,
                "tablo_adi" => $table,
                "tablo_id" => serialize($indexData),
                "yeni_veriler" => serialize($newData),
                "eski_veriler" => serialize($oldData)
            );

            log::islem_kaydi($islemler);
        }

        return $queryResult;
    }

    /**
     * $table ile belirtilen tablodan $where ifadesi ile belirlenen satırları siler
     *
     * @param  mixed        $table Satırın silineceği tablonun adı
     * @param  mixed        $where Silme işlemine ait WHERE bilgisi
     * @return int          Etkilenen satır sayısı
     */
    public function delete($table, $where = '') {

        $result = parent::delete($table, $where);

        $islemler = array(
            "yapilan_islem" => YT_DELETE,
            "tablo_adi" => $table,
            "tablo_id" => serialize($where),
            "yeni_veriler" => NULL,
            "eski_veriler" => NULL
        );

        log::islem_kaydi($islemler);

        return $result;
    }

    /**
     * $table ile belirtilen tablodan $where ifadesi ile belirlenen satırları dizi şeklinde çeker
     *
     * @param  mixed        $table Verinin çekileceği tablonun adı
     * @param  mixed        $where Sorgu işlemine ait WHERE bilgisi
     * @return array        Sorgu sonucunda elde edilen veri
     */
    private function get_old_data($table, $where) {

        $oldDataSQL = "SELECT * FROM  $table WHERE " . $this->_whereExpr($where);

        try {
            $rows = $this->query($oldDataSQL);
            $rows->setFetchMode(Zend_Db::FETCH_ASSOC);
            $oldDataArray = $rows->fetchAll();
        } catch (Exception $ex) {
            throw $ex;
        }

        return $oldDataArray;
    }

}
