<?php


namespace App\Service;


class ApiWrapper
{
    const API_LOCATION = '94.254.0.188:4000';
    const BOOKS_RESOURCE = '/books';
    const AUTHORS_RESOURCE = '/authors';
    const AUTHORS_BOOKS_RESOURCE = '/authors/{authorId}/books';

    /**
     * @param int $limit
     * @param int $offset
     * @return array|mixed
     */
    public function getBooks($limit = 0, $offset = 0)
    {
        $resource = self::BOOKS_RESOURCE.'?limit='.$limit.'&offset='.$offset;
        $request = $this->getRequest($resource);
        if (array_key_exists('books', $request)) {
            return $request['books'];
        }else{
            return [];
        }
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|mixed
     */
    public function getAuthors($limit = 0, $offset = 0)
    {
        $resource = self::AUTHORS_RESOURCE.'?limit='.$limit.'&offset='.$offset;
        $request = $this->getRequest($resource);
        if (array_key_exists('authors', $request)) {
            return $request['authors'];
        }else{
            return [];
        }
    }

    /**
     * @param $authorId
     * @param int $limit
     * @param int $offset
     * @return array|mixed
     */
    public function getBooksByAuthors($authorId, $limit = 0, $offset = 0)
    {
        $resource = str_ireplace('{authorId}', $authorId, self::AUTHORS_BOOKS_RESOURCE);
        $resource = $resource.'?limit='.$limit.'&offset='.$offset;

        $request = $this->getRequest($resource);
        if (array_key_exists('books', $request)) {
            return $request['books'];
        }else{
            return [];
        }
    }

    /**
     * @param $resource
     * @return mixed
     */
    private function getRequest($resource)
    {
        $ch = curl_init(self::API_LOCATION.$resource);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: PHP-IPN-Verification-Script',
            'Connection: Close',
        ));

        $res = curl_exec($ch);

        if (!($res)) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }
        $info = curl_getinfo($ch);
        $http_code = $info['http_code'];

        if ($http_code != 200) {
            throw new Exception("Fake Api responded with http code $http_code");
        }
        curl_close($ch);

        return json_decode($res, true)['data'];
    }
}