# WP Starter Plugin

Just another Wordpress Starter Plugin using namespaces.

## Installation

After installation, use the search and replace function (case sensitive) to change the following:

- Starter Plugin -> your plugin's name
- Starter_Plugin -> your plugin's namespace (underscores and hyphens, proper case)
- starter_plugin -> your plugin's name (underscores and hyphens, lowercase)
- starter-plugin -> your plugin's name as a slug or text domain (hyphens, lowercase)

You must also renamed <code>wp-starter-plugin.php</code> to your plugin's filename. Remember that this file's name must match your plugin's filename and should be unique.

You will also find starter examples in the <code>settings.php</code> file. Delete or replace them as needed.

## Model-View-Controller

WP Starter Plugin is based on the model-view-controller pattern: models are meant to represent entities and interact with the database, views are meant to display data, and controllers are meant to interact between the two. In this starter, a Base Model is provided and must be extended to produce working models. No views or controllers are provided. For more information: https://developer.mozilla.org/fr/docs/Glossary/MVC

## Database Table Creation

The <code>db.php</code> file contains basic instructions on how to create database tables upon plugin activation. Remember to uncomment the <code>register_activation_hook</code> method call in <code>wp-starter-plugin.php</code> and to modify the <code>queries</code> property of the DatabaseTables class (the <code>tag</code> table is provided as an example).
