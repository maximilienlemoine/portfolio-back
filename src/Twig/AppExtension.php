<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('contrastColor', [$this, 'contrastColor']),
        ];
    }

    public function contrastColor($backgroundColor): string
    {
        // Assuming the color is in hex format (e.g., #ffffff)
        $color = ltrim($backgroundColor, '#');
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $luminance > 0.5 ? 'black' : 'white';
    }
}