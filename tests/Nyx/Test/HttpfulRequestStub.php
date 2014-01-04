<?php

namespace Nyx\Test;

use Httpful\Request;

class HttpfulRequestStub extends Request {

  protected $_ret;

  public function __construct() {}

  public function setReturn( $ret ) {
    $this->_ret = $ret;
  }

  public function send() {
    return $this->_ret;
  }

} // HttpfulRequestStub
