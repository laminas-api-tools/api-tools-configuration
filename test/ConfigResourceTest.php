<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Configuration;

use Laminas\ApiTools\Configuration\ConfigResource;
use Laminas\Config\Writer\PhpArray;
use PHPUnit\Framework\TestCase;
use stdClass;

use function array_intersect;
use function array_keys;
use function count;
use function file_exists;
use function file_put_contents;
use function gettype;
use function is_array;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

class ConfigResourceTest extends TestCase
{
    /** @var string */
    public $file;

    /** @var ConfigResource */
    protected $configResource;

    /** @var TestAsset\ConfigWriter */
    protected $writer;

    protected function setUp(): void
    {
        $this->removeScaffold();
        $this->file = tempnam(sys_get_temp_dir(), 'laminasconfig');
        file_put_contents($this->file, '<' . "?php\nreturn array();");

        $this->writer         = new TestAsset\ConfigWriter();
        $this->configResource = new ConfigResource([], $this->file, $this->writer);
    }

    protected function tearDown(): void
    {
        $this->removeScaffold();
    }

    public function removeScaffold(): void
    {
        if ($this->file && file_exists($this->file)) {
            unlink($this->file);
        }
    }

    /**
     * @param string|array $array1
     * @param string|array $array2
     * @return false|string|array
     */
    public function arrayIntersectAssocRecursive($array1, $array2)
    {
        if (! is_array($array1) || ! is_array($array2)) {
            if ($array1 === $array2) {
                return $array1;
            }
            return false;
        }

        $commonKeys = array_intersect(array_keys($array1), array_keys($array2));
        $return     = [];
        foreach ($commonKeys as $key) {
            /** @psalm-suppress MixedArgument */
            $value = $this->arrayIntersectAssocRecursive($array1[$key], $array2[$key]);
            if ($value) {
                $return[$key] = $value;
            }
        }
        return $return;
    }

    public function testCreateNestedKeyValuePairExtractsDotSeparatedKeysAndCreatesNestedStructure(): void
    {
        $patchValues = [];
        $this->configResource->createNestedKeyValuePair($patchValues, 'foo.bar.baz', 'value');
        $this->assertArrayHasKey('foo', $patchValues);
        $this->assertEquals('array', gettype($patchValues));
        /** @psalm-suppress MixedArgument */
        $this->assertArrayHasKey('bar', $patchValues['foo']);
        /** @psalm-suppress MixedArgument,MixedArrayAccess */
        $this->assertEquals('array', gettype($patchValues['foo']['bar']));
        /** @psalm-suppress MixedArgument,MixedArrayAccess */
        $this->assertArrayHasKey('baz', $patchValues['foo']['bar']);
        /** @psalm-suppress MixedArgument,MixedArrayAccess */
        $this->assertEquals('value', $patchValues['foo']['bar']['baz']);

        // ensure second call to createNestedKeyValuePair does not destroy original values
        $this->configResource->createNestedKeyValuePair($patchValues, 'foo.bar.boom', 'value2');
        /** @psalm-suppress MixedArgument,MixedArrayAccess */
        $this->assertCount(2, $patchValues['foo']['bar']);
    }

    public function testPatchListUpdatesFileWithMergedConfig(): void
    {
        $config         = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'bat',
                'bat' => 'bogus',
            ],
            'baz' => 'not what you think',
        ];
        $configResource = new ConfigResource($config, $this->file, $this->writer);

        $patch    = [
            'bar.baz' => 'UPDATED',
            'baz'     => 'what you think',
        ];
        $response = $configResource->patch($patch);

        $this->assertEquals($patch, $response);

        $expected = [
            'bar' => [
                'baz' => 'UPDATED',
            ],
            'baz' => 'what you think',
        ];
        $written  = $this->writer->writtenConfig;
        $this->assertSame($expected, $written);
    }

    public function testTraverseArrayFlattensToDotSeparatedKeyValuePairs(): void
    {
        $config   = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'bat',
                'bat' => 'bogus',
            ],
            'baz' => 'not what you think',
        ];
        $expected = [
            'foo'     => 'bar',
            'bar.baz' => 'bat',
            'bar.bat' => 'bogus',
            'baz'     => 'not what you think',
        ];

        $this->assertSame($expected, $this->configResource->traverseArray($config));
    }

    public function testFetchFlattensComposedConfiguration(): void
    {
        $config         = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'bat',
                'bat' => 'bogus',
            ],
            'baz' => 'not what you think',
        ];
        $expected       = [
            'foo'     => 'bar',
            'bar.baz' => 'bat',
            'bar.bat' => 'bogus',
            'baz'     => 'not what you think',
        ];
        $configResource = new ConfigResource($config, $this->file, $this->writer);

        $this->assertSame($expected, $configResource->fetch());
    }

    public function testFetchWithTreeFlagSetToTrueReturnsConfigurationUnmodified(): void
    {
        $config         = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'bat',
                'bat' => 'bogus',
            ],
            'baz' => 'not what you think',
        ];
        $configResource = new ConfigResource($config, $this->file, $this->writer);
        $this->assertSame($config, $configResource->fetch(true));
    }

    public function testPatchWithTreeFlagSetToTruePerformsArrayMergeAndReturnsConfig(): void
    {
        $config         = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'bat',
                'bat' => 'bogus',
            ],
            'baz' => 'not what you think',
        ];
        $configResource = new ConfigResource($config, $this->file, $this->writer);

        $patch    = [
            'bar' => [
                'baz' => 'UPDATED',
            ],
            'baz' => 'what you think',
        ];
        $response = $configResource->patch($patch, true);

        $this->assertSame($patch, $response);

        $expected = [
            'bar' => [
                'baz' => 'UPDATED',
            ],
            'baz' => 'what you think',
        ];
        $written  = $this->writer->writtenConfig;
        $this->assertSame($expected, $written);
    }

    /**
     * @psalm-return array<string, array{
     *     0: string,
     *     1: string|array<array-key, string>,
     *     2: array<string, string|array>
     * }>
     */
    public function replaceKeyPairs(): array
    {
        return [
            'scalar-top-level'        => ['top', 'updated', ['top' => 'updated']],
            'overwrite-hash'          => ['sub', 'updated', ['sub' => 'updated']],
            'nested-scalar'           => [
                'sub.level',
                'updated',
                [
                    'sub' => [
                        'level' => 'updated',
                    ],
                ],
            ],
            'nested-list'             => [
                'sub.list',
                ['three', 'four'],
                [
                    'sub' => [
                        'list' => ['three', 'four'],
                    ],
                ],
            ],
            'nested-hash'             => [
                'sub.hash.two',
                'updated',
                [
                    'sub' => [
                        'hash' => [
                            'two' => 'updated',
                        ],
                    ],
                ],
            ],
            'overwrite-nested-null'   => [
                'sub.null',
                'updated',
                [
                    'sub' => [
                        'null' => 'updated',
                    ],
                ],
            ],
            'overwrite-nested-object' => [
                'sub.object',
                'updated',
                [
                    'sub' => [
                        'object' => 'updated',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider replaceKeyPairs
     * @param string|array $value
     * @param string|array $expected
     * @psalm-param string|array<array-key, string> $value
     * @psalm-param array<string, string|array>     $expected
     */
    public function testReplaceKey(string $key, $value, $expected): void
    {
        $config = [
            'top' => 'level',
            'sub' => [
                'level'  => 2,
                'list'   => [
                    'one',
                    'two',
                ],
                'hash'   => [
                    'one' => 1,
                    'two' => 2,
                ],
                'null'   => null,
                'object' => new stdClass(),
            ],
        ];

        $updated      = $this->configResource->replaceKey($key, $value, $config);
        $intersection = $this->arrayIntersectAssocRecursive($expected, $updated);
        $this->assertSame($expected, $intersection);
        $this->assertEquals(2, count($updated));
    }

    /**
     * @psalm-return array<string, array{
     *     0: string|array<array-key, string>,
     *     1: array<string, string|array>
     * }>
     */
    public function deleteKeyPairs(): array
    {
        return [
            'scalar-top-level'                       => [
                'top',
                [
                    'sub' => [
                        'level' => 2,
                        'list'  => [
                            'one',
                            'two',
                        ],
                        'hash'  => [
                            'one' => 1,
                            'two' => 2,
                        ],
                    ],
                ],
            ],
            'delete-hash'                            => ['sub', ['top' => 'level']],
            'delete-nested-via-arrays'               => [
                ['sub', 'level'],
                [
                    'top' => 'level',
                    'sub' => [
                        'list' => [
                            'one',
                            'two',
                        ],
                        'hash' => [
                            'one' => 1,
                            'two' => 2,
                        ],
                    ],
                ],
            ],
            'delete-nested-via-dot-separated-values' => [
                'sub.level',
                [
                    'top' => 'level',
                    'sub' => [
                        'list' => [
                            'one',
                            'two',
                        ],
                        'hash' => [
                            'one' => 1,
                            'two' => 2,
                        ],
                    ],
                ],
            ],
            'delete-nested-array'                    => [
                'sub.list',
                [
                    'top' => 'level',
                    'sub' => [
                        'level' => 2,
                        'hash'  => [
                            'one' => 1,
                            'two' => 2,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider deleteKeyPairs
     * @param string|array $key
     * @psalm-param string|array<array-key, string> $key
     * @psalm-param array<string, string|array>     $expected
     */
    public function testDeleteKey($key, array $expected): void
    {
        $config = [
            'top' => 'level',
            'sub' => [
                'level' => 2,
                'list'  => [
                    'one',
                    'two',
                ],
                'hash'  => [
                    'one' => 1,
                    'two' => 2,
                ],
            ],
        ];
        $writer = new PhpArray();
        $writer->toFile($this->file, $config);
        // Ensure the writer has written to the file!
        /** @psalm-suppress UnresolvableInclude */
        $this->assertEquals($config, include $this->file);

        // Create config resource, and delete a key
        $configResource = new ConfigResource($config, $this->file, $writer);
        $test           = $configResource->deleteKey($key);

        // Verify what was returned was what we expected
        $this->assertSame($expected, $test);

        // Verify the file contains what we expect
        /** @psalm-suppress UnresolvableInclude */
        $this->assertSame($expected, include $this->file);
    }

    public function testDeleteNestedKeyShouldAssignArrayToParent(): void
    {
        $config = [
            'top' => 'level',
            'sub' => [
                'sub2' => [
                    'sub3' => [
                        'two',
                    ],
                ],
            ],
        ];
        $writer = new PhpArray();
        $writer->toFile($this->file, $config);
        // Ensure the writer has written to the file!
        /** @psalm-suppress UnresolvableInclude */
        $this->assertEquals($config, include $this->file);

        // Create config resource, and delete a key
        $configResource = new ConfigResource($config, $this->file, $writer);
        $test           = $configResource->deleteKey('sub.sub2.sub3');

        // Verify what was returned was what we expected
        $expected = [
            'top' => 'level',
            'sub' => [
                'sub2' => [],
            ],
        ];
        $this->assertSame($expected, $test);

        // Verify the file contains what we expect
        /** @psalm-suppress UnresolvableInclude,MixedAssignment */
        $test = include $this->file;
        $this->assertSame($expected, $test);
    }

    public function testDeleteNonexistentKeyShouldDoNothing(): void
    {
        $config = [];
        $writer = new PhpArray();
        $writer->toFile($this->file, $config);
        // Ensure the writer has written to the file!
        /** @psalm-suppress UnresolvableInclude */
        $this->assertEquals($config, include $this->file);

        // Create config resource, and delete a key
        $configResource = new ConfigResource($config, $this->file, $writer);
        $test           = $configResource->deleteKey('sub.sub2.sub3');

        // Verify what was returned was what we expected
        $expected = [];
        $this->assertSame($expected, $test);

        // Verify the file contains what we expect
        /** @psalm-suppress UnresolvableInclude,MixedAssignment */
        $test = include $this->file;
        $this->assertSame($expected, $test);
    }
}
