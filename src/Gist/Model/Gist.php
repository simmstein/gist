<?php

namespace Gist\Model;

use Gist\Model\Base\Gist as BaseGist;
use Propel\Runtime\Map\TableMap;

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
     * Returns the type for highlighting.
     *
     * @return string
     */
    public function getHighlightType()
    {
        $data = array(
            'html' => 'xml',
            'asp' => 'aspnet',
            'actionscript3' => 'actionscript',
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
            'markdown' => 'md',
        );

        return str_replace(array_keys($data), array_values($data), $this->getType());
    }

    /*
     * Increments the number of commits.
     */
    public function commit()
    {
        $this->setCommits($this->getCommits() + 1);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        $data = parent::toArray(
            $keyType,
            $includeLazyLoadColumns,
            $alreadyDumpedObjects,
            $includeForeignObjects
        );

        foreach ($data as $key => $value) {
            $newKey = lcfirst($key);
            unset($data[$key]);
            $data[$newKey] = $value;
        }

        return $data;
    }
}
