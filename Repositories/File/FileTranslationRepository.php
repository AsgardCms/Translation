<?php namespace Modules\Translation\Repositories\File;

use Illuminate\Filesystem\Filesystem;
use Modules\Translation\Repositories\FileTranslationRepository as FileTranslationRepositoryInterface;

class FileTranslationRepository implements FileTranslationRepositoryInterface
{
    /**
     * @var Filesystem
     */
    private $finder;

    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Get all the translations for all modules on disk
     * @return array
     */
    public function all()
    {
        $allFileTranslations = [];
        $files = $this->finder->allFiles($this->getTranslationsDirectory());
        foreach ($files as $file) {
            $lang = $this->getLanguageFrom($file->getRelativePath());
            $moduleName = $this->getModuleFrom($file->getRelativePath());
            $path = $file->getRelativePathname();
            $contents = $this->finder->getRequire($this->getTranslationsDirectory() . '/' . $path);
            $trans = array_dot($contents, $moduleName . '::');
            foreach ($trans as $key => $value) {
                $allFileTranslations[$lang][$key] = $value;
            }
        }

        return $allFileTranslations;
    }

    /**
     * @return string
     */
    private function getTranslationsDirectory()
    {
        return __DIR__ . '/../../Resources/lang';
    }

    /**
     * @param string $relativePath
     * @return string
     */
    private function getLanguageFrom($relativePath)
    {
        return substr(strrchr($relativePath, "/"), 1);
    }

    /**
     * @param string $relativePath
     * @return string
     */
    private function getModuleFrom($relativePath)
    {
        return explode('/', $relativePath)[0];
    }
}
