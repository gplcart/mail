[![Build Status](https://scrutinizer-ci.com/g/gplcart/mail/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gplcart/mail/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gplcart/mail/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gplcart/mail/?branch=master)

Mail is a [GPL Cart](https://github.com/gplcart/gplcart) module that offers more secure and modern than PHP mail() method to send store emails. By enabling this module all your emails will be sent via an SMTP server, e.g Gmail. Based on the [PHPMailer](https://github.com/PHPMailer/PHPMailer) library


**Installation**

1. Download and extract to `system/modules` manually or using composer `composer require gplcart/mail`. IMPORTANT: If you downloaded the module manually, be sure that the name of extracted module folder doesn't contain a branch/version suffix, e.g `-master`. Rename if needed.
2. Go to `admin/module/list` end enable the module
3. Enter SMTP server details and enable the module on `admin/module/settings/mail`