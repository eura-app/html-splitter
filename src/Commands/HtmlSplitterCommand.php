<?php

namespace David\HtmlSplitter\Commands;

use Illuminate\Console\Command;

class HtmlSplitterCommand extends Command
{
    public $signature = 'html-splitter';

    public $description = 'My command';

    public function handle(): int
    {

        $this->comment('All done');

        return self::SUCCESS;
    }
}
