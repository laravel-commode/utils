<?php

namespace LaravelCommode\Utils\Path;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testStripSlashes()
    {
        $urls = [
            '/url',
            'url/',
            '/url/'
        ];

        foreach ($urls as $url) {
            $this->assertSame('url', Helper::stripSlashes($url));
        }
    }
}
