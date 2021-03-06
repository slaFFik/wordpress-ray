<?php

namespace Spatie\WordPressRay\Spatie\Ray\Origin;

use Spatie\WordPressRay\Spatie\Backtrace\Backtrace;
use Spatie\WordPressRay\Spatie\Backtrace\Frame;
use Spatie\WordPressRay\Spatie\Ray\Ray;

class DefaultOriginFactory implements OriginFactory
{
    public function getOrigin(): Origin
    {
        $frame = $this->getFrame();

        return new Origin(
            $frame ? $frame->file : null,
            $frame ? $frame->lineNumber : null
        );
    }

    protected function getFrame(): ?Frame
    {
        $frames = $this->getAllFrames();

        $indexOfRay = $this->getIndexOfRayFrame($frames);

        return $frames[$indexOfRay] ?? null;
    }

    protected function getAllFrames(): array
    {
        $frames = Backtrace::create()->frames();

        return array_reverse($frames, true);
    }

    protected function getIndexOfRayFrame(array $frames): ?int
    {
        $index = $this->search(function (Frame $frame) {
            if ($frame->class === Ray::class) {
                return true;
            }

            if ($this->startsWith($frame->file, dirname(__DIR__))) {
                return true;
            }

            return false;
        }, $frames);

        return $index + 1;
    }

    public function startsWith(string $hayStack, string $needle): bool
    {
        return strpos($hayStack, $needle) === 0;
    }

    protected function search(callable $callable, array $items): ?int
    {
        foreach ($items as $key => $item) {
            if ($callable($item, $key)) {
                return $key;
            }
        }

        return null;
    }
}
