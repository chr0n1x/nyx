<?php

namespace Nyx;

use Httpful\Request;
use Nyx\Util\Http;

class Factory {

  protected $_util,
            $_config;

  public function __construct( array $config = array(), Http $util = null ) {

    $this->_config = $config;
    $this->_util   = ( empty( $util ) )
                     ? new Util( $config )
                     : $util;

  } // __contruct

  public function request( $method, array $arguments ) {

    if ( !$this->_util->isValidHttpMethod( $method ) ) {
      throw new \BadMethodCallException( "Unknown method [{$method}]" );
    }

    return call_user_func_array( array( 'Httpful\Request', $method ), $arguments );

  } // request

} // Factory
