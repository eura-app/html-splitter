<?php

namespace David\HtmlSplitter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use David\HtmlSplitter\Commands\HtmlSplitterCommand;

class HtmlSplitterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('html-splitter')
            ->hasConfigFile()
            // ->hasViews()
            // ->hasMigration('create_html_splitter_table')
            ->hasCommand(HtmlSplitterCommand::class);
    }
}
