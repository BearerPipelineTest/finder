<?php

/**
 * Test: Nette\Utils\Finder basic usage.
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function export($iterator, bool $sort = true)
{
	$arr = [];
	foreach ($iterator as $key => $value) {
		$arr[] = strtr($key, '\\', '/');
	}

	if ($sort) {
		sort($arr);
	}
	return $arr;
}


test('non-recursive file search', function () {
	$finder = Finder::findFiles('file.txt')->in('files');
	Assert::same(['files/file.txt'], export($finder));
});


test('non-recursive file search alt', function () {
	$finder = (new Finder)->files('file.txt')->in('files');
	Assert::same(['files/file.txt'], export($finder));
});


test('recursive file search', function () {
	$finder = Finder::findFiles('file.txt')->from('files');
	Assert::same([
		'files/file.txt',
		'files/subdir/file.txt',
		'files/subdir/subdir2/file.txt',
	], export($finder));
});


test('recursive file search with depth limit', function () {
	$finder = Finder::findFiles('file.txt')->from('files')->limitDepth(1);
	Assert::same([
		'files/file.txt',
		'files/subdir/file.txt',
	], export($finder));
});


test('non-recursive file & directory search', function () {
	$finder = Finder::find('file.txt')->in('files');
	Assert::same([
		'files/file.txt',
	], export($finder));
});


test('recursive file & directory search', function () {
	$finder = Finder::find('file.txt')->from('files');
	Assert::same([
		'files/file.txt',
		'files/subdir/file.txt',
		'files/subdir/subdir2/file.txt',
	], export($finder));
});


test('recursive file & directory search in child-first order', function () {
	$finder = Finder::find('file.txt')->from('files')->childFirst();
	Assert::same([
		'files/file.txt',
		'files/subdir/file.txt',
		'files/subdir/subdir2/file.txt',
	], export($finder, false));
});


test('recursive file & directory search excluding folders', function () {
	$finder = Finder::find('file.txt')->from('files')->exclude('images')->exclude('subdir2');
	Assert::same([
		'files/file.txt',
		'files/subdir/file.txt',
	], export($finder));
});


test('non-recursive directory search', function () {
	$finder = Finder::findDirectories('subdir*')->in('files');
	Assert::same([
		'files/subdir',
	], export($finder));
});


test('non-recursive directory search alt', function () {
	$finder = (new Finder)->directories('subdir*')->in('files');
	Assert::same([
		'files/subdir',
	], export($finder));
});


test('recursive directory search', function () {
	$finder = Finder::findDirectories('subdir*')->from('files');
	Assert::same([
		'files/subdir',
		'files/subdir/subdir2',
	], export($finder));
});


test('getRelativePathName', function () {
	$res = [];
	foreach (Finder::findFiles('file.txt')->from('files') as $foo) {
		$res[$foo->getRelativePathName()] = true;
	}

	Assert::same(
		['file.txt', 'subdir/file.txt', 'subdir/subdir2/file.txt'],
		export($res),
	);
});


test('empty args', function () {
	$finder = Finder::find()->in('files');
	Assert::same([], export($finder));

	$finder = Finder::findFiles()->in('files');
	Assert::same([], export($finder));

	$finder = Finder::findDirectories()->in('files');
	Assert::same([], export($finder));

	$finder = Finder::find()->exclude()->in('files');
	Assert::same([], export($finder));
});
