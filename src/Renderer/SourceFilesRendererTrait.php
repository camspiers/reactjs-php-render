<?php

namespace ReactJS\Renderer;

use InvalidArgumentException;
use Traversable;

trait SourceFilesRendererTrait
{
    /**
     * @var array|\Traversable
     */
    protected $sourceFiles;

    /**
     * @param array|\Traversable $sourceFiles
     * @throws \InvalidArgumentException
     */
    public function setSourceFiles($sourceFiles)
    {
        if (!(is_array($sourceFiles) || $sourceFiles instanceof Traversable)) {
            throw new InvalidArgumentException(
                "\$sourceFiles passed to setSourceFiles must be iterable"
            );
        }

        foreach ($sourceFiles as $sourceFile) {
            if (!is_readable($sourceFile)) {
                throw new InvalidArgumentException(
                    sprintf(
                        "File '%s' doesn't exist or is not readable",
                        $sourceFile
                    )
                );
            }
        }

        $this->sourceFiles = $sourceFiles;
    }

    /**
     * @return array|\Traversable
     */
    public function getSourceFiles()
    {
        return $this->sourceFiles;
    }
}