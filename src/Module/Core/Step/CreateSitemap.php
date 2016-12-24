<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Module\Template\Model\TextFile;
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

    private $pageList = [];

    private $pageTree = [];

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
        $this->generateSiteTree($project);
        $this->generateHtmlSitemap($project);
        $this->generateSimpleSitemap($project);
    }

    private function generateSimpleSitemap(Project $project)
    {
        $urlList = '';

        foreach ($this->pageList as $page) {
            $this->logger->debug('Adding {page} to simple sitemap', ['page' => $page]);
            $pageUrl = $project->metadata['baseUrl'].'/'.$page;
            $urlList .= $pageUrl.PHP_EOL;
        }

        $project->addFile(new TextFile($this->simpleSitemapFilename, $urlList));
    }

    private function generateHtmlSitemap(Project $project)
    {
        $pageListMarkup = '<ul>'.self::generateSubtreeMarkup($this->pageTree, '/');
        $pageListMarkup .= "<li><a href='$this->htmlSitemapFilename'>Sitemap</a></li></ul>";

        $htmlSitemap = new HtmlFile($this->htmlSitemapFilename, $pageListMarkup);
        $metadata = $htmlSitemap->getMetadata();
        $metadata['title'] = 'Sitemap';
        $project->addFile($htmlSitemap);

        $this->pageTree['Sitemap'] = 'sitemap.html';
        $this->pageList[] = 'sitemap.html';
    }

    private static function generateSubtreeMarkup(&$subtree, $path)
    {
        $markup = '';

        foreach ($subtree as $key => $item) {

            $markup .= '<li>';

            // if array parse subtree
            if (is_array($item)) {

                $markup .= '<ul>';

                // Insert labels for dirs w/o an index
                $pages = array_filter($item, function($i){
                    return is_string($i);
                });

                if (sizeof($pages) == 0) {
                    $dirLabel = ucwords(str_replace('-', ' ', $key));
                    $markup .= $dirLabel;
                }

                $markup .= self::generateSubtreeMarkup($item, $path.$key.'/');
                $markup .= '</ul>';
            }

            // if string, add to markup
            if (is_string($item)) {
                $href = $path.$item;
                $markup .= "<a href='$href'>$key</a>";
            }

            $markup .= '</li>';

        }

        return $markup;
    }

    private function generateSiteTree($project) {

        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');

        foreach ($htmlFiles as $file) {
            $this->pageList[] = $file->relativeFilename;

            $path = dirname($file->relativeFilename);
            $filename = basename($file->relativeFilename);
            $fileMetadata = $file->getMetadata();

            if ($path === '.') {
                $path = [];
            } else {
                $path = explode(DIRECTORY_SEPARATOR, $path);
            }

            $title = $fileMetadata['title'];

            $this->setValue($this->pageTree, $path, $title, $filename);
        }

        // Sort
        natsort($this->pageList);
        self::sortRecursively($this->pageTree);
    }

    private static function setValue(array &$array, array $path, $key, $value)
    {
        if (empty($path)) {
            $array[$key] = $value;

            return;
        }

        $dir = array_shift($path);

        if (!array_key_exists($dir, $array)) {
            $array[$dir] = [];
        }

        self::setValue($array[$dir], $path, $key, $value);
    }

    private static function sortRecursively(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                self::sortRecursively($value);
            }
        }
        ksort($array);
    }

}
