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

        if (isset($data['cipher'])) {
            $this->setCipher(in_array($data['cipher'], [true, 'yes']));
        }

        return $this;
    }

    public function generateFilename()
    {
        $this->setFile(uniqid());
    }
}
