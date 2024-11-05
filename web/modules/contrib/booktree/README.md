# Booktree

## Description

The module allow site administrator to draw a simple
tree presentation of one or more book.



## Requirements

This module requires Drupal 8.x or a later version.


## Installation

### Manual copy (old)

1) Copy/upload the folders to the web/modules/cpntrib directory of your
   Drupal installation.
2) Enable the modules in Drupal (administer -> modules).
3) Configure the root of main tree in /admin/settings/booktree

### composer
1) composer require drupal/booktree

## Advanced use


If you want draw many book or different part of the same book you can use
this path sintax:

`/booktree/ID of root node/[deep]/[max length]`


Ex:
  - http://mysite.com/?q=booktree/1834/20/50
  - http://mysite.com/?q=booktree/1834/20/
  - http://mysite.com/?q=booktree/1834/

or with Clean Urls
  - http://mysite.com/booktree/1834/20/50
  - http://mysite.com/booktree/1834/20/
  - http://mysite.com/booktree/1834/


## Author
Uccio  (https://uccio.org)




