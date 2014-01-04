<?php

namespace Nyx;

use Nyx\Factory;
use Nyx\Util\Http;

class Requester {

  /**
   * @string
   */
  protected $_protocol = 'https';

  /**
   * @array
   * known endpoints for the extending class.
   * must have a '/' preceding it
   */
  protected $_endpoints   = array();

  /**
   * @array
   * Stores params that are used to build the query stream
   * Order matters!
   */
  protected $_queryParams = array();

  /**
   * @string
   * set once in constructor and that's it
   */
  protected $_baseUrl;

  /**
   * @string
   * Convenience instance var for building out endpoint URLs
   */
  protected $_url;

  /**
   * @Nyx\Util\Http
   */
  protected $_http_util;

  /**
   * @Nyx\Factory
   */
  protected $_factory;

  /**
   * @param  array       $cfg        Anything special that needs to be merged
   *                                 into $this->_queryParams
   * @throws \Exception              When the endpoint url is invalid
   */
  public function __construct( $overrides = array(), $cfg = array() ) {

    $this->_queryParams = array_merge( $this->_queryParams, $overrides );
    $this->_http_util   = new Http( $cfg );
    $this->_factory     = new Factory( $cfg, $this->_http_util );

  } // __construct

  /**
   * @param   string  $fx   HTTP method
   * @param   array   $args
   * @return  Httpful\Response
   * @throws  \Exception    When $fx is unknown
   */
  public function __call( $fx, $args ) {

    if ( empty( $this->_baseUrl ) ) {
      throw new \Exception( "Empty base URL" );
    }

    $this->setRequestUrl( $this->_baseUrl );

    $this->_factory->request( $fx, $args )
                   ->send();

  } // __call

  /**
   * Set base param for request URL
   * @param string  $url
   */
  public function setRequestUrl( $url ) {

    $url = $this->_http_util->removeProtocol( $url );

    $this->_baseUrl = $this->_protocol.'://'.$url;

    return $this->_baseUrl;

  } // setRequestUrl

  /**
   * Set protocol
   * @param bool  $secure
   */
  public function setSsl( $secure = true ) {

    $this->_protocol = ( $secure )
                       ? 'https'
                       : 'http';

    return $this->_protocol;

  } // setSsl

  /**
   * @param  string     $endpoint
   * @return string
   * @throws \Exception
   */
  protected function _buildEndpointUrl( $endpoint ) {

    if ( !in_array( $endpoint, $this->_endpoints ) ) {
      throw new \Exception( "Unknown endpoint {$endpoint}" );
    }

    // force reset of protocol
    $this->setRequestUrl( $this->_baseUrl );

    return $this->_baseUrl . $endpoint;

  } // _buildEndpointUrl

  /**
   * @param  string  $endpoint
   * @param  array   $params  Keys = variable names, Values = values
   * @return string
   */
  protected function _buildRequestUrl( $endpoint, array $params = [] ) {

    $params = ( empty( $params ) )
              ? $this->_queryParams
              : $params;

    return $this->_buildEndpointUrl( $endpoint ) . $this->_buildQueryStream( $params );

  } // _buildRequestUrl

  /**
   * @param  array $params  Keys = variable names, Values = values
   * @return string
   */
  public function _buildQueryStream( array $params ) {

    if ( empty( $params ) ) {
      return '';
    }

    $first = key( $params );
    $query = '?' . $first . '=' . current( $params );

    unset( $params[ $first ] );

    foreach( $params as $param => $val ) {
      $param  = urlencode( $param );
      $val    = urlencode( $val );
      $query .= "&{$param}={$val}";
    }

    return $query;

  } // _buildQueryStream

} // Nyx\Requester
