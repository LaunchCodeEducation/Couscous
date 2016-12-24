<?php

namespace Couscous\Module\Template\Model;

use Couscous\Model\File;

/**
 * @author Chris Bay <chris@chrisbay.net>
 */
class TextFile extends File
{
    /**
     * @var string
     */
    public $content;

    public function __construct($relativeFilename, $content)
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
