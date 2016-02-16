Console Configuration Application
========================

[![License](http://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](http://RaphaelBronsveld.mit-license.org)
  
**Symfony** console application to modify a certain config file.

#### Examples
```
php raaftisan config test.database --get 
// Returns value for given key at file test.php in config dir.

php raaftisan config app.database --set 'mysql2'
// Sets new value by given key at file app.php in config dir.

php raaftisan config 'filename'.'key' --set 'value'
```

## This is still Work in Progress.

### Copyright/License
Copyright 2016 [Raphael Bronsveld](https://github.com/RaphaelBronsveld) - [MIT Licensed](http://RaphaelBronsveld.mit-license.org) 