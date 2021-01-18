.. include:: ../Includes.txt

.. _configuration:

=============
Configuration
=============

.. _installation-controlTagAndCookies:

Control Tag and Cookies
=======================

This adapter does not take care of adding the control tag to the HTML output of your website. Make sure to add it and put the
following TYPO3 configuration into :php:`AdditionalConfiguration.php` or any other appropriate place.

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['cookieName'] = 'yourcookiename';

Verify that a cookie with the name declared in the configuration is set for your website domain. The adapter relies on this
cookie to be present and set.

More information about the control tag is available in the
`Audience Studio documentation <https://konsole.zendesk.com/hc/en-us/articles/215557298-Control-Tag-Implementation-Guide>`__.

.. _installation-controlTagAndCookies-cookieValueFromLocalStorage:

Cookie Value from Local Storage
-------------------------------

Since the AudienceStudio cookie is not set on the domain where your website is available, the cookie value must be read from the
user's local storage. You can store the key of the local storage value in the configuration `localStorageKey`:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['localStorageKey'] = 'yourlocalstoragekey';

In this case, the following lines of JavaScript are written in the source code:

.. code-block:: js

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

You can also add this script manually. Then you can just leave the configuration empty and the extension will not add the
JavaScript to your page.

.. _installation-s3AccessConfiguration:

S3 Access Configuration
=======================

Put the following TYPO3 configuration into AdditionalConfiguration.php or any other appropriate place.

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['storageConfiguration'] = [
       'key' => 'your S3 key',
       'secret' => 'your corresponding S3 secret',
       'region' => 'us-east-1', // Must be the correct region of your bucket
       'bucket' => 'your/bucket',
   ];

Import users and segments by using the following CLI command that comes with the extension:

.. code-block:: bash

   vendor/bin/typo3 audience-studio:import

It is recommended to add this command as TYPO3 Scheduler task to be executed daily.

.. _configuration-personaConfiguration:

Persona Configuration
=====================

Open the extension manager module of your TYPO3 instance and select "Get Extensions" in the select menu above the upload
button. There you can search for `audience studio` and simply install the extension. Please make sure you are using the latest
version of the extension by updating the extension list before installing the Audience Studio extension.

.. _configuration-targetContent:

Target Content
==============

By default the Marketing Automation extension allows targeting content and page records. Additional records can be optionally be
activated for targeting.

Targeting is based on Personas. In the Access tab of the content or page record, select the Persona for which this record should
be shown.
