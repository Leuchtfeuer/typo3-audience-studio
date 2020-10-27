# TYPO3 Salesforce Audience Studio Adapter

This TYPO3 extension provides an adapter for the [marketing_automation](https://github.com/Leuchtfeuer/typo3-marketing-automation)
to allow targeting users with personalised content.

To achieve that, users and segments from Audience Studio are imported from an Amazon S3 export,
which can be configured in Audience Studio.

## Installation

`composer require leuchtfeuer/typo3-audience-studio` 
Don't forget to activate the extension in the TYPO3 Extension Manager.

## Configuration

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
