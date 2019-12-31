# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.3.3 - 2018-05-07

### Added

- [zfcampus/zf-configuration#32](https://github.com/zfcampus/zf-configuration/pull/32) adds support for PHP 7.2.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.3.2 - 2017-11-14

### Added

- Nothing.

### Changed

- [zfcampus/zf-configuration#29](https://github.com/zfcampus/zf-configuration/pull/29) reverts the
  changes from 1.3.1, as we discovered they were backwards-incompatible with how
  api-tools-admin utilizes the component. We will re-introduce them for a new
  major release.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.3.1 - 2017-11-01

### Added

- Nothing.

### Changed

- [zfcampus/zf-configuration#25](https://github.com/zfcampus/zf-configuration/pull/25) changes the
  behavior of `ConfigResource::patchKey()` to do what it is advertised to do:
  merge incoming configuration. Previously, it was overwriting configuration,
  which could in some extreme instances lead to lost configuration. The behavior
  is now correct.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

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
