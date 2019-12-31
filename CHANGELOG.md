# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.3.0 - 2017-08-24

### Added

- [zfcampus/zf-configuration#22](https://github.com/zfcampus/zf-configuration/pull/22) adds support for
  laminas-config v3 releases.

- [zfcampus/zf-configuration#23](https://github.com/zfcampus/zf-configuration/pull/23) adds support for
  PHP 7.1 and the upcoming 7.2 release.

### Deprecated

- Nothing.

### Removed

- [zfcampus/zf-configuration#23](https://github.com/zfcampus/zf-configuration/pull/23) removes support
  for HHVM.

### Fixed

- Nothing.

## 1.2.2 - TBD

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.2.1 - 2016-08-13

### Added

- [zfcampus/zf-configuration#19](https://github.com/zfcampus/zf-configuration/pull/19) adds the ability
  to enable usage of `::class` notation in generated configuration via a
  configuration setting, `api-tools-configuration.class_name_scalars`.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zfcampus/zf-configuration#19](https://github.com/zfcampus/zf-configuration/pull/19) fixes a syntax
  error in the `ConfigResourceFactory`.

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
