# Dhii - Regex Abstract

[![Build Status](https://travis-ci.com/Dhii/regex-abstract.svg?branch=develop)](https://travis-ci.com/Dhii/regex-abstract)
[![Code Climate](https://codeclimate.com/github/Dhii/regex-abstract/badges/gpa.svg)](https://codeclimate.com/github/Dhii/regex-abstract)
[![Test Coverage](https://codeclimate.com/github/Dhii/regex-abstract/badges/coverage.svg)](https://codeclimate.com/github/Dhii/regex-abstract/coverage)
[![Latest Stable Version](https://poser.pugx.org/dhii/regex-abstract/version)](https://packagist.org/packages/dhii/regex-abstract)
[![Latest Unstable Version](https://poser.pugx.org/dhii/regex-abstract/v/unstable)](https://packagist.org/packages/dhii/regex-abstract)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

## Details
Abstract functionality for working with regular expressions

### Traits
- [`QuoteRegexCapablePcreTrait`] - Escapes a string such that it is interpreted literally by the PCRE engine.
- [`GetAllMatchesRegexCapablePcreTrait`] - Gets all matches which correspond to a given PCRE expression from a string.
Throws an exception if matching failed.


[Dhii]: https://github.com/Dhii/dhii

[`QuoteRegexCapablePcreTrait`]:                     src/QuoteRegexCapablePcreTrait.php
[`GetAllMatchesRegexCapablePcreTrait`]:             src/GetAllMatchesRegexCapablePcreTrait.php
