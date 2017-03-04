# Change Log

## 1.1.0 / 2017-03-04
### Added
- Function: count
- (private) Function: err_exception

### Changed
- Function rename: '_backtick' ~ 'backtick', and code improvements
- Function rename: '_cWhere' ~ 'cWHERE', and code improvements
- Code improvements in function: cSQL

### Removed
- Support for prefix and suffix
- Support for selecting all columns with 'all'
- Function: createTable
- (private) Function: _PDOException
- File: examples\select.php

## 1.0.1 / 2016-09-22
### Added
- File CHANGELOG.md
- File examples/connections.php
- Prefix and suffix for columns names
- Function _buildDSN()

### Changed
- Connection with drivers PDO are now generic

### Removed
- Function _setSettings()
- Function init()
- File dist/YSPDO-Structure.txt
