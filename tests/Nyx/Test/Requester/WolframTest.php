<?php

namespace Nyx\Test\Requester;

use Nyx\Test\HttpfulRequestStub;
use Nyx\Requester\Wolfram;

class WolframTest extends \PHPUnit_Framework_TestCase {

  protected $_content = 'blahblahthisistest';

  /**
   * @test
   */
  public function emptyResponseReturnsApology() {

    $factory_mock = $this->_getFactoryRequestMock();

    $instance = new Wolfram;
    $instance->setFactory( $factory_mock );

    $this->assertEquals( Wolfram::ERROR_MESSAGE, $instance->ask( 'does not matter' ) );

  } // emptyResponseReturnsApology

  /**
   * @test
   */
  public function validXmlReturnsContent() {

    $factory_mock = $this->_getFactoryRequestMock( "<plaintext>{$this->_content}</plaintext>" );

    $instance = new Wolfram;
    $instance->setFactory( $factory_mock );

    $this->assertEquals( $this->_content, $instance->ask( 'does not matter' ) );

  } // validXmlReturnsContent

  /**
   * @todo: this will have to be removed when smarter response parsing is implemented
   * @test
   */
  public function noPlaintextReturnsError() {

    $factory_mock = $this->_getFactoryRequestMock( $this->_content );

    $instance = new Wolfram;
    $instance->setFactory( $factory_mock );

    $this->assertEquals( Wolfram::ERROR_MESSAGE, $instance->ask( 'does not matter' ) );

  } // noPlaintextReturnsError 

  /**
   * Only create a factory mock here - hopefully this can be used somewhere else
   * @param   mixed         $ret  Anything that should be returned by the mocked Request object
   * @return  Nyx\Factory
   */
  protected function _getFactoryRequestMock( $ret = '' ) {

    $request_stub = new HttpfulRequestStub;
    $request_stub->setReturn( $ret );

    $factory_mock = $this->getMock( 'Nyx\Factory' );
    $factory_mock->expects( $this->any() )
                 ->method( 'request' )
                 ->will( $this->returnValue( $request_stub ) );

    return $factory_mock;

  } // _getFactoryRequestMock

} // WolframTest
