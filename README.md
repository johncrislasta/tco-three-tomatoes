This plugin provides a Calendar on the backend of WordPress, and a sliding form for the plated meal orders on the front end.

**Requirements**
1. WP Version 5.6 above
2. Advanced Custom Fields plugin.
3. Custom Post Type UI plugin.

**Installation.**
1. Pull files into /wp-content/plugins/
2. On the console, type the command: `npm install`

   This will provide an scss compiler `gulp` that will compile all /assets/sass/ files into a minified version at /assets/css
3. From the wp-admin, find from the side menu the CPT UI > Tools, copy and paste the contents from the included file **cpt-import.json** in to the Import field, and press the "Import" button
4. From the wp-admin still, go to Custom Fields > Tools, and on the Import Field Group, upload the included import file `acf-export-2022-07-01.json` and press the "Import File" button.
