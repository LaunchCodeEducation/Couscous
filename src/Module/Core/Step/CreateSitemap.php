<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Psr\Log\LoggerInterface;

/**
 * Writes the generated file URLs/paths to sitemap.txt
 *
 * @author Chris Bay <chris@chrisbay.net>
 */
class CreateSitemap implements Step
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Sitemap file
     */
    private $sitemapFilename = "sitemap.txt";

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Project $project)
    {
        $sitemap = fopen($project->targetDirectory.'/'.$this->sitemapFilename, 'w');
        $metadata = $project->metadata;

        foreach ($metadata['pageList'] as $page) {
            $this->logger->debug('Adding {page} to sitemap', ['page' => $page]);
            $pageUrl = $metadata['baseUrl'].'/'.$page;
            fwrite($sitemap, $pageUrl.PHP_EOL);
        }

        fclose($sitemap);

    }
}
