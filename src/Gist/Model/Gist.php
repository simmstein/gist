<?php

namespace Gist\Model;

use Gist\Model\Base\Gist as BaseGist;

/**
 * Class Gist.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class Gist extends BaseGist
{
    /**
     * Hydrates the gist with array data.
     *
     * @param array $data
     *
     * @return Gist
     */
    public function hydrateWith(array $data)
    {
        if (isset($data['title'])) {
            $this->setTitle(trim($data['title']));
        }

        if (isset($data['type'])) {
            $this->setType($data['type']);
        }

        $this->setCipher(isset($data['cipher']) && $data['cipher'] === 'yes');

        return $this;
    }

    /**
     * Generates a unique filename.
     *
     * @return string
     */
    public function generateFilename()
    {
        $this->setFile(uniqid());
    }

    /**
     * Returns the type for Geshi.
     *
     * @return string
     */
    public function getGeshiType()
    {
        $data = array(
            'html' => 'xml',
        );

        return str_replace(array_keys($data), array_values($data), $this->getType());
    }

    /**
     * Returns the extension depending of the type.
     *
     * @return string
     */
    public function getTypeAsExtension()
    {
        $data = array(
            'javascript' => 'js',
            'yaml' => 'yml',
            'perl' => 'pl',
            'python' => 'py',
            'bash' => 'sh',
            'actionscript3' => 'as',
            'text' => 'txt',
        );

        return str_replace(array_keys($data), array_values($data), $this->getType());
    }
}
