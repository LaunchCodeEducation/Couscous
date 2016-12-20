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
     * @var Simple sitemap file (URL list)
     */
    private $simpleSitemapFilename = "sitemap.txt";
    private $htmlSitemapFilename = "sitemap.html";

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Project $project)
    {
        $this->generateSimpleSitemap($project);
        $this->generateHtmlSitemap($project);
    }

    private function generateSimpleSitemap(Project $project)
    {
        $sitemap = fopen($project->targetDirectory.'/'.$this->simpleSitemapFilename, 'w');

        if ($sitemap) {
            $metadata = $project->metadata;

            print_r($metadata);

            foreach ($metadata['pageList'] as $page) {
                $this->logger->debug('Adding {page} to simple sitemap', ['page' => $page]);
                $pageUrl = $metadata['baseUrl'].'/'.$page;
                fwrite($sitemap, $pageUrl.PHP_EOL);
            }

            fclose($sitemap);
        } else {
            $this->logger->error('Failed to open simple sitemap file: {file}. No sitemap generated.', ['file' => $this->simpleSitemapFilename]);
        }

    }

    private function generateHtmlSitemap(Project $project)
    {
        $sitemap = fopen($project->targetDirectory.'/'.$this->htmlSitemapFilename, 'w');

        if ($sitemap) {
            $metadata = $project->metadata;

            $head = '<html>'.
                        '<head>'.
                            '<title>Sitemap</title>'.
                        '</head>'.
                        '<body>'.
                            '<h1>Sitemap</h1>';

            $foot = '</body></html>';

            fwrite($sitemap, $head);
            fwrite($sitemap, $this->parseSubtree($metadata['pageTree'], ''))
            foreach ($metadata['pageList'] as $page) {
                $this->logger->debug('Adding {page} to HTML sitemap', ['page' => $page]);
                $pageUrl = $metadata['baseUrl'].'/'.$page;
                fwrite($sitemap, $pageUrl.PHP_EOL);
            }

            fwrite($sitemap, $foot);

            fclose($sitemap);
        } else {
            $this->logger->error('Failed to open HTML sitemap file: {file}. No sitemap generated.', ['file' => $this->$htmlSitemapFilename]);
        }

    }

    private function parseSubtree($subtree, $markup)
    {
        $markup .= '<ul>';

        foreach ($subtree as $item) {

            // check for type of $item

            // if string, add to markup

            // if array, parse

        }

        $markup .= '</ul>';
        return $markup;
    }

    private function createSiteLink($href, $text)
    {
        return "<li><a href='$href' >$text</a></li>";
    }
}
