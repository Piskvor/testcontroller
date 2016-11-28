# Transparent caching interface with configurable drivers

An extensible source-querying interface with popularity logging and swappable backends.

The intended flow of action, as seen in `foo\index.php`:

- controller gets called with a product ID
    - see method `detailAction()` in `foo/controllers/ProductController`
    - a set of configuration options is injected (in an object implementing `IConfig`)
        - in the example, `LocalConfig` is be instantiated 
    - also injected are the two product backends (implementing `IElasticSearchDriver` and `IMySQLDriver`, respectively)
    - from the configuration, a `ISearch` implementation is chosen: a facade unifying the above backends under one interface
        - the implementation always uses only one backend
- if the product detail is HTTP-cached, only the popularity counter is incremented, no further serverside processing
- if the product detail is cached, it is returned from cache
- else the product backend is queried (MySQL and ElasticSearch backends are provided in `vendor\db\*`)
- when a match is found, it is stored in the cache
- popularity counter is incremented
- result is sent to the client as JSON