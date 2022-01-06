<?php

/**
 * Test: Nette\Utils\Finder multiple batches.
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

	sort($arr);
	return $arr;
}


test('and', function () {
	$finder = Finder::findFiles('file.txt')
		->in('files')
		->and()
		->directories('subdir*')->from('files')
		->and()
		->files('file.txt')->from('files/*/subdir*');

	Assert::same([
		'files/file.txt',
		'files/subdir',
		'files/subdir/subdir2',
		'files/subdir/subdir2/file.txt',
	], export($finder));
});
