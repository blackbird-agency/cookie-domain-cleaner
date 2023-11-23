# Cookie domain cleaner &nbsp; [![Latest Stable Version](https://img.shields.io/badge/version-1.0.0-pink)](https://packagist.org/packages/blackbird/cookie-domain-cleaner) [![License: MIT](https://img.shields.io/github/license/blackbird-agency/cookie-domain-cleaner.svg)](./LICENSE)

This Magento 2 module will clean cookies from parent domains gradually while navigating on the site on a subdomain.

Its main purpose is to allow the hosting of several environments of a site on the same domain name while avoiding cookie conflicts with subdomains.

For instance, when navigating on `subdomain.my-site.com`, each time a cookie is defined, it will be deleted from `my-site.com` if there are duplicates.

## Installation

```bash
composer require blackbird/cookie-domain-cleaner
```

```bash
php bin/magento setup:upgrade
```

## Alternatives

There are 3 ways to solve similar issues :

- Install this module
- Add a subdomain (e.g. `www`) for the production environment
- Rename the `PHPSESSID` with an unique name for each environment
