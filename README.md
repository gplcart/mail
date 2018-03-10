[![Build Status](https://scrutinizer-ci.com/g/gplcart/mail/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gplcart/mail/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gplcart/mail/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gplcart/mail/?branch=master)

Mail is a [GPL Cart](https://github.com/gplcart/gplcart) module that offers more secure and modern than PHP mail() method to send store emails. By enabling this module all your emails will be sent via an SMTP server, e.g Gmail. Based on the [PHPMailer](https://github.com/PHPMailer/PHPMailer) library

**Installation**

This module requires 3-d party library which should be downloaded separately. You have to use [Composer](https://getcomposer.org) to download all the dependencies.

1. From your web root directory: `composer require gplcart/mail`. If the module was downloaded and placed into `system/modules` manually, run `composer update` to make sure that all 3-d party files are presented in the `vendor` directory.
2. Go to `admin/module/list` end enable the module
3. Enter SMTP server details and enable the module on `admin/module/settings/mail`