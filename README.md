Console Configuration Application
========================

[![License](http://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](http://RaphaelBronsveld.mit-license.org)
  
**Symfony** console application to modify a certain config file.

#### Examples
```
php raaftisan config test 
// Will return array at test.php

php raaftisan config test.1
// Will return 'secret'

php raaftisan config test.database mysql3
// Sets new value by given key at file app.php in config dir.

php raaftisan config 'filename'.'key' --set 'value'
```

### Copyright/License
Copyright 2016 [Raphael Bronsveld](https://github.com/RaphaelBronsveld) - [MIT Licensed](http://RaphaelBronsveld.mit-license.org) 