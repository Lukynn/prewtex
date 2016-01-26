# Prewtex #

* [Learn Markdown](https://bitbucket.org/tutorials/markdowndemo)

### How do I get set up? ###
1. Clone this repository to */prewtex/* in root folder of your PrestaShop
2. Add these two rows to start of file *index.php* in root folder:
```
#!php
$approvedIPs = ["216.58.211.36"];
require __DIR__ . "/prewtex/bootstrap.php";
```
3. Copy [Db.php](https://bitbucket.org/radekhubner/prewtex/src/0d3fead93a6c/Db.php) to */override/classes/db/*
4. Delete */cache/class_index.php*