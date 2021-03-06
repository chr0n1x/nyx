<?php

namespace Nyx\Test\Requester;

use Nyx\Requester\OpenCalais;
use Nyx\Test\HttpfulRequestStub;

class OpenCalaisTest extends \PHPUnit_Framework_TestCase {

  /**
   * @test
   */
  public function emptyReturnsEmpty() {

    $val = ( new OpenCalais )->enrich( '' );
    $this->assertEmpty( $val );
    $this->assertInternalType( 'array', $val );

  } // emptyReturnsEmpty

  /**
   * @test
   */
  public function invalidJsonResponseReturnsArray() {

    $request_stub = new HttpfulRequestStub;
    $request_stub->setReturn( 'invalid json' );

    $factory_mock = $this->getMock( 'Nyx\Factory' );
    $factory_mock->expects( $this->any() )
                 ->method( 'request' )
                 ->will( $this->returnValue( $request_stub ) );

    $instance = new OpenCalais;
    $instance->setFactory( $factory_mock );

    $val = $instance->enrich( 'this may or may not be a wall of text' );
    $this->assertEmpty( $val );
    $this->assertInternalType( 'array', $val );

  } // responseWellFormatted

  protected function _assertArrayNestedOrScalar( array $arr ) {

    foreach ( $arr as $val ) {

      if ( is_array( $val ) ) {
        $this->_assertArrayNestedOrScalar( $val );
      }
      else if ( is_scalar( $val ) ) {
        continue;
      }
      else {
        $this->fail();
      }

    } // foreach val

  } // _assertArrayNestedOrScalar

} // OpenCalaisTest
