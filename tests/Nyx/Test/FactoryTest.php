<?php

namespace Nyx\Test;

use Nyx\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase {

  /**
   * @test
   * @expectedException \BadMethodCallException
   */
  public function invalidHttpMethodThrows() {

    $http = $this->getMock( 'Nyx\Util\Http' );
    $http->expects( $this->any() )
         ->method( 'isValidHttpMethod' )
         ->will( $this->returnValue( false ) );

    ( new Factory( [], $http ) )->request( md5( time() ), [] );

  } // invalidHttpMethodThrows

  /**
   * @test
   */
  public function returnsRequiredRequests() {

    $http = $this->getMock( 'Nyx\Util\Http' );
    $http->expects( $this->any() )
         ->method( 'isValidHttpMethod' )
         ->will( $this->returnValue( true ) );

    $factory = new Factory( [], $http );

    $post = $factory->request( 'post', [ md5( time() ) ] );
    $this->assertInstanceOf( 'Httpful\Request', $post );

    $get = $factory->request( 'get', [ md5( time() ) ] );
    $this->assertInstanceOf( 'Httpful\Request', $get );

  } // invalidHttpMethodThrows


} // FactoryTest
