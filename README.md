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
