<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => '1.0.0+no-version-set',
    'version' => '1.0.0.0',
    'aliases' => 
    array (
    ),
    'reference' => NULL,
    'name' => 'log1x/nofollow-pretty-links',
  ),
  'versions' => 
  array (
    'illuminate/collections' => 
    array (
      'pretty_version' => 'v8.13.0',
      'version' => '8.13.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e61e796ff98e19eac5e6735ea6abfa66f4a2f6db',
    ),
    'illuminate/contracts' => 
    array (
      'pretty_version' => 'v8.13.0',
      'version' => '8.13.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c23428149639d5257916ad10d390cdbeffe4d504',
    ),
    'illuminate/macroable' => 
    array (
      'pretty_version' => 'v8.13.0',
      'version' => '8.13.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '300aa13c086f25116b5f3cde3ca54ff5c822fb05',
    ),
    'log1x/nofollow-pretty-links' => 
    array (
      'pretty_version' => '1.0.0+no-version-set',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
    ),
    'psr/simple-cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '408d5eafb83c57f6365a3ca330ff23aa4a5fa39b',
    ),
    'squizlabs/php_codesniffer' => 
    array (
      'pretty_version' => '3.5.8',
      'version' => '3.5.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '9d583721a7157ee997f235f327de038e7ea6dac4',
    ),
    'symfony/dom-crawler' => 
    array (
      'pretty_version' => 'v5.1.8',
      'version' => '5.1.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '0969122fe144dd8ab2e8c98c7e03eedc621b368c',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f4ba089a5b6366e453971d3aad5fe8e897b37f41',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '39d483bdf39be819deabf04ec872eb0b2410b531',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e70aa8b064c5b72d3df2abd5ab1e90464ad009de',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
