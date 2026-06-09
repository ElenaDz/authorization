<?php

namespace Auth\App\Entity;

class DleSong extends _Base
{
    const NAME_ID = 'id';
    const NAME_URL = 'url';
    const NAME_POST_IMAGE = 'post_image';
    const NAME_ALT_NAME = 'alt_name';
    const NAME_CATEGORY = 'category';
    const NAME_TITLE = 'title';
    const NAME_ARTIST_NAME = 'artist_name';
    const NAME_XFIELDS = 'xfields';

    private $id;
    private $url;
    private $post_image;
    private $altName;
    private $category;
    private $title;
    private $artistName;
    private $xfields;
    private $duration;
    private $bitrate;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl(): void
    {
        $link = self::getValueParam('link');

        $url = self::generate_secret_link($link);

        $this->url = $url;
    }

    public function getPostImage()
    {
        return $this->post_image;
    }


    public function setPostImage($post_image): void
    {
        $this->post_image = $post_image;
    }

    public function getAltName()
    {
        return $this->altName;
    }

    public function setAltName($altName)
    {
        $this->altName = $altName;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getArtistName()
    {
        return str_replace('|', ' ', $this->artistName);
    }

    public function setArtistName($artistName)
    {
        $this->artistName = $artistName;
    }

    public function getXfields()
    {
        return $this->xfields;
    }

    public function getBitrate()
    {
        return $this->bitrate;
    }

    public function setBitrate()
    {
        $bitrate = self::getValueParam('bit');

        $this->bitrate = $bitrate;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration()
    {
        $duration = self::getValueParam('prodol');

        $this->duration = $duration;
    }

    public function setXfields($xfields)
    {
        $this->xfields = $xfields;
    }

    private function getValueParam($param) {
        // Разбиваем строку по разделителю '|'
        $parts = explode('|', $this->getXfields());

        $result = [];

        // Перебираем элементы с шагом 2, так как они идут парами: "ключ" -> "значение"
        for ($i = 0; $i < count($parts); $i += 2) {
            $key = trim($parts[$i]);
            $value = isset($parts[$i + 1]) ? trim($parts[$i + 1]) : '';

            if ($key !== '') {
                $result[$key] = $value;
            }
        }

        return $result[$param];
    }

    // Взято на copy.drivemusic, изменено $secret_word
    private function generate_secret_link($url, $time=43200, $useip = true, $online = 1)
    {

        if ( preg_match('/^https?:\/\/([^\/]+)\/uploads(\/.*)/i', $url, $match) )
        {
            if($online == 1) {
                $uri_prefix = 'https://drivemusic.me/dl/online/';
            } else {
                $uri_prefix = 'https://drivemusic.me/dl/';
            }
            $file = $match[2];
            $addr = $useip?$_SERVER['REMOTE_ADDR']:"";
            $secret_word = "secret_word"; // <D1><E5><EA><F0><E5><F2><ED><EE><E5> <F1><EB><EE><E2><EE> <E4><EE><EB><E6><ED><EE> <E1><FB><F2><FC> <EE><E4><E8><ED><E0><EA><EE><E2><EE>
            $time = time()+$time; // <D1><F1><FB><EB><EA><E0> <E1><F3><E4><E5><F2> <E4><E5><E9><F1><F2><E2><E8><F2><E5><EB><FC><ED><E0> <E2> <F2><E5><F7><E5><ED><E8><E8> 12 <F7><E0><F1>
            //    $secret = md5($time.'.'.$file.'.'.$addr.'.'.$secret_word, true); // <D5><E5><F8><E8><F0><F3><E5><EC> <E2> md5 bin.
            $secret = md5($time.'.'.$file.'.'.$secret_word, true); // <D5><E5><F8><E8><F0><F3><E5><EC> <E2> md5 bin.
            $secret = substr(strtr(base64_encode($secret), '+/', '-_'),0,-2); // Encode base64 url
            $hash_url = $uri_prefix.$secret.'/'.$time.$file; // <D4><EE><F0><EC><E8><F0><F3><E5><EC> <F1><F1><FB><EB><EA><F3>

            return $hash_url;
        }

        return $url;
    }

}