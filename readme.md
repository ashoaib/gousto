## Gousto API Test
This is an API to provide recipes and ratings, based on the [Gousto](http://www.gousto.co.uk) API Test requirements document. It is built using PHP and the Lumen framework.

### Prerequisites
- Apache
- `PHP >= 5.6`
- `composer`
- Command line access

### Installation
1. Clone this repo to a suitable location
2. Configure an Apache `VirtualHost`, and set the `DocumentRoot` to `/path/to/cloned/repo/public`
3. Add the configured hostname to your hosts file
4. On the command line, navigate to the repo root, and run
```
composer install
```

### Usage
- Use your favourite REST client (or `curl`) – [full API specifications](https://htmlpreview.github.io/?https://github.com/ashoaib/gousto/blob/master/docs/html/gousto-api.html)
- To enable debug mode and see detailed error messages, update the `.env` file with
```
API_DEBUG=true
```
#### Root namespace
```
Gousto\
```
#### Code path
```
app/Gousto
```

### Tests
- To run tests, navigate to repo root
  - For unit tests, run:
  ```
  ./vendor/bin/phpunit --testsuite gousto-unit
  ```
  - For acceptance tests, run:
  ```
  ./vendor/bin/phpunit --testsuite gousto-acceptance
  ```
#### Unit tests
```
tests/Gousto/Unit/Tests
```
#### Acceptance tests
```
tests/Gousto/Acceptance/Tests
```

### Notes
- Lumen was chosen as the framework due to:
  - Familiarity
  - Easy to bootstrap
  - Suitable for RESTful APIs
  - Performance as a micro-framework
- API built with extendability in mind
  - Most components bound to interfaces and dependency injected
  - Trivial to swap/add new components
  - Adhering to SOLID principles
- Different API consumers can take advantage of dependency injectable transformers
  - Allows different API responses with different data based on context of request (e.g. from web or mobile)
- Some shortcuts taken (e.g. in the `JsonModel` or `RatingService`) to work around lack of proper database engine 
