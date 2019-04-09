# autolink-plugin
To install

Step 1: Use composer require saurabhd/silverstripe-plugin
Step 2: Use dev/build?flush=all
Step 3: Add below code in _config.php file.

use saurabhd\silverstripeplugin\AutolinkSearch;
AutolinkSearch::AutolinkDiff();
