<?php

namespace Canvas\App\Components\Markdown;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;

class ResponsiveImageExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment) : void
    {
        $environment->addEventListener(DocumentParsedEvent::class, new ResponsiveImageProcessor());
    }
}
