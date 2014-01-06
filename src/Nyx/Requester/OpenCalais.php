<?php

namespace Nyx\Requester;

use Httpful\Mime;
use Nyx\Requester;
use Nyx\Util\Json;

class OpenCalais extends Requester {

  protected $_json_util;
  protected $_ssl       = false;
  protected $_baseUrl   = 'api.opencalais.com';
  protected $_endpoints = array(
      '/tag/rs/enrich',
      '/enlighten/rest'
  );

  /**
   * @array
   */
  protected $_queryParams = array(
      'Content-Type'       => Mime::HTML,
      'Accept'             => 'Application/JSON',
      'x-calais-licenseID' => null,
  );

  public function __construct( $overrides = array(), $cfg = array() ) {

    parent::__construct( $overrides, $cfg );

    $this->_json_util = new Json( $cfg );

  } // __construct

  /**
   * @param   string  $text
   * @return  array
   */
  public function enrich( $text ) {

    if ( empty( $text ) ) {
      return array();
    }

    $headers = [];

    foreach ( $this->_queryParams as $key => $val ) {
      $headers[] = "{$key}: {$val}";
    }

    $uri  = $this->_buildEndpointUrl( '/tag/rs/enrich' );
    $res  = $this->_postRequest( $uri, $text, $headers );
    $json = json_decode( (string)$res );

    return ( empty( $json ) )
           ? array()
           : $this->_json_util->jsonToArray( (string)$res );

  } // enrich

  /**
   * @todo: figure out why this request does not work with Httpful, even though
   *        the headers & CURLOPT_*s are the same...
   * @param string $uri
   * @param string $content
   * @param array  $headers
   * @return string
   */
  protected function _postRequest( $uri, $content, array $headers = array() ) {

    // for some reason Httpful just does not work well with
    // this particular request...
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $uri );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $content );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;

  } // _postRequest

} // Nyx\Requester\Wolfram
