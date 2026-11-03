<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2025 pdir / digital agentur // pdir GmbH
 *
 * @package    maklermodul-bundle
 * @link       https://www.maklermodul.de
 * @license    proprietary / pdir license - All-rights-reserved - commercial extension
 * @author     Mathias Arzberger <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pdir\MaklermodulBundle\Tests\Maklermodul\Domain\Repository;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use PHPUnit\Framework\TestCase;

class EstateRepositoryTest extends TestCase
{
    private string $storageDir;

    protected function setUp(): void
    {
        $this->storageDir = sys_get_temp_dir().'/maklermodul-test-'.uniqid('', true).'/';
        mkdir($this->storageDir, 0777, true);
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->storageDir.'*') ?: []);
        rmdir($this->storageDir);
    }

    public function testReturnsNullForUnknownObject(): void
    {
        $repository = new EstateRepository($this->storageDir);

        $this->assertNull($repository->findByObjectId('does-not-exist'));
    }

    /**
     * @dataProvider invalidAliasProvider
     */
    public function testRejectsPathTraversal(string $alias): void
    {
        // A file outside of the alias pattern must never be loaded
        file_put_contents($this->storageDir.'secret.json', '{"a":"b"}');

        $repository = new EstateRepository($this->storageDir);

        $this->assertNull($repository->findByObjectId($alias));
    }

    public static function invalidAliasProvider(): \Generator
    {
        yield 'empty' => [''];
        yield 'parent directory' => ['../secret'];
        yield 'nested path' => ['sub/secret'];
        yield 'backslash' => ['sub\\secret'];
        yield 'null byte' => ["secret\0"];
    }
}
