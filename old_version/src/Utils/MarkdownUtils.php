<?php

namespace App\Utils;

class MarkdownUtils
{
    public static function markdownToHtml(string $markdown): string
    {
        return (new Parsedown())->setSafeMode(true)->text($markdown);
    }
}
