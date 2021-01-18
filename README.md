# TYPO3 Salesforce Audience Studio Adapter

This TYPO3 extension provides an adapter for the [marketing_automation](https://github.com/Leuchtfeuer/typo3-marketing-automation)
to allow targeting users with personalised content.

To achieve that, users and segments from Audience Studio are imported from an Amazon S3 export,
which can be configured in Audience Studio.

## Installation

`composer require leuchtfeuer/typo3-audience-studio` 
Don't forget to activate the extension in the TYPO3 Extension Manager.

## Configuration

### Adding the [Control Tag](https://konsole.zendesk.com/hc/en-us/articles/215557298-Control-Tag-Implementation-Guide) and cookie configuration

This adapter does not take care of adding the control tag to the HTML output of your website.
Make sure to add it and put the following TYPO3 configuration into AdditionalConfiguration.php or any other appropriate place.

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['cookieName'] = 'yourcookiename';
```

Verify that a cookie with the name declared in the configuration is set for your website domain.
The adapter relies on this cookie to be present and set.

#### Get cookie value from local storage

Since the AudienceStudio cookie is not set on the domain where your website is available, the cookie value must be read from the 
user's local storage. You can store the key of the local storage value in the configuration `localStorageKey`:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['localStorageKey'] = 'yourlocalstoragekey';
```

In this case, the following lines of JavaScript will be added to the source code:

```js
(function() {
    if (window.localStorage) {
        const KUID = localStorage.getItem('YOUR_LOCAL_STORAGE_KEY');
        if (KUID !== null) {
            let date = new Date();
            date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
            document.cookie = 'YOUR_COOKIE_NAME=' + KUID + ';expires=' + date.toUTCString() + ';path=/';
        }
    }
})();
```

You can also add this script manually. Then you can just leave the configuration empty and the extension will not add the 
JavaScript to your page.

### S3 Access configuration

Put the following TYPO3 configuration into AdditionalConfiguration.php or any other appropriate place.

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['storageConfiguration'] = [
    'key' => 'your S3 key',
    'secret' => 'your corresponding S3 secret',
    'region' => 'us-east-1', // Must be the correct region of your bucket
    'bucket' => 'your/bucket',
];
```

Import users and segments by using the following CLI command that comes with the extension:

```bash
vendor/bin/typo3 audience-studio:import
```

It is recommended to add this command as TYPO3 Scheduler task to be executed daily.

### Persona configuration

Create *Persona* records as required in any system folder in the page tree.
Each *Persona* is required to be associated with one or more Audience Studio segments.
A *Persona* is matched when a user visits the website that is part of any segement selected in the *Persona* record.

### Target content

By default the Marketing Automation extension allows targeting content and page records.
Additional records can be optionally be activated for targeting.

Targeting is based on *Personas*. In the Access tab of the content or page record, select the *Persona* for which
this record should be shown. 
