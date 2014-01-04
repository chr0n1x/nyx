<?php

namespace Nyx\Util;

use Httpful;
use Nyx\Util;

class Http extends Util {

  const HTTP  = 'http';
  const HTTPS = 'https';

  private $_methods;

  public function __construct( array $config = array() ) {

    parent::__construct( $config );

    $methods = array_merge( Httpful\Http::safeMethods(), Httpful\Http::idempotentMethods() );
    $methods = array_map( 'strtolower', $methods );

    $this->_methods = $methods;

  } // __construct

  public function isValidHttpMethod( $method ) {

    return ( in_array( $method, $this->_methods ) );

  } // isValidHttpMethod

  public function removeProtocol( $url ) {

    return preg_replace( '/^[(http)[s]*]*(:\/\/)/', '', $url );

  } // sanitizeUrlProtocol

  public function appendProtocol( $url, $secure = true ) {

    $proto = ( $secure )
             ? self::HTTPS
             : self::HTTP;

    return $proto . '://' . $this->removeProtocol( $url );

  } // appendProtocol

} // Http
