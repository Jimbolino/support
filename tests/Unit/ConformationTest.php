<?php namespace MattyRad\Support\Test\Unit;

use InvalidArgumentException;

class ConformationTest extends \PHPUnit\Framework\TestCase
{
    // See Sample.php for the trait by Conformation

    public function testFailsOnMissingKeys()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Sample missing key(s): array, str, int, bool, float');

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
            'optional2' => null,
        ], $source);

        $this->assertEquals($expected, $sample->toArray());
    }

    public function sampleArrayProvider()
    {
        return [
            'all' => [[
                'array' => [1,2,3],
                'str' => 'example',
                'int' => 1,
                'bool' => false,
                'float' => 2.0,
                'optional1' => 'optional',
                'optional2' => null,
            ]],
            'only mandatory' => [[
                'array' => [1,2,3],
                'str' => 'example',
                'int' => 1,
                'bool' => false,
                'float' => 2.0,
            ]],
            'missing an optional' => [[
                'array' => [1,2,3],
                'str' => 'example',
                'int' => 1,
                'bool' => false,
                'float' => 2.0,
                'optional2' => null,
            ]],
            'not in order' => [[
                'optional2' => null,
                'bool' => false,
                'int' => 1,
                'float' => 2.0 ,
                'str' => 'example',
                'array' => [1,2,3],
            ]],
            'nullable but required types like ?float $float' => [[
                'array' => [1,2,3],
                'str' => 'example',
                'int' => 1,
                'bool' => false,
                'float' => null,
            ]],
        ];
    }

    public function testIgnoresSuperfluousData()
    {
        $data = [
            'array' => [1,2,3],
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
            'optional2' => null,
        ], $data);

        $this->assertEquals($expected, $sample->toArray());
    }

    /**
     * @dataProvider typeProvider
     */
    public function testValidatedTypes(array $sample_data, string $error_message)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($error_message);

        Sample::fromArray($sample_data);
    }

    public function typeProvider()
    {
        return [
            'incorrect primitive types see errors' => [[
                'array' => [1,2,3],
                'str' => 1,
                'int' => 1,
                'bool' => 1,
                'float' => 1,
            ], 'Param "str" expected type string but got type int with value \'1\''],
            'incorrect object types see errors' => [[
                'array' => [1,2,3],
                'str' => 'example',
                'int' => 1,
                'bool' => false,
                'float' => 1.0,
                'optional2' => 100,
            ], 'Param "optional2" expected type stdClass but got type int with value \'100\''],
            'incorrect object types see errors (object message)' => [[
                'array' => [1,2,3],
                'str' => new \stdClass,
                'int' => 1,
                'bool' => false,
                'float' => 1.0,
            ], 'Param "str" expected type string but got type stdClass'],
        ];
    }
}
