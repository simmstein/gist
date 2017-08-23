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
     * URI of list.
     *
     * @const string
     */
    const LIST = '/en/api/list';

    /**
     * The API token.
     *
     * @var string|null
     */
    protected $apiToken;

    /**
     * Creates a gist.
     *
     * @param string $title The title
     * @param string $type The type
     * @param string $content The content
     *
     * @return array
     */
    public function create($title, $type, $content)
    {
        $response = $this->post(
            $this->mergeToken(self::CREATE),
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
     * Clones and update a gist
     *
     * @param string $gist Gist's ID
     * @param string $content The content
     *
     * @return array
     */
    public function update($gist, $content)
    {
        $response = $this->post(
            str_replace('{gist}', $gist, $this->mergeToken(self::LIST)),
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
     * Lists the user's gists.
     *
     * @param string $gist Gist's ID
     * @param string $content The content
     *
     * @return array
     */
    public function list()
    {
        $response = $this->get($this->mergeToken(self::LIST));

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return [];
    }

    /*
     * Merges the API token with the given url..
     *
     * @param string $url
     *
     * @return string
     */
    public function mergeToken($url)
    {
        if (empty($this->apiToken)) {
            return $url;
        }

        return rtrim($url, '/').'/'.$this->apiToken;
    }

    /*
     * Set the value of "apiToken".
     *
     * @param string|null $apiToken
     *
     * @return Client
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /*
     * Get the value of "apiToken".
     *
     * @return string|null
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }
}
