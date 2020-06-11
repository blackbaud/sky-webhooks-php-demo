# SKY Webhooks Demo (PHP)

PHP version of the [SKY Developer Webhooks Tutorial](https://developer.blackbaud.com/skyapi/apis/webhook/tutorial).

This code sample handles three scenarios:

1. The `OPTION` request necessary to validate a subscription registration.
2. The `POST` request when [real webhook events](https://developer.blackbaud.com/skyapi/apis/webhook/event-types) or [the test payload](https://developer.sky.blackbaud.com/docs/services/webhook/operations/SendTestPayloadToSubscription) are delivered.  These requests are stored in a local `handler/results.json` file.
3. All other requests display the contents of the `handler/results.json` file.  This is for debugging purposes only.

## Usage

- Replace the value for `WEBHOOK_SECRET_KEY` in the `index.php` with a unique value for you.
- Host this repo on the webserver of your choice with PHP support.
- Use your webserver host URL + `/handler/?key=[WEBHOOK_SECRET_KEY]` when you register your webhook subscription.
- Trigger a webhook event.
- Visit your webserver host URL + `/hander/` to see any events that were delivered.

## Debugging

This code has been tested on a wide variety of hosts supporting PHP, including but not limited to the following:

- "Shared" hosting providers including HostGator and Elevent2.
- Local Apache instance setup with ModSecurity using vagrant.
- Azure, AWS, and Digital Ocean.

As a note, we recommend extreme caution using shared hosting providers for any serious production workloads.

Last, a stock installation of ModSecurity is known to block the `content-type` of `application/cloudevents+json`.  This will typically result in a 403 or 406 HTTP error.  Given the somewhat recent push for this schema, our current plan is to continue to require it.

Many hosting providers expose ModSecurity configuration options.  While disabling completely is a possibility, we hope that to be an absolute last resort.  We found that even those that do not expose configuration, their support staff were very accommodating in making changes. 

On a personal note, this actually happend with us. The very knowledgable support staff at HostGator understood our problem and very promptly whitelisted the `content-type` in about 5 minutes for their entire fleet.

## Questions

Please do not hesitate to reach out via the [Blackbaud Community](https://community.blackbaud.com/developer) with any questions you may have.