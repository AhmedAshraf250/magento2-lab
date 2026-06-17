```bash
bin/magento config:set admin/security/session_lifetime 86400
bin/magento cache:flush
```

```bash
bin/magento module:disable Magento_AdminAdobeImsTwoFactorAuth Magento_TwoFactorAuth
bin/magento setup:upgrade
bin/magento cache:flush
bin/magento setup:di:compile
```

```bash
bin/magento cache:status
bin/magento cache:flush
bin/magento cache:enable
bin/magento cache:disables
```

```bash
bin/magento deploy:mode:show
bin/magento deploy:mode:set developer # (default,production)
```
