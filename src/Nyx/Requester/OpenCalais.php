<?php

namespace Nyx\Requester;

use Httpful\Mime;
use Nyx\Requester;
use Nyx\Util\Json;

class OpenCalais extends Requester {

  protected $_json_util;
  protected $_secure    = false;
  protected $_endpoints = array(
      '/tag/rs/enrich'
  );
  protected $_baseUrl = 'api.opencalais.com';

  /**
   * @array
   */
  protected $_queryParams = array(
      'licenseID' => null,
      'content'   => '',
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

    $this->_queryParams['content'] = $text;

    $url = $this->_buildEndpointUrl( '/tag/rs/enrich' );
    $res = $this->post( $url, $this->_queryParams, Mime::FORM )
                ->addHeaders( [ 'Accept' => Mime::JSON ] )
                ->send();

    $json = json_decode( (string)$res );

    return ( empty( $json ) )
           ? array()
           : $this->_json_util->jsonToArray( (string)$res );

  } // enrich

} // Nyx\Requester\Wolfram
