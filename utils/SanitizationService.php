<?php
declare(strict_types=1);

## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.

require_once ('views/header.php');

require_once('vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php');


my_error_logging_principles();


final class SanitizationService
{
    /** @var HTMLPurifier */
    private $htmlPurifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        
        // Remove this after you configured your final set
        $config->set('Cache.DefinitionImpl', null);
        
        $config->set('Core.Encoding', 'UTF-8');

        $allowedElements = [];

        $config->set('HTML.Allowed', implode(',', $allowedElements));

        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('span', 'data-custom-id', 'Text');
        $def->addAttribute('span', 'contenteditable', 'Text');

        $this->htmlPurifier = new HTMLPurifier($config);
    }

    public function sanitizeHtml(string $content): string
    {
        return $this->htmlPurifier->purify($content);
    }
}