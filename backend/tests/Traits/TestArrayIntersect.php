<?php

declare (strict_types = 1);

namespace Tests\Traits;


trait TestArrayIntersect
{
    protected function assertArrayIntersect(
        array $expected,
        array $current,
        bool $assoc = false
    ){
        $getIntersect = match($assoc) {
            true => array_intersect_assoc($current, $expected),
            false => array_intersect($current, $expected)
        };

        $this->assertCount(count($expected), $getIntersect);
    }
}