<?php
class LinkTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group distro
     * @testdox Link class file exists
     */
    public function testLinkClassFileExists()
    {
        $this->assertFileExists(STORECORE_FILESYSTEM_LIBRARY_ROOT_DIR . 'Types' . DIRECTORY_SEPARATOR .  'Link.php');
    }

    /**
     * @group distro
     * @testdox Implemented \Psr\Link\LinkInterface file exists
     */
    public function testImplementedPsrLinkLinkInterfaceFileExists()
    {
        $this->assertFileExists(STORECORE_FILESYSTEM_LIBRARY_ROOT_DIR . 'Psr' . DIRECTORY_SEPARATOR . 'Link' . DIRECTORY_SEPARATOR .  'LinkInterface.php');
    }

    /**
     * @group hmvc
     * @testdox Link implements \Psr\Link\LinkInterface
     */
    public function testsLinkImplementsPsrLinkLinkInterface()
    {
        $link = new \StoreCore\Types\Link();
        $this->assertInstanceOf(\Psr\Link\LinkInterface::class, $link);
    }


    /**
     * @testdox Link::__construct() exists
     */
    public function testLinkConstructExists()
    {
        $class = new \ReflectionClass('\StoreCore\Types\Link');
        $this->assertTrue($class->hasMethod('__construct'));
    }

    /**
     * @testdox Link::__construct() is public
     */
    public function testLinkConstructIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', '__construct');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Link::__construct() has three optional parameters
     */
    public function testLinkConstructHasThreeOptionalParameters()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', '__construct');
        $this->assertTrue($method->getNumberOfParameters() === 3);
        $this->assertTrue($method->getNumberOfRequiredParameters() === 0);
    }


    /**
     * @testdox Link::__set() exists
     */
    public function testLinkOverloadingSetExists()
    {
        $class = new \ReflectionClass('\StoreCore\Types\Link');
        $this->assertTrue($class->hasMethod('__set'));
    }

    /**
     * @testdox Link::__set() is public
     */
    public function testLinkOverloadingSetIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', '__set');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Link::__set() has two required parameters
     */
    public function testLinkOverloadingSetHasTwoRequiredParameters()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', '__set');
        $this->assertTrue($method->getNumberOfRequiredParameters() === 2);
    }


    /**
     * @testdox Link::getAttributes() exists
     */
    public function testLinkGetAttributesExists()
    {
        $class = new \ReflectionClass('\StoreCore\Types\Link');
        $this->assertTrue($class->hasMethod('getAttributes'));
    }

    /**
     * @testdox Link::getAttributes() is public
     */
    public function testLinkGetAttributesIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', 'getAttributes');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Link::getAttributes() has no parameters
     */
    public function testLinkGetAttributesHasNoParameters()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', 'getAttributes');
        $this->assertTrue($method->getNumberOfParameters() === 0);
    }

    /**
     * @testdox Link::getAttributes() returns empty array by default
     */
    public function testLinkGetAttributesReturnsEmptyArrayByDefault()
    {
        $link = new \StoreCore\Types\Link();
        $this->assertEmpty($link->getAttributes());
        $this->assertInternalType('array', $link->getAttributes());
    }


    /**
     * @testdox Link::getHref() exists
     */
    public function testLinkGetHrefExists()
    {
        $class = new \ReflectionClass('\StoreCore\Types\Link');
        $this->assertTrue($class->hasMethod('getHref'));
    }

    /**
     * @testdox Link::getHref() is public
     */
    public function testLinkGetHrefIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', 'getHref');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Link::getHref() has no parameters
     */
    public function testLinkGetHrefHasNoParameters()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', 'getHref');
        $this->assertTrue($method->getNumberOfParameters() === 0);
    }


    /**
     * @testdox Link::set() exists
     */
    public function testLinkSetExists()
    {
        $class = new \ReflectionClass('\StoreCore\Types\Link');
        $this->assertTrue($class->hasMethod('set'));
    }

    /**
     * @testdox Link::set() is public
     */
    public function testLinkSetIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', 'set');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Link::set() has two required parameters
     */
    public function testLinkSetHasTwoRequiredParameters()
    {
        $method = new \ReflectionMethod('\StoreCore\Types\Link', 'set');
        $this->assertTrue($method->getNumberOfRequiredParameters() === 2);
    }
}
