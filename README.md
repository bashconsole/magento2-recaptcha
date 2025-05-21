# Magento2 reCAPTCHA module
# Alexej Zubovsky (bashconsole@gmail.com)


## Install


$ composer config repositories.bashconsole vcs https://github.com/bashconsole/magento2-recaptcha/

$ composer config minimum-stability dev

$ composer require bashconsole/magento2-recaptcha

$ composer config minimum-stability alpha

$ composer update

$ magento module:enable Bashconsole_ReCaptcha

$ magento setup:upgrade

$ magento setup:static-content:deploy

$ magento setup:di:compile
