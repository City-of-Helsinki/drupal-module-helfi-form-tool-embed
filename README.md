# Form tool embed module

This module integrates From Tool made forms into any Drupal site. It creates nodetype, paragraph & field configurations
 and is meant to be a drop in solution for embedding forms to Drupal from form tool..

Environment variables:

FORM_TOOL_HOST -> Base URL of the form tool backend. Used for form metadata & embedding

FORM_TOOL_TOKEN -> APIkey for authenticating to form tool backend. Token is sent via HTTP header (X-Auth-Token)

## Installation
Like any Drupal module, make sure you have environment variables set.

Module provides field type that can be used wherever fields can be used.

## Configuring
Add field to content type, in content form, select form you want to embed. The form should be configured in FormTool for embedding.

## Status / TODO / Notes

- So far my Docker <-> Docker connections are broken so there is hard coded form config for local testing. But If connection to backend is up, then that data is used. See EmbedFormWidget.
- Develop branch has only the field setup, there is a branch for nodetype & paragraph as well. But this bare bones seems to be preferred way.
- The login form needs some work, the stripping used relied on custom node type, now it's hard to figure out when the login form is used in place of the iframe. See helfi_formtool_embed.module.
- 