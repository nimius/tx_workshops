# tx_workshops
A TYPO3 extension to manage workshops (or events, seminars, ...).

[![Build Status](https://travis-ci.org/nimius/tx_workshops.svg)](https://travis-ci.org/nimius/tx_workshops)

## What does it do?
tx_workshops provides functionality for managing and displaying workshops, and ships with signup functionality.

This extension and its features are developed on demand. If you're missing a feature, we'd be happy to accept some code for it. Alternatively, contact us if you'd like to sponsor a feature and we'll happily integrate it for you.

## Installation
Workshops requires some basic TypoScript to work properly. Include the static extension template into your template and you're set. If you don't include the static TypoScript, TYPO3 will not recognize the table mappings required for tx_workshops to work.

## Configuration

### Feature toggling
In order to keep everything as minimalistic as possible, advanced features are disabled by default. Have a look at the extension configuration inside the extension manager to enable/disable features.

### Registration field validations
The only validation which is always run on registrations is extbase's `EmailAddressValidator`. If you need more validations, you're free to configure them through TypoScript. Example:

	plugin.tx_workshops.settings.registration.validation {
		firstName {
			10.validator = NotEmpty
			
			20.validator = StringLength
			20.options.minimum = 5
		}
        
        additionalFields {
            membershipCode {
                10.validator = NotEmpty
            }
        }
	}

A list of validators together with valid options for them is available in the [API documentation](https://typo3.org/api/typo3cms/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_validation_1_1_validator_1_1_boolean_validator.html).

### Email delivery
Delivering an email takes some time. In order to optimize frontend response times on e.g. registrations, emails can be delivered through a scheduler task / cron job in the background. For this, you have to set the according TypoScript configuration option. If this is an issue for you or for your hosting provider (sadly, this can be the case), you should configure workshops to deliver directly. Have a look at the TypoScript file inside Configuration/TypoScript, which lists all possible settings.

Attention: Have a look at the issues, as there is currently a problem when delivering emails through cron when using multiple languages.

### iCalendar export (ICS)
To provide an ICS file containing workshop dates, set up a separate PAGE TLO responding to a certain `typeNum`, and call the export plugin there. An example:

	icsExport = PAGE
	icsExport {
		config {
			disableAllHeaderCode = 1
			xhtml_cleaning = none
			admPanel = 0
			metaCharset = utf-8
			additionalHeaders = Content-Type:text/calendar;charset=utf-8
			disablePrefixComment = 1
		}
		
		typeNum = 1234
		
		10 = USER
		10 {
			userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
			vendorName = NIMIUS
			extensionName = Workshops
			pluginName = Exports
		}
	}

Don't forget to have the following configuration parts set in your TypoScript template:

	plugin.tx_workshops.settings.export.iCalendar {
		// These two values are used as PRODID, which is a required property.
		businessName = Your business name
		productName = Your product name
	}
	
	// The 2-char language code is also required for a valid PRODID
	config.language = en

Requesting a page with `?type=1234` will now respond with an ICS file containing all upcoming dates. You can also pass `workshop` to it to narrow down results. Example:

	<f:link.action pluginName="Exports" controller="Exports" action="iCalendar" arguments="{workshop: workshop}" additionalParams="{type: 1234}">ICS</f:link.action>

#### Tasks
In order to deliver confirmation emails, make sure to have a working scheduler, and register the appropriate extbase tasks. The following tasks are currently provided:

 * notification:registrationConfirmationCommand - Delivers notification mails to workshop attendees.

#### Templates
Email templates are freely configurable through fluid, you'll find them in Resources/Private/Templates/Notifications.

#### Email configuration
Make sure that your TYPO3 instance is properly configured for email delivery. Check your install tool / `LocalConfiguration.php` for valid information in the `[MAIL]` section. There's also a report module checking for this. Alternatively, you can set required information for delivery through TypoScript. 

## Credits
Developed and maintained by [NIMIUS](http://www.nimius.net)

This extension was initially developed for TYPO3 v3.6 by Stefan Padberg, who handed the key off to us for future development.