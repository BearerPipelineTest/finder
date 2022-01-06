<?php

/**
 * Test: Nette\Utils\Finder browsing PHAR.
 *
 * @phpIni phar.readonly=0
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$pharFile = __DIR__ . '/test.phar';

$phar = new Phar($pharFile);
$phar['a.php'] = '';
$phar['b.php'] = '';
$phar['sub/c.php'] = '';
unset($phar);

Assert::true(is_file($pharFile));
Phar::loadPhar($pharFile, 'test.phar');


test('from()', function () {
	$finder = Finder::findFiles('*')
		->from('phar://test.phar');

	Assert::same([
		'phar://test.phar\a.php',
		'phar://test.phar\b.php',
		'phar://test.phar\sub\c.php',
	], array_keys($finder->toArray()));
});

test('files()', function () {
	$finder = Finder::findFiles('phar://test.phar/*');

	Assert::same([
		'phar://test.phar\a.php',
		'phar://test.phar\b.php',
	], array_keys($finder->toArray()));
});
