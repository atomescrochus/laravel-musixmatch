<?php

namespace Atomescrochus\Musixmatch;

use Illuminate\Support\Facades\Cache;

class Musixmatch
{
    public $base_url;
    public $parameters;
    public $endpoint;

    public $cache_time;
    private $inCache;
    private $cacheOnly;

    private $api_key;

    public function __construct()
    {
        $this->cache_time = 1440;
        $this->inCache = false;
        $this->cacheOnly = false;

        $this->api_key = config('laravel-musixmatch.api_key');
        $this->base_url = "http://api.musixmatch.com/ws/1.1/";
        $this->parameters = [
            'apikey' => $this->api_key,
        ];
    }

    ////
    // API functions
    ////

    /**
     * Set the request endpoint to track.search
     */
    public function trackSearch($q_track = null, $q_artist = null, $q_lyrics = null)
    {
        if (!is_null($q_track)) {
            $this->parameters['q_track'] = $q_track;
        }

        if (!is_null($q_artist)) {
            $this->parameters['q_artist'] = $q_artist;
        }

        if (!is_null($q_lyrics)) {
            $this->parameters['q_lyrics'] = $q_lyrics;
        }

        $this->endpoint = "track.search";

        return $this;
    }

    public function getLyrics($track_id)
    {
        $this->endpoint = "track.lyrics.get";
        $this->parameters['track_id'] = $track_id;

        return $this;
    }

    /**
     * Calls the API to get the results
     */
    public function results()
    {
        $request_url = $this->createRequestUrl();
        $cache_name = md5($request_url);

        if ($this->inCache) {
            return Cache::has($cache_name);
        }

        $cache = $this->checkForCache($cache_name);

        if (isset($cache->content)) {
            $cached = $cache->content;
            $cached->cacheOnly = $this->cacheOnly;
            return $cached;
        }

        if ($this->cacheOnly) { // here, there is no cached content, and we've asked only for the cache...
            return (object) [
                'results' => collect([]),
                'count' => 0,
                'cached' => $cached,
                'cacheOnly' => $this->cacheOnly,
                'raw' => json_decode($raw),
                'query' => urldecode($this->createRequestUrl()),
            ];
        }

        $response = \Httpful\Request::get($request_url)->expectsJson()->send();

        if ($response->code == 200 && $cache->shouldCache == true) {
            return  Cache::remember($cache_name, $this->cache_time, function () use ($response) {
                return $this->formatApiResults($response);
            });
        }

        return  $this->formatApiResults($response, false);
    }

    ////
    // Utilities
    ////
    
    private function formatApiResults($result, $cached = true)
    {
        $raw = $result->raw_body;

        if ($this->endpoint == "track.search") {
            $results = collect($result->body->message->body->track_list)->map(function ($track) {
                $track = collect($track)->get('track');
                return $track;
            });

            $count = $results->count();
        }

        if ($this->endpoint == "track.lyrics.get") {
            $results = collect($result->body->message->body->lyrics);
            $count = 1;
        }

        if ($results->count() > 0) {
            return (object) [
                'results' => $results,
                'count' => $count,
                'cached' => $cached,
                'cacheOnly' => $this->cacheOnly,
                'raw' => json_decode($raw),
                'query' => urldecode($this->createRequestUrl()),
            ];
        }

        return (object) [
            'results' => collect([]),
            'count' => 0,
            'cached' => $cached,
            'cacheOnly' => $this->cacheOnly,
            'raw' => json_decode($raw),
            'query' => urldecode($this->createRequestUrl()),
        ];
    }

    /**
     * Creates the URL to make the request to the API
     */
    private function createRequestUrl()
    {
        $parameters = http_build_query($this->parameters);
        return "{$this->base_url}{$this->endpoint}?{$parameters}";
    }

    /////
    // Cache related function
    ////
    
    /**
     * Change the default duration of the cache
     */
    public function setCacheDuration(int $minutes)
    {
        $this->cache_time = $minutes;

        return $this;
    }

    /**
     * Should be query only in the cache?
     */
    public function cacheOnly(bool $cacheOnly = true)
    {
        $this->cacheOnly = $cacheOnly;

        return $this;
    }

    /**
     * Check if query exists in the cache
     */
    public function inCache(bool $inCache = true)
    {
        $this->inCache = $inCache;

        return $this;
    }

    /**
     * Check if API request is cached
     */
    private function checkForCache($name)
    {
        $cache = (object) ['content' => null, 'shouldCache' => true];

        if (Cache::has($name)) {
            $cache->content = Cache::get($name);
        }

        if ($this->cache_time == 0) {
            $cache->shouldCache = false;
            $cache->content = null;
        }

        return $cache;
    }
}
