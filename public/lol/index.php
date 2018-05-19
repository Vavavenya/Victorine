<?php
$collection = [
	"0" => ["score" => 3],
	"1" => ["score" => 1],
	"2" => ["score" => 4],
	"3" => ["score" => 7],
	"4" => ["score" => 1],
];
echo "<pre>";
var_dump($collection);
echo "</pre>";
echo "__________<br>";
$collection2=["0" => ["score" => 3],];
echo "<pre>";
var_dump($collection2);
echo "</pre>";
echo "__________<br>";
foreach ($collection as $lol) {
	foreach ($collection2 as $key => $lol2) {
		echo "в первом массиве взяли эл. ".$lol["score"]."<br>";
		echo "в втором массиве взяли эл. ".$lol2["score"]."<br>";
		if ($lol["score"]>$lol2["score"] || $lol["score"]==$lol2["score"]) {
			echo $lol["score"]."больше или равен". $lol2["score"]."<br>";
			array_splice($collection2, $key, 0, [$lol]);
			echo "вставили".$lol["score"]." на позицию ".$key." перед ".$lol2["score"]."<br>";
			echo "<pre>";
			var_dump($collection2);
			echo "</pre>";
			echo "__________<br>";
		} else {
			//array_splice($collection2, $key, 0, [$lol]);
		};
	};
};
