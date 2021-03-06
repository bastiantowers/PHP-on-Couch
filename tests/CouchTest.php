<?php

namespace PHPOnCouch;

use InvalidArgumentException,
	PHPOnCouch\Exceptions,
	PHPUnit_Framework_TestCase,
	stdClass;

require_once join(DIRECTORY_SEPARATOR, [__DIR__, '_config', 'config.php']);

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-01 at 01:49:47.
 */
class CouchTest extends PHPUnit_Framework_TestCase
{

	private $host = 'localhost';
	private $port = '5984';

	/**
	 *
	 * @var PHPOnCouch\Couch
	 */
	private $couch;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$config = \config::getInstance();
		$this->aUrl = $config->getUrl($this->host, $this->port, $config->getFirstAdmin());
		$this->couch_server = 'http://' . $this->host . ':' . $this->port . '/';

		$this->couch = new Couch($this->aUrl);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		$this->couch = null;
	}

	/**
	 * @covers PHPOnCouch\Couch::getAdapter()
	 * @covers PHPOnCouch\Couch::setAdapter()
	 */
	public function testAdapterGetterSetter()
	{
		$adapter = $this->couch->getAdapter();

		//Should be Curl by default
		$this->assertEquals("PHPOnCouch\Adapter\CouchHttpAdapterCurl", get_class($adapter));

		$socketAdapter = new Adapter\CouchHttpAdapterSocket("http://localhost:5984", []);
		$this->couch->setAdapter($socketAdapter);
		$this->assertEquals("PHPOnCouch\Adapter\CouchHttpAdapterSocket", get_class($this->couch->getAdapter()));
	}

	/**
	 * @covers PHPOnCouch\Couch::initAdapter()
	 */
	public function testInitAdapter()
	{
		//By default, it should be curl
		$newCouch1 = new Couch("randomdsn");
		$this->assertEquals("PHPOnCouch\Adapter\CouchHttpAdapterCurl", get_class($newCouch1->getAdapter()));

		$opts = ['test' => 'optionIsSet'];
		$newCouch1->initAdapter($opts);
		$this->assertEquals($newCouch1->getAdapter()->getOptions(), $opts);
	}

	/**
	 * @covers PHPOnCouch\Couch::dsn()
	 */
	public function testDsn()
	{
		$dsn = "dsnTest";
		$couch1 = new Couch($dsn);
		$this->assertEquals($dsn, $couch1->dsn());
	}

	/**
	 * @covers PHPOnCouch\Couch::options()
	 */
	public function testOptions()
	{
		$opts = ['param' => 'value'];
		$couch = new Couch("dsnTest", $opts);
		$this->assertEquals($opts, $couch->options());
	}

	public function testSessionAccessors()
	{
		$session = "Y291Y2g6NENGNDgzNz ";
		$this->couch->setSessionCookie($session);
		$this->assertEquals($session, $this->couch->getSessionCookie());
	}
}
