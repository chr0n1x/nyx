<?php

namespace Nyx;

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
      throw new \Exception( "Unknown method [{$fx}]" );
    }

    $request = call_user_func_array( array( 'Httpful\Request', $method ), $arguments );

    if ( $request instanceof Request ) {
      return $request;
    }

    throw new \Exception( 'Failed to create response; params: ' . var_export( $arguments, true ) );

  } // request

} // Factory
