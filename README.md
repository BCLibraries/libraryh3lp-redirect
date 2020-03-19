# LibraryH3lp Redirect

`libraryh3lp-redirect` redirects BC Libraries users to the appropriate [LibraryH3lp](https://libraryh3lp.com/) chat queue.

This package is specific to Boston College. If you are looking for a general solution to resolving an appropriate LibraryH3lp queue from a list of queues, see [`bclibraries/libraryh3lp`](https://github.com/BCLibraries/libraryh3lp).

## Installation

Download the latest release and use the [composer](https://getcomposer.org/) package manager to install.

```bash
git clone https://github.com/BCLibraries/libraryh3lp-redirect.git
cd libraryh3lp-redirect
composer install
```

## Usage

Point the chat URL (e.g. [https://library.bc.edu/chat](https://library.bc.edu/chat)) to *public/index.php*.

### Queue options

`Queue` constructors take the following arguments:

* **`code`** - (_string_) the queue's LibraryH3lp ID code
* **`title`** - (_string_) a human-readable title for the queue
* `skin` - (_string_) a numeric LibraryH3lp skin code (BC's is 29500)
* `sounds` - (_bool_) enable sounds by default

```php
$queue = new Queue('queue-code', 'Queue Title', '99999', true);
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)