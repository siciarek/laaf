<?php
/**
 * Sample service class
 */
class User
{
    private $data = array();

    public function __construct() {
        $this->data[] = array("id" => 45, "first_name" => "John", "last_name" => "Doe");
        $this->data[] = array("id" => 54, "first_name" => "Luie", "last_name" => "Corn");
        $this->data[] = array("id" => 71, "first_name" => "Matilda", "last_name" => "Mello");
    }

    public function getList() {
        return $this->data;
    }

    public function getUser($value) {
        $id = intval($value);

        if($id > 0) {
            foreach($this->data as $row) {
                if($row["id"] === $id) {
                    return $row;
                }
            }
        }

        return array();
    }
}
