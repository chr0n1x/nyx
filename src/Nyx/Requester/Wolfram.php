<?php

namespace Nyx\Requester;

use Nyx\Requester;
use TheSeer\fDOM\fDOMDocument;

class Wolfram extends Requester {

  const ERROR_MESSAGE = 'Wolfram Alpha returned an empty answer';
  const QUERY_EP      = '/v2/query';

  protected $_protocol = 'http';
  protected $_endpoints = array(
      self::QUERY_EP
  );
  protected $_baseUrl = 'api.wolframalpha.com';

  /**
   * @array
   */
  protected $_queryParams = array(
      'appid'     => null,
      'format'    => 'plaintext',
      'podtitle'  => 'Result',
      'input'     => ''
  );

  public function ask( $query ) {

    $this->_queryParams['input'] = $query;

    $url = $this->_buildRequestUrl( self::QUERY_EP );
    $res = $this->get( $url );
    $res = (string)$res;

    return ( empty( $res ) )
           ? self::ERROR_MESSAGE
           : $this->_getPodPlaintext( $res );

  } // ask

  protected function _getPodPlaintext( $xml ) {

    $dom = new fDomDocument;

    try {
      $dom->loadXml( $xml );
    }
    catch ( \Exception $e ) {
      return self::ERROR_MESSAGE;
    }

    $nodes = $dom->getElementsByTagName( 'plaintext' );

    if ( count( $nodes ) <= 0 ) {
      return self::ERROR_MESSAGE;
    }

    // I hate XML
    // just because the Node element list is a Traversable
    foreach ( $nodes as $node ) {
      return $node->nodeValue;
    }

  } // _getPodPlaintext

} // Nyx\Requester\Wolfram
