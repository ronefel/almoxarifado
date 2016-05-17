<?php
header ('Content-type: text/html; charset=UTF-8',true);
Abstract class ExtensionBridge {

    // array contem as classes-extensões
    private $_exts = array();

    public function addExt($object) {
        $this->_exts[] = $object;
    }

    public function __get($varname) {
        foreach ($this->_exts as $ext) {
            if (property_exists($ext, $varname)) {
                return $ext->$varname;
            }
        }
    }

    public function __call($method, $args) {
        foreach ($this->_exts as $ext) {
            if (method_exists($ext, $method)) {
                return call_user_func_array(array($ext, $method), $args);
            }
        }
        throw new Exception("Este Metodo {$method} nao existe!");
    }

}
