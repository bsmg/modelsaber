# ModelSaber
Repository for Beat Saber Avatars, Sabers, Platforms, and Bloqs

## Setting up the site
The site requires a bit of setup.

- The file [resources/fetcher.js](resources/fetcher.js) requires the URLs in the fetcher() function to be changed from localhost.
- The [settings.txt](settings.txt) file needs to be configured with the right settings 
(if you don't have this file, duplicate [settings.example.txt](settings.example.txt) and rename it to settings.txt).
- The file [resources/manifest/manifest.json](resources/manifest/manifest.json) needs to have it's **start_url** property changed to whatever 
you web root is set to in the [settings.txt](settings.txt) file.

## Documentation
When developing for this project it would make everybody's day easier if you followed the coding standards used below.

[PHPDOC](https://www.phpdoc.org/) is used for php documentation, the syntax used will be shown below.

### General
There are a few things that are common between all instances, these are shown here.

- Always end the short and long descriptions with a period.
- Always have an empty line between the short description and the long description.
- Always have an empty line between the descriptions and the tags (@param, @return, etc.).

### Methods and functions
Methods and functions should use this syntax for everything, simple getters and setters are optional however.
```php
/**
 * <A short description of the function.>
 *
 * <An optional longer description if required.>
 *
 * @param <type> <$name> <an optional description.>
 * @return <type> <an optional description.>
 */
```

### Classes
Classes use a slightly different approach than methods and functions, this is shown below.
```php
/**
 * <A general description of the class.>
 *
 * @author <name or username> <an optional email>
 * @version V<version number as an integer>
 */
```

## Credits
Original developer: [Assistant](https://bs.assistant.moe/Donate/)
V2 developer: [laugexd](https://twitter.com/laugexd)