<?php namespace Sunlight\Support\Test\Unit;

class ConformationTest extends \PHPUnit\Framework\TestCase
{
    // See Sample.php for the trait by Conformation

    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    /*public function testTrait()
    {
    }

    public function testFailsOnMissingKeys()
    {
    }

    public function testFailsOnIncorrectTypes()
    {
    }*/

    /**
     * @dataProvider sampleArrayProvider
     */
    public function testCanCreate(array $source)
    {
        //shuffle($source);

        $sample = Sample::fromArray($source);

        $expected = array_merge([
            'optional1' => '',
            'optional2' => 2,
        ], $source);

        $this->assertEquals($expected, $sample->toArray());
    }

    public function testAsdf()
    {
        $d = ['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 ];

        $sample = Sample::fromArray($d);

        $this->assertEquals(array_merge($d, ['optional1' => '', 'optional2' => 2]), $sample->toArray());
    }

    public function sampleArrayProvider()
    {
        return [
            'all' => [['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 ,'optional1' => 'optional', 'optional2' => 5]],
            'only mandatory' => [['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 ]],
            'missing some' => [['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 , 'optional2' => 5]],
        ];
    }
}