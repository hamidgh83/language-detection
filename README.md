### The package

[![Version](https://poser.pugx.org/hamidgh83/language-detection/version)](//packagist.org/packages/hamidgh83/language-detection)
[![Total Downloads](https://poser.pugx.org/hamidgh83/language-detection/downloads)](//packagist.org/packages/hamidgh83/language-detection)
[![Dependents](https://poser.pugx.org/hamidgh83/language-detection/dependents)](//packagist.org/packages/hamidgh83/language-detection)
[![License](https://poser.pugx.org/hamidgh83/language-detection/license)](//packagist.org/packages/hamidgh83/language-detection)

# language-detection
ُThis is an extension of [language-detection](https://github.com/patrickschur/language-detection) where you can find its documentation. 

This library can detect the language of a given text string.
It can parse given training text in many different idioms into a sequence of [N-grams](https://en.wikipedia.org/wiki/N-gram) and builds a database file in JSON format to be used in the detection phase.
Then it can take a given text and detect its language using the database previously generated in the training phase.
The library comes with text samples used for training and detecting text in 110 languages.

## Installation with Composer
> **Note:** This library requires the [Multibyte String](https://secure.php.net/manual/en/book.mbstring.php) extension in order to work. 

```bash
$ composer require hamidgh83/language-detection
```

## Basic Usage
Basic usage of this library is the same as original library but the following methods has beed added:

<hr style="background-color:#666"/>

#### `bestResult()`
Returns the best result as a string.
```php
$ld->detect('Mag het een onsje meer zijn?')->bestResult();
```
Result:
```text
string(2) "nl"
```
<hr style="background-color:#666"/>

#### `getSupportLanguages()`
This method returns a list if supportted languages as an array.
```php
$ld->getSupportLanguages();
```
Result:
```text
Array
(
    0 => ab
    1 => af
    2 => am
    3 => ar
    4 => ay
    5 => az-Cyrl
    6 => az-Latn
    7 => be
    8 => bg
    9 => bi
    10 => bn
    11 => bo
    12 => br
    [...]
)
```
<hr style="background-color:#666"/>

#### getLanguageProps($lang)
Every language has their own properties and you can get the it as follow:
```php
$lng    = new Language();
$result = $lng->detect('This is an example text.');
$props  = $lng->getLanguageProps($result->bestResult());
```
Result:
```text
Array
(
    'props' => Array(
        'language' => 'English',
        'language' => Array (
            'United Kingdom',
            'United States of America'
        ),
        'direction' => 'ltr'
    )
)
```
<hr style="background-color:#666"/>

## Supported languages
The library currently supports 110 languages.
To get an overview of all supported languages please have a look at [here](resources/README.md).
