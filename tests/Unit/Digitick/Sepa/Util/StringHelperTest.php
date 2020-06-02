<?php

namespace Tests\Unit\Digitick\Sepa\Util;

use Digitick\Sepa\Util\StringHelper;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for StringHelper
 */
class StringHelperTest extends TestCase
{

    /**
     * Tests german character translation
     */
    public function testGermanCharacters()
    {
        $string = 'ÄÖÜäöüß';

        $this->assertEquals('AeOeUeaeoeuess', StringHelper::sanitizeString($string));
    }

    /**
     * Tests german character translation
     */
    public function testSpecialCharacters()
    {
        $string = 'Az09#_:?,-(+.)';

        $this->assertEquals('Az09  :?,-(+.)', StringHelper::sanitizeString($string));
    }

}
