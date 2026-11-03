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

namespace Pdir\MaklermodulBundle\Tests\Util;

use Pdir\MaklermodulBundle\Util\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testEscapeEncodesHtml(): void
    {
        $this->assertSame(
            '&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;',
            Helper::escape('<script>alert("x")</script>')
        );
    }

    public function testEscapeStripsInsertTags(): void
    {
        $this->assertSame('Wohnung ', Helper::escape('Wohnung {{env::path}}'));
    }

    public function testEscapeHandlesNullAndArrays(): void
    {
        $this->assertSame('', Helper::escape(null));
        $this->assertSame('', Helper::escape(['a']));
        $this->assertSame('123', Helper::escape(123));
    }

    public function testEscapeInsertTagParamRemovesParameterSeparators(): void
    {
        $value = Helper::escapeInsertTagParam('Haus & Hof?mode=box}}{{env::path');

        $this->assertStringNotContainsString('&', $value);
        $this->assertStringNotContainsString('?', $value);
        $this->assertStringNotContainsString('=', $value);
        $this->assertStringNotContainsString('{', $value);
        $this->assertStringNotContainsString('}', $value);
        $this->assertStringStartsWith('Haus', $value);
    }

    public function testEscapeXmlPreventsInjection(): void
    {
        $this->assertSame(
            'Max&lt;/vorname&gt;&lt;anfrage&gt;x&lt;/anfrage&gt; &amp; &quot;Co&quot;',
            Helper::escapeXml('Max</vorname><anfrage>x</anfrage> & "Co"')
        );
    }
}
