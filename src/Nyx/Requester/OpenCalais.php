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
      'x-calais-licenseID' => 'wcp9vj5gvzyxwfa98e4kvb3j',
      'Content-Type'       => Mime::HTML,
      'Accept'             => 'Application/JSON',
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

    $this->_queryParams['content'] = trim( $text );

    $url = $this->_buildEndpointUrl( '/tag/rs/enrich' );
    $res = $this->post( $url, $text, Mime::HTML )
                ->addHeaders( $this->_queryParams )
                ->send();

    $json = json_decode( (string)$res );

    return ( empty( $json ) )
           ? array()
           : $this->_json_util->jsonToArray( (string)$res );

  } // enrich

} // Nyx\Requester\Wolfram
