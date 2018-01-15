<?php namespace Sunlight\Support\Test\Unit;

use InvalidArgumentException;

class ConformationTest extends \PHPUnit\Framework\TestCase
{
    // See Sample.php for the trait by Conformation

    public function testFailsOnMissingKeys()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Sample missing key(s): str, int, bool, float');

        $sample = Sample::fromArray([]);
    }

    /**
     * @dataProvider sampleArrayProvider
     */
    public function testCanCreate(array $source)
    {
        $sample = Sample::fromArray($source);

        $expected = array_merge([
            'optional1' => '',
            'optional2' => 2,
        ], $source);

        $this->assertEquals($expected, $sample->toArray());
    }

    public function sampleArrayProvider()
    {
        return [
            'all' => [['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 ,'optional1' => 'optional', 'optional2' => 5]],
            'only mandatory' => [['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 ]],
            'missing some' => [['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 , 'optional2' => 5]],
            'not in order' => [['optional2' => 5, 'bool' => false, 'int' => 1, 'float' => 2.0 , 'str' => 'example']],
        ];
    }

    public function testIgnoresSuperfluousData()
    {
        $data = [
            'str' => 'example',
            'int' => 1,
            'bool' => false,
            'float' => 2.0,
        ];

        $sample = Sample::fromArray(array_merge($data, [
            'superfluous' => 'data',
        ]));

        $expected = array_merge([
            'optional1' => '',
            'optional2' => 2,
        ], $data);

        $this->assertEquals($expected, $sample->toArray());
    }
}