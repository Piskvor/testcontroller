# Transparent caching interface with configurable drivers

An extensible source-querying interface with popularity logging and swappable backends.

The intended flow of action, as seen in `foo\index.php`:

- controller gets called with a product ID
    - see method `detailAction()` in `foo/controllers/ProductController`
    - a set of configuration options is injected (in an object implementing `IConfig`)
        - in the example, `LocalConfig` is instantiated, selecting a config from the environment string 
    - also injected are the two product backends (implementing `IElasticSearchDriver` and `IMySQLDriver`, respectively)
        - these are provided externally and we have no control over them, hence their location in `vendor\db\`
    - from the configuration, a `ISearch` implementation is chosen: a facade unifying the above backends under one interface
        - the implementation always uses only one backend at a time
- if the product detail is HTTP-cached, only the popularity counter is incremented, no further serverside processing
    - we could set an `Expires` header, but we'd lose the popularity counter on return visitors and would have to get them out-of-band (as no HTTP request would be made)
    - therefore, we only check for *existence* of `If-Modified-Since`: as we assume infinite cache, this means we have a return visitor, but we don't need to spin up backends (not even the server cache)
    - only bump the popularity keeper
- if the product detail is cached, it is returned from cache backend
- else the product backend is queried (MySQL and ElasticSearch backends are provided here)
- when a match is found, it is stored in the cache
- popularity counter is incremented
- result is sent to the client as JSON