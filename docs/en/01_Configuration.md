# Silverstripe Sendy - Configuration

## Sendy Credentials

In order to actually send campaigns, you need to configure your sendy instance
for this module:

```yml
Syntro\SilverStripeSendy\Connector:
  installation_url: 'https://sendy.yourdomain.com'
  api_key: 'XXXXXXXXXXXXXX'
  brand_id: 1
```

## Default Values

If your name and email stays the same and you do not want to always
repeat them, configure them as defaults:

```yml
Syntro\SilverStripeSendy\Model\SendyCampaign:
  defaults:
    FromName: Your Name
    FromEmail: helly@yourdomain.ch
```
