<?php
class ImageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group distro
     */
    public function testImageClassFileExists()
    {
        $this->assertFileExists(
            STORECORE_FILESYSTEM_LIBRARY_ROOT_DIR . 'Image.php'
        );
    }

    /**
     * @group hmvc
     * @testdox Class implements \StoreCore\Types\StringableInterface
     */
    public function testClassImplementsStoreCoreTypesStringableInterface()
    {
        $image = new \StoreCore\Image();
        $this->assertTrue($image instanceof \StoreCore\Types\StringableInterface);
    }

    /**
     * @group distro
     */
    public function testVersionConstantIsDefined()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasConstant('VERSION'));
    }

    /**
     * @testdox Public __toString() method exists
     */
    public function testPublicToStringMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('__toString'));
    }

    /**
     * @depends testPublicToStringMethodExists
     * @testdox Public __toString() method is public
     */
    public function testPublicToStringMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', '__toString');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @depends testPublicToStringMethodExists
     * @testdox Public __toString() method returns non-empty string
     */
    public function testPublicToStringMethodReturnsNonEmptyString()
    {
        $image = new \StoreCore\Image();
        $this->assertFalse(empty($image->__toString()));
        $this->assertFalse(empty((string)$image));
        $this->assertTrue(is_string($image->__toString()));
    }

    /**
     * @depends testPublicToStringMethodReturnsNonEmptyString
     * @testdox Public __toString() method returns <img> tag
     */
    public function testPublicToStringMethodReturnsImgTag()
    {
        $image = new \StoreCore\Image();
        $this->assertStringStartsWith('<img ', (string)$image);
        $this->assertEmpty(strip_tags((string)$image));
    }

    /**
     * @testdox Public getAlt() method exists
     */
    public function testPublicGetAltMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('getAlt'));
    }

    /**
     * @testdox Public getAlt() method is public
     */
    public function testPublicGetAltMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', 'getAlt');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Public getAlt() method returns empty string by default
     */
    public function testPublicGetAltMethodReturnsEmptyStringByDefault()
    {
        $image = new \StoreCore\Image();
        $this->assertTrue(empty($image->getAlt()));
        $this->assertTrue(is_string($image->getAlt()));
    }

    /**
     * @testdox Public getHeight() method exists
     */
    public function testPublicGetHeightMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('getHeight'));
    }

    /**
     * @testdox Public getHeight() method is public
     */
    public function testPublicGetHeightMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', 'getHeight');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Public getWidth() method exists
     */
    public function testPublicGetWidthMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('getWidth'));
    }

    /**
     * @testdox Public getWidth() method is public
     */
    public function testPublicGetWidthMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', 'getWidth');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Public setAlt() method exists
     */
    public function testPublicSetAltMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('setAlt'));
    }

    /**
     * @testdox Public setAlt() method is public
     */
    public function testPublicSetAltMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', 'setAlt');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Public setHeight() method exists
     */
    public function testPublicSetHeightMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('setHeight'));
    }

    /**
     * @testdox Public setHeight() method is public
     */
    public function testPublicSetHeightMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', 'setHeight');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @expectedException \DomainException
     * @testdox Public setHeight() method throws \DomainException on 0 (zero)
     */
    public function testPublicSetHeightMethodThrowsDomainExceptionOnZero()
    {
        $image = new \StoreCore\Image();
        $image->setHeight(0);
    }

    /**
     * @testdox Public setSource() method exists
     */
    public function testPublicSetSourceMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('setSource'));
    }

    /**
     * @testdox Public setSource() method is public
     */
    public function testPublicSetSourceMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', 'setSource');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @testdox Public setWidth() method exists
     */
    public function testPublicSetWidthMethodExists()
    {
        $class = new \ReflectionClass('\StoreCore\Image');
        $this->assertTrue($class->hasMethod('setWidth'));
    }

    /**
     * @testdox Public setWidth() method is public
     */
    public function testPublicSetWidthMethodIsPublic()
    {
        $method = new \ReflectionMethod('\StoreCore\Image', 'setWidth');
        $this->assertTrue($method->isPublic());
    }

    /**
     * @expectedException \DomainException
     * @testdox Public setWidth() method throws \DomainException on 0 (zero)
     */
    public function testPublicSetWidthMethodThrowsDomainExceptionOnZero()
    {
        $image = new \StoreCore\Image();
        $image->setWidth(0);
    }
}
