# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.2.0 - 2016-07-13

### Added

- [zfcampus/zf-configuration#17](https://github.com/zfcampus/zf-configuration/pull/17) adds support for v3
  releases of Laminas components, while retaining compatibility with v2
  releases.
- [zfcampus/zf-configuration#17](https://github.com/zfcampus/zf-configuration/pull/17) extracts all
  factories previously defined inline in the `Module` class into their own classes:
  - `Laminas\ApiTools\Configuration\ConfigResourceFactory`
  - `Laminas\ApiTools\Configuration\ConfigWriterFactory`
  - `Laminas\ApiTools\Configuration\ModuleUtilsFactory`
  - `Laminas\ApiTools\Configuration\ResourceFactoryFactory`

### Deprecated

- Nothing.

### Removed

- [zfcampus/zf-configuration#17](https://github.com/zfcampus/zf-configuration/pull/17) removes support
  for PHP 5.5.

### Fixed

- Nothing.
