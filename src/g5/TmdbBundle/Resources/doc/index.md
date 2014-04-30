TmdbBundle
==========

Use the api as mock in a functional test
----------------------------------------

**Create a response mock**

```php
private function getMockResponsePlugin($body, $status = 200)
{
    $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
    $response = new \Guzzle\Http\Message\Response($status);

    $response->setBody($body);
    $response->setInfo(array('total_time' => 0.1));

    $plugin->addResponse($response);

    return $plugin;
}

private function getMockTmdbApi()
{
    // create the real api on your own way
    $tmdbApi = new TmdbApi();
    $tmdbApi->addSubscriber($this->getMockResponsePlugin('{ "json":"string"}', 200));

    return $tmdbApi;
}
```

Now you use every function as expected. Internaly Guzzle will use the Response Mock.
