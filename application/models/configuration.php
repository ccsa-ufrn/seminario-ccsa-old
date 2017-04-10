<?php

class Configuration extends CI_Model {

    var $name = '';
    var $nickname = '';
    var $value = '';
    var $type = '';

    function __construct() {
        parent::__construct();
    }

    public function setConfig($name, $nickname, $value, $type) {
        $this->name = $name;
        $this->nickname = $nickname;
        $this->value = $value;
        $this->type = $type;
    }

}
