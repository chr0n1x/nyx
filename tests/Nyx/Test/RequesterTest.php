<?php

namespace Nyx\Test;

use Nyx\Requester;

class RequesterTest extends \PHPUnit_Framework_TestCase {

  protected $_instance;

  protected function setUp() {

    parent::setUp();

    $this->_instance = new Requester;

  } // setUp

  /**
   * @test
   * @expectedException \Exception
   */
  public function baseObjectNoWork() {

    $randomFx = sha1( time() );

    $this->_instance->{$randomFx}();

  } // unknownHttpMethodPoosItself

  /**
   * @test
   */
  public function sslSwitch() {

    $this->assertEquals( 'http', $this->_instance->setSsl( false ) );
    $this->assertEquals( 'https', $this->_instance->setSsl( true ) );

  } // sslSwitch

  /**
   * @test
   * @depends sslSwitch
   */
  public function requestUrlWorks() {

    $this->_instance->setSsl( true );

    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'blah.com' ) );
    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'http://blah.com' ) );
    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'https://blah.com' ) );
    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'httpshttp://blah.com' ) );

    $this->_instance->setSsl( false );

    $this->assertEquals( 'http://blah.com', $this->_instance->setRequestUrl( 'blah.com' ) );
    $this->assertEquals( 'http://blah.com', $this->_instance->setRequestUrl( 'http://blah.com' ) );
    $this->assertEquals( 'http://blah.com', $this->_instance->setRequestUrl( 'https://blah.com' ) );
    $this->assertEquals( 'http://blah.com', $this->_instance->setRequestUrl( 'httpshttp://blah.com' ) );


  } // requestUrlWorks

  /**
   * @test
   * @depends requestUrlWorks
   * @expectedException \Exception
   */
  public function unknownHTTPMethod() {

    $this->_instance->setRequestUrl( 'this.is.a.test.lan' );

    $randomFx = sha1( time() );

    $this->_instance->__call( $randomFx, array() );

  } // unknownHttpMethodPoosItself

} // RequesterTest
