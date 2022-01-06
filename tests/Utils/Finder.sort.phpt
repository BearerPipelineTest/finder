<?php

/**
 * Test: Nette\Utils\Finder sorting.
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function export($iterator)
{
	$arr = [];
	foreach ($iterator as $key => $value) {
		$arr[] = strtr($key, '\\', '/');
	}

	return $arr;
}


test('basic', function () {
	$finder = Finder::find('*')
		->from('files')
		->sortByName();

	Assert::same([
		'files/file.txt',
		'files/images',
		'files/images/logo.gif',
		'files/subdir',
		'files/subdir/file.txt',
		'files/subdir/readme',
		'files/subdir/subdir2',
		'files/subdir/subdir2/file.txt',
	], export($finder));

	$finder->childFirst();
	Assert::same([
		'files/file.txt',
		'files/images/logo.gif',
		'files/images',
		'files/subdir/file.txt',
		'files/subdir/readme',
		'files/subdir/subdir2/file.txt',
		'files/subdir/subdir2',
		'files/subdir',
	], export($finder));
});


test('and', function () {
	$finder = Finder::find('*')->in('files/subdir')
		->and()
		->files('*')->in('files/images')
		->sortByName();

	Assert::same([
		'files/images/logo.gif',
		'files/subdir/file.txt',
		'files/subdir/readme',
		'files/subdir/subdir2',
	], export($finder));
});
