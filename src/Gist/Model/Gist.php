<?php

namespace Gist\Model;

use Gist\Model\Base\Gist as BaseGist;

class Gist extends BaseGist
{
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

    public function generateFilename()
    {
        $this->setFile(uniqid());
    }

    public function getGeshiType()
    {
        $data = array(
            'html' => 'xml',
        );

        return str_replace(array_keys($data), array_values($data), $this->getType());
    }

    public function getTypeAsExtension()
    {
        $data = array(
            'javascript' => 'js',
            'yaml'=> 'yml',
            'perl' => 'pl',
            'python' => 'py',
            'bash' => 'sh',
            'actionscript3' => 'as',
            'text' => 'txt',
        );

        return str_replace(array_keys($data), array_values($data), $this->getType());
    }
}
