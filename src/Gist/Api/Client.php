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
     * URI of creation
     * 
     * @const string
     */
    const CREATE = '/en/api/create';

    /**
     * URI of updating
     *
     * @const string
     */
    const UPDATE = '/en/api/update/{gist}';

    /**
     * Creates a gist
     *
     * @param string $title The title
     * @param string $type The type
     * @param string $content The content
     * @return array
     */
    public function create($title, $type, $content)
    {
        $response = $this->post(
            self::CREATE,
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
            str_replace('{gist}', $gist, self::UPDATE),
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
}
