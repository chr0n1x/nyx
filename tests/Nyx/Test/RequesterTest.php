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
  public function requestUrlWorks() {

    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'blah.com' ) );
    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'http://blah.com' ) );
    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'https://blah.com' ) );
    $this->assertEquals( 'https://blah.com', $this->_instance->setRequestUrl( 'httpshttp://blah.com' ) );

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

  /**
   * Create mock of requester
   */
  protected function _createMock() {

  } // createMock

} // RequesterTest
