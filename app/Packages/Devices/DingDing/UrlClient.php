<?php


namespace App\Packages\Devices\DingDing;


class UrlClient extends Client
{
    public function get()
    {
        return $this->httpGet('getUrl');
    }

    public function update(string $url)
    {
        return $this->httpGet('updateUrl', compact('url'));
    }

    public function store(string $url)
    {
        return $this->httpGet('saveUrl', compact('url'));
    }

    public function destroy()
    {
        return $this->httpGet('deleteUrl');
    }
}
