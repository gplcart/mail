[![Build Status](https://scrutinizer-ci.com/g/gplcart/mail/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gplcart/mail/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gplcart/mail/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gplcart/mail/?branch=master)

Mail is a [GPL Cart](https://github.com/gplcart/gplcart) module that offers more secure and modern than PHP mail() method to send store emails. By enabling this module all your emails will be sent via an SMTP server, e.g Gmail. Based on the [PHPMailer](https://github.com/PHPMailer/PHPMailer) library

**Installation**

This module contains 3-d party files which should be downloaded separately. You must use [Composer](https://getcomposer.org) to download all the required dependencies and create a class autoloader file.

*Download manually:*

1. Download and extract the archive to `system/modules`.
2. Remove branch/version from the module folder name
3. `cd` to the module directory and install dependencies: `composer install`

*or using Composer:*

1. `cd` to webroot directory (i.e where is `index.php`) then `composer require gplcart/mail`

*Once all files in place:*

1. Go to `admin/module/list` end enable the module
2. Enter SMTP server details and enable the module on `admin/module/settings/mail`