<<<<<<< HEAD
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
=======
# Prewtex
This is a small extension that adds [nette/tracy](https://github.com/nette/tracy) into PrestaShop.

## How to use?
1. Clone this repository to ``` /prewtex/ ``` in root folder of your PrestaShop
2. Edit your file ``` index.php ``` in root folder
3. Copy [Db.php](https://bitbucket.org/radekhubner/prewtex/src/0d3fead93a6c/Db.php) to ``` /override/classes/db/ ```
4. Delete ``` /cache/class_index.php ```

### How to edit ``` index.php ``` in root?
```
$approvedIPs = ["216.58.211.36"]; // Array or string, no required
$emails = "mail@example.com" // Must be a string, no required
require __DIR__ . "/prewtex/bootstrap.php";

// Your code here

\Tracy\Debugger::getBar()->addPanel($dbDiagnostics);
```
>>>>>>> 9e13320853ca7c9d5ea3387a8b47989e85b62d7a
