<?php

namespace Gist\Api;

use GuzzleHttp\Client as BaseClient;

/**
 * Class Client.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class Client extends BaseClient
{
    /**
     * URI of creation.
     *
     * @const string
     */
    const CREATE = '/en/api/create';

    /**
     * URI of update.
     *
     * @const string
     */
    const UPDATE = '/en/api/update/{gist}';

    /**
     * URI of delete.
     *
     * @const string
     */
    const DELETE = '/en/api/delete/{gist}';

    /**
     * URI of list.
     *
     * @const string
     */
    const LIST = '/en/api/list';

    /**
     * The API key.
     *
     * @var string|null
     */
    protected $apiKey;

    /**
     * Creates a gist.
     *
     * @param string $title   The title
     * @param string $type    The type
     * @param string $content The content
     *
     * @return array
     */
    public function create($title, $type, $content)
    {
        $response = $this->post(
            $this->mergeApiKey(self::CREATE),
            array(
                'form_params' => array(
                    'form' => array(
                        'title' => $title,
                        'type' => $type,
                        'content' => $content,
                    ),
                ),
            )
        );

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return [];
    }

    /**
     * Clones and update a gist.
     *
     * @param string $gist    Gist's ID
     * @param string $content The content
     *
     * @return array
     */
    public function update($gist, $content)
    {
        $response = $this->post(
            str_replace('{gist}', $gist, $this->mergeApiKey(self::LIST)),
            array(
                'form_params' => array(
                    'form' => array(
                        'content' => $content,
                    ),
                ),
            )
        );

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return [];
    }

    /**
     * Deletes a gist.
     *
     * @param string $gist Gist's ID
     *
     * @return array
     */
    public function delete($gist)
    {
        $response = $this->post(str_replace('{gist}', $gist, $this->mergeApiKey(self::DELETE)));

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return [];
    }

    /**
     * Lists the user's gists.
     *
     * @param string $gist    Gist's ID
     * @param string $content The content
     *
     * @return array
     */
    public function list()
    {
        $response = $this->get($this->mergeApiKey(self::LIST));

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return [];
    }

    /*
     * Merges the API key with the given url.
     *
     * @param string $url
     *
     * @return string
     */
    public function mergeApiKey($url)
    {
        if (empty($this->apiKey)) {
            return $url;
        }

        return rtrim($url, '/').'/'.$this->apiKey;
    }

    /*
     * Set the value of "apiKey".
     *
     * @param string|null $apiKey
     *
     * @return Client
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /*
     * Get the value of "apiKey".
     *
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
