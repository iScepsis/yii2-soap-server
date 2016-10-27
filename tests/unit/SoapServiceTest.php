<?php

namespace subdee\soapserver\tests;

use Codeception\TestCase\Test;
use subdee\soapserver\tests\unit\SoapController;
use subdee\soapserver\SoapService;

class SoapServiceTest extends Test
{
    public function testGenerateWsdl()
    {
		$controller = new SoapController();
		$soapService = new SoapService($controller, 'http://wsdl-url/', 'http://test-url/');
		$wsdl = $soapService->generateWsdl();

		$xml = simplexml_load_string($wsdl);
		$this->assertInstanceOf(\SimpleXMLElement::class, $xml);
		$this->assertSame((string) $xml->getName(), 'definitions');

		$operation = $xml->xpath('//wsdl:operation[@name="getHello"]');
		$this->assertInstanceOf(\SimpleXMLElement::class, $operation[0]);

		$address = $xml->xpath('//soap:address');
		$location = (string) $address[0]->attributes()->location;
		$this->assertEquals('http://test-url/', $location);
    }

}
