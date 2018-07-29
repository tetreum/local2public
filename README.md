# local2public
Have an updated subdomain pointing to your local network cloudflare + ipfy.

This cron will request your router's ip every 5min and update the cloudflare's dns record of your subdomain whenever it changes.

Sort of afraid.org/no-ip.com alternative.

Example:

You have a domain called facebook.com and a linux server in your house that you want to access remotely.

By creating a subdomain, for example myhome.facebook.com and running this script in your server, myhome.facebook.com will be always pointing to your router's ip no matter if you have dynamic ip at home.

# Requirements
- php

# Setup

1. Create the subdomain entry in cloudflare. It must be Type: A & TTL: 2min or the lowest one possible.
2. Go to cloudflare and grab your (global) api key: https://dash.cloudflare.com/profile
3. Rename `config.demo.php` to `config.php`
4. Edit the config.php data
5. Setup the cronjob, ex: `*/5 * * * * php /PATH_TO/cron.php >/dev/null 2>&1`
