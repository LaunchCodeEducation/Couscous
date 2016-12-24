<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Project;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Step;

/**
 * If a page has a title add an id attribute to the body tag
 *
 * @author Chris Bay <chris@chrisbay.net>
 */
class CreateBodyId implements Step
{

    /**
     * @param Project $project
     */
    public function __invoke(Project $project)
    {
        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');

        foreach ($htmlFiles as $htmlFile) {
            $content = $htmlFile->getContent();
            $metadata = $htmlFile->getMetadata();

            if (isset($metadata['title'])) {
                $title = "page-".self::slugify($metadata['title']);
                $content = preg_replace('/<\s?body([^>]?)>/i', "<body id='$title'$1>", $content);
            }

            $htmlFile->content = $content;
        }
    }

    private static function slugify($text)
    {
        $slug = trim($text);
        $slug = strtr($slug, ' ', '-');
        $slug = strtolower($slug);

        return preg_replace('/[^a-z0-9_-]/', '', $slug);
    }
}
