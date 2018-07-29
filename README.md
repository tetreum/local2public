# local2public
Have an updated subdomain pointing to your local network cloudflare + ipfy.

This cron will request your router's ip every 5min and update the cloudflare's dns record of your subdomain whenever it changes.

# Requirements
- php

# Setup

1. Create the subdomain entry in cloudflare. It must be Type: A & TTL: 2min or the lowest one possible.
2. Go to cloudflare and grab your (global) api key: https://dash.cloudflare.com/profile
3. Rename `config.demo.php` to `config.php`
4. Edit the config.php data
5. Setup the cronjob, ex: `*/5 * * * * php /PATH_TO/cron.php >/dev/null 2>&1`