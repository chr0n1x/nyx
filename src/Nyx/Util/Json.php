<?php

namespace Nyx\Util;

use Nyx\Util;

class Json extends Util {

  public function jsonToArray( $json ) {

    return $this->_stdClassToArray( json_decode( $json ) );

  } // jsonToArray

  /**
   * @param   stdClass|array  JSON blob
   * @return  array
   */
  protected function _stdClassToArray( $obj ) {

    $ret = (array)$obj;

    foreach ( $ret as $key => $val ) {
      if ( is_object( $val ) || is_array( $val ) ) {
        $ret[ $key ] = $this->_stdClassToArray( $val );
      }
    }

    return $ret;

  } // _stdClassToArray

} // Json
